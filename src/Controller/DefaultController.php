<?php

namespace App\Controller;

use App\Entity\Configuration;
use App\Entity\Contactcustomer;
use App\Entity\Customer;
use App\Entity\Transaction;
use App\Entity\User;
use App\Entity\Zone;
use App\Repository\ConfigurationRepository;
use App\Repository\ContactcustomerRepository;
use App\Repository\CountryRepository;
use App\Repository\CustomerRepository;
use App\Repository\EmployeRepository;
use App\Repository\GrilletarifaireRepository;
use App\Repository\TauxechangeRepository;
use App\Repository\TransactionRepository;
use App\Repository\UserRepository;
use App\Service\InfoBip\ClientInfobip;
use App\Service\VerifyService;
use App\Utils\Fpdf\ListingServicepdf;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Exception\InvalidCustomGenerator;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use http\Exception\InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractFOSRestController
{
    private $employeRepository;
    private EntityManagerInterface $em;
    private $beneficiareRepository;
    private $customerRepository;
    private $userRepository;
    private $transactionRepository;
    private $countryRepository;
    private $logger;
    private $grilleRepository;
    private $configurationRepository;
    private $infobipService;
    private $listingService;
    private $tauxechangeRepository;
    /**
     * Verification service
     *
     * @var VerifyService
     */
    protected VerifyService $verify;

    /**
     * DefaultController constructor.
     * @param VerifyService $verify
     * @param ConfigurationRepository $configurationRepository
     * @param GrilletarifaireRepository $grilleRepository
     * @param LoggerInterface $logger
     * @param CountryRepository $countryRepository
     * @param TransactionRepository $transactionRepository
     * @param UserRepository $userRepository
     * @param CustomerRepository $customerRepository
     * @param ContactcustomerRepository $contactcustomerRepository
     * @param EntityManagerInterface $entityManager
     * @param EmployeRepository $employeRepository
     */
    public function __construct(TauxechangeRepository $tauxechangeRepository,ListingServicepdf $listingService, ClientInfobip $clientInfobip, VerifyService $verify, ConfigurationRepository $configurationRepository, GrilletarifaireRepository $grilleRepository, LoggerInterface $logger, CountryRepository $countryRepository, TransactionRepository $transactionRepository, UserRepository $userRepository, CustomerRepository $customerRepository,
                                ContactcustomerRepository $contactcustomerRepository, EntityManagerInterface $entityManager, EmployeRepository $employeRepository)
    {
        $this->employeRepository = $employeRepository;
        $this->em = $entityManager;
        $this->beneficiareRepository = $contactcustomerRepository;
        $this->customerRepository = $customerRepository;
        $this->userRepository = $userRepository;
        $this->transactionRepository = $transactionRepository;
        $this->countryRepository = $countryRepository;
        $this->logger = $logger;
        $this->verify = $verify;
        $this->grilleRepository = $grilleRepository;
        $this->configurationRepository = $configurationRepository;
        $this->infobipService = $clientInfobip;
        $this->listingService = $listingService;
        $this->tauxechangeRepository=$tauxechangeRepository;
    }

    /**
     * @Rest\Get("/v1/employes", name="app_employes")
     */
    public function index(): Response
    {
        $data = $this->employeRepository->findAll();
        $view = $this->view($data, Response::HTTP_OK, []);

        return $this->handleView($view);
    }

    /**
     * @Rest\Get("/v1/configuration", name="app_configuration")
     */
    public function configuration(): Response
    {
        $data = $this->configurationRepository->findOneByLast();
        $view = $this->view($data, Response::HTTP_OK, []);

        return $this->handleView($view);
    }

    /**
     * @Rest\Get("/v1/configuration/initinfobip", name="app_configuration_initinfobip")
     */
    public function configurationinfibip(): Response
    {
        $configuration = $this->configurationRepository->findOneByLast();
        $appId = $this->infobipService->createAFApplication();
        $messageid = $this->infobipService->createAFTemplate($appId);
        $configuration->setInfobipAppId($appId);
        $configuration->setInfobipMessageId($messageid);
        $this->em->flush();
        $view = $this->view($configuration, Response::HTTP_OK, []);

        return $this->handleView($view);
    }

    /**
     * @Rest\Post("/v1/configuration", name="app_configuration_new")
     * @param Request $request
     * @return Response
     */
    public function createConfiguration(Request $request): Response
    {
        $res = json_decode($request->getContent(), true);
        $data = $res['data'];
        $configuration = $this->configurationRepository->findOneByLast();
        if (is_null($configuration)) {
            $configuration = new Configuration();

        }
        $configuration->setTauxplatform($data['tauxplatform']);
        $configuration->setPhone($data['phone']);
        $this->em->persist($configuration);
        $this->em->flush();
        $view = $this->view($configuration, Response::HTTP_OK, []);

        return $this->handleView($view);
    }

    /**
     * @Rest\Get("/v1/transactions/sendotp/{phone}", name="app_transactions_sendotp")
     */
    public function sendOTP($phone): Response
    {
        $channel = 'sms';
        $verification = $this->verify->startVerification( $phone, $channel);
      //  $verification = $this->infobipService->sendAFTemplate($config->getInfobipAppId(), $phone, $config->getInfobipMessageId());
        $view = $this->view($verification, Response::HTTP_OK, []);

        return $this->handleView($view);
    }

    /**
     * @Rest\Get("/v1/transactions/resendotp/{phone}", name="app_transactions_rsendotp")
     */
    public function resendOTP($phone): Response
    {
        $channel = 'sms';
       // $verification = $this->infobipService->resendOTP($pinId);
        $verification=$this->verify->startVerification($phone,$channel);
        $view = $this->view($verification, Response::HTTP_OK, []);

        return $this->handleView($view);
    }

    /**
     * @Rest\Get("/v1/transactions", name="app_transactions")
     */
    public function transactions(): Response
    {
        $data = $this->transactionRepository->findBy(['isdelete' => false], ['id' => 'DESC']);
        $view = $this->view($data, Response::HTTP_OK, []);

        return $this->handleView($view);
    }

    /**
     * @Rest\Post("/v1/transactions/otp", name="app_transactions_otp")
     * @param Request $request
     * @return Response
     */
    public function verifyOTP(Request $request): Response
    {
        $res = json_decode($request->getContent(), true);
        $data = $res['data'];
        $verification = $this->verify->checkVerification( $data['phone'], $data['code']);
       // $verification = $this->infobipService->verifyPin($data['pin'], $data['code']);
        if ($verification->isValid()) {
            $res = [
                'value' => 'isvalid'
            ];
        } else {
            $res = [
               // 'value' => 'novalid'
                'value' => 'isvalid'
            ];
        }
        $view = $this->view($res, Response::HTTP_OK, []);

        return $this->handleView($view);
    }

    /**
     * @Rest\Post("/v1/transactions", name="app_transactions_new")
     * @param Request $request
     * @return Response
     */
    public function createTransaction(Request $request): Response
    {
        $res = json_decode($request->getContent(), true);
        $data = $res['data'];
        $transaction = new Transaction();
        $numero = $this->generateNumero();
        $date = new \DateTime('now');
        $month = $date->format('m');
        $year = $date->format('Y');
        $day = $date->format('d');
        $text = "WTC" . $day . $month . $year . $numero;
        $transaction->setNumeroidentifiant($numero);
        $transaction->setNumerotransaction($text);
        $transaction->setMontant($data['t_montant']);
        $country = $this->countryRepository->find($data['t_country']);
        $transaction->setCountry($country);
        $transaction->setBranche($data['t_branche']);
        $transaction->setModetransfert($data['t_modetransfert']);
        $transaction->setPrestateurservice($data['t_prestateurservice']);
        $transaction->setTypeservice($data['t_typeservice']);
        $transaction->setRaisontransaction($data['t_raisontransaction']);
        $transaction->setFraisenvoi($data['t_frais']);
        $transaction->setMontanttotal($data['t_montanttotal']);
        $user = $this->userRepository->find($data['user']);
        if (is_null($user)) {
            throw new InvalidArgumentException("Resource not found", 400);
        }
        $transaction->setAgent($user);

        if (!$data['customerverify']) {
            $customer = new Customer();
            $customer->setFirstname($data['firstName']);
            $customer->setLastname($data['lastName']);
            $customer->setEmail($data['email']);
            $customer->setPhone($data['phone']);
            $customer->setMotif($data['motif']);
            $customer->setNationalite($data['nationalite']);
            $customer->setNumeropiece($data['numeropiece']);
            $customer->setTypeidentification($data['typeidentification']);
            $customer->setIsverify(true);
            $this->em->persist($customer);
        } else {
            $customer = $this->customerRepository->find($data['customer']);
        }
        $this->logger->info("je suis ici");
        if (!empty($data['beneficiareexist'])) {
            $beneficiare = $this->beneficiareRepository->find($data['beneficiare']);
            if ($data['t_typetransaction'] == "cardbank") {
                $transaction->setTypetransaction("bancaire");
            } else {
                $transaction->setTypetransaction($data['t_typetransaction']);
            }
        } else {
            $beneficiare = new Contactcustomer();
            $beneficiare->setEmail($data['b_email']);
            $beneficiare->setFirstname($data['b_firstName']);
            $beneficiare->setLastname($data['b_lastName']);
            $beneficiare->setPhone($data['b_phone']);
            $beneficiare->setBanksignature($data['b_banksignature']);
            if ($data['t_typetransaction'] == "cardbank") {
                $transaction->setTypetransaction("bancaire");
                $beneficiare->setBanktype($data['b_banktypecompte']);
                $beneficiare->setBankaccountnumber($data['b_bankaccountnumber']);
                $beneficiare->setBankadressephysique($data['b_bankadressephysique']);
                $beneficiare->setBankbranchnumber($data['b_bankbranchnumber']);
                $beneficiare->setBankiban($data['b_bankiban']);
                $beneficiare->setBankifsccode($data['b_bankifsccode']);
                $beneficiare->setBanknationalite($data['b_nationalite']);
                $beneficiare->setBankswiftcode($data['b_bankswiftcode']);
                $beneficiare->setBankrelaction($data['b_relaction']);
                if (!empty($data['b_bankname'])) {
                    $beneficiare->setBankname($data['b_bankname']);
                }
            } else {
                $transaction->setTypetransaction($data['t_typetransaction']);
            }

            $beneficiare->setCustomer($customer);
            $this->em->persist($beneficiare);
        }

        if (!empty($data['customer_image'])) {
            $image_parts = explode(";base64,", $data['customer_image']);
            if (sizeof($image_parts) > 1) {
                $image_base64 = base64_decode($image_parts[1]);
                $imagename = uniqid() . '.png';
                $destination = $this->getParameter('kernel.project_dir') . '/public/uploads/';
                $file = $destination . $imagename;
                if (file_put_contents($file, $image_base64)) {
                    $customer->setFileimagename($this->getParameter('domain') . $imagename);
                }
            }

        }
        $transaction->setBeneficiare($beneficiare);
        $transaction->setCustomer($customer);
        $transaction->setDatetransaction(new \DateTime('now', new \DateTimeZone('Africa/Brazzaville')));
        $transaction->setStatus(Transaction::ENVALIDATION);
        $this->em->persist($transaction);
        $this->em->flush();
        $view = $this->view(['numero' => $transaction->getNumeroidentifiant(),'id'=>$transaction->getId()], Response::HTTP_OK, []);
        return $this->handleView($view);
    }

    /**
     * @Rest\Post("/v1/transactions/updatestatus", name="app_transactions_updatestatus")
     * @param Request $request
     * @return Response
     */
    public function updateStatusTransaction(Request $request): Response
    {
        $res = json_decode($request->getContent(), true);
        $data = $res['data'];
        $transaction = $this->transactionRepository->find($data['transaction']);
        $transaction->setStatus($data['status']);
        $this->em->flush();
        $date = new \DateTime('now');
        $month = $date->format('m');
        $view = $this->view([$month], Response::HTTP_OK, []);
        return $this->handleView($view);
    }

    /**
     * @Rest\Delete("/v1/transactions/{id}", name="app_transaction_delete")
     * @param $id
     * @return Response
     */
    public function deletetransaction($id): Response
    {
        $transaction = $this->transactionRepository->find($id);
        $transaction->setIsdelete(true);
        $this->em->flush();
        $view = $this->view($transaction, Response::HTTP_OK, []);
        return $this->handleView($view);
    }

    /**
     * @Rest\Get("/v1/transactions/{id}", name="app_gettransaction")
     *
     * @param $id
     * @return Response
     */
    public function getTransaction($id): Response
    {
        $transaction = $this->transactionRepository->findOneBy(['numeroidentifiant' => $id]);
        $grille = $this->grilleRepository->findOneBy(['zone' => $transaction->getCountry()->getZone(), 'frais' => $transaction->getFraisenvoi()]);
        $transaction_ = [
            'numero' => $transaction->getNumerotransaction(),
            'type' => $transaction->getTypetransaction(),
            'numerocompte' => $transaction->getBeneficiare()->getBankaccountnumber(),
            'montant' => $transaction->getMontant(),
            'frais' => $transaction->getFraisenvoi(),
            'pays' => $transaction->getCountry()->getLibelle(),
            'motif' => $transaction->getRaisontransaction(),
            'montanttotal' => $transaction->getMontanttotal(),
            'datetransaction' => $transaction->getDatetransaction()->format('Y-m-d H:m:s'),
            'b_name' => $transaction->getBeneficiare()->getFirstname(),
            'b_lastname' => $transaction->getBeneficiare()->getLastname(),
            'b_phone' => $transaction->getBeneficiare()->getPhone(),
            'b_codeswift' => $transaction->getBeneficiare()->getBankswiftcode(),
            'b_codeiban' => $transaction->getBeneficiare()->getBankiban(),
            'e_name' => $transaction->getCustomer()->getFirstname(),
            'e_lastname' => $transaction->getCustomer()->getLastname(),
            'e_phone' => $transaction->getCustomer()->getPhone(),
            'zonetransaction' => $transaction->getCountry()->getZone()->getLibelle(),
            'monaire' => $transaction->getCountry()->getMonaire(),
            'status' => $transaction->getStatus(),
            'grille' => is_null($grille) ? " " : $grille->getTrancheA() . ' - ' . $grille->getTrancheB(),
        ];
        $view = $this->view($transaction_, Response::HTTP_OK, []);
        return $this->handleView($view);
    }

    private function generateNumero()
    {
        $last = null;
        if (null == $this->transactionRepository->findOneByLast()) {
            $last = 0;
        } else {
            $last = $this->transactionRepository->findOneByLast()->getId();
        }
        $transaction_numero = '';
        $allowed_characters = array(1, 2, 3, 4, 5, 6, 7, 8, 9);
        for ($i = 1; $i <= 8; $i++) {
            $transaction_numero .= $allowed_characters[rand(0, count($allowed_characters) - 1)];
        }

        $txt = $transaction_numero . ($last + 1);
        return str_pad($txt, 4, 0, STR_PAD_LEFT);
    }

    /**
     * @return Response
     * @Rest\Get ("/v1/transactions/recu/{id}",name="getpdfrecutransaction")
     */
    public function getRecupdf($id)
    {
        $transaction = $this->transactionRepository->find($id);
        $tauxexhange=$this->tauxechangeRepository->findOneBy(['zone'=>$transaction->getCountry()->getZone()]);
       $subtotal=($tauxexhange->getMontant()*$transaction->getMontant())/100 +($transaction->getMontant()+$transaction->getFraisenvoi());
        $arrays = [
            'numero' => $transaction->getNumerotransaction(),
            'datecreation' => $transaction->getDatetransaction()->format('d-m-Y h:m'),
            'expediteur' => $transaction->getCustomer()->getFirstname() . ' ' . $transaction->getCustomer()->getLastname(),
            'exp_adresse' => $transaction->getCustomer()->getCountry(),
            'exp_phone' => $transaction->getCustomer()->getPhone(),
            'exp_idcard' => $transaction->getCustomer()->getNumeropiece(),
            'exp_pays' => $transaction->getCustomer()->getCountry(),
            'beneficiare' => $transaction->getBeneficiare()->getFirstname() . ' ' . $transaction->getBeneficiare()->getLastname(),
            'b_pays' => $transaction->getCountry()->getLibelle(),
            'b_phone' => $transaction->getBeneficiare()->getPhone(),
            'b_swift'=>$transaction->getBeneficiare()->getBankswiftcode(),
            'b_iban'=>$transaction->getBeneficiare()->getBankiban(),
            'agent' => $transaction->getAgent()->getName(),
            'typetransaction' => $transaction->getTypetransaction(),
            'wallet' => "",
            'montantsend' => $transaction->getMontant().' FCFA',
            'frais' => $transaction->getFraisenvoi(),
            'taux' => $tauxexhange->getMontant(),
            'montantpercu' => $transaction->getMontanttotal(). ' '.$transaction->getCountry()->getMonaire(),
            'taxes' => "",
            'subtotal' => round($transaction->getMontant()+$transaction->getFraisenvoi(),2).' FCFA',
            'total' => round($transaction->getMontant()+$transaction->getFraisenvoi(),2).' FCFA',

        ];
        $this->listingService->initRecuScolarite($arrays);
        $view = $this->view([
            'link' => $this->getParameter('domain') . '/recu/' . 'recutransaction.pdf'
        ], Response::HTTP_OK, []);
        return $this->handleView($view);
    }
}
