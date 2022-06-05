<?php

namespace App\Controller;

use App\Entity\Contactcustomer;
use App\Entity\Customer;
use App\Entity\Transaction;
use App\Entity\User;
use App\Entity\Zone;
use App\Repository\ContactcustomerRepository;
use App\Repository\CountryRepository;
use App\Repository\CustomerRepository;
use App\Repository\EmployeRepository;
use App\Repository\TransactionRepository;
use App\Repository\UserRepository;
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

    /**
     * DefaultController constructor.
     * @param TransactionRepository $transactionRepository
     * @param UserRepository $userRepository
     * @param CustomerRepository $customerRepository
     * @param ContactcustomerRepository $contactcustomerRepository
     * @param EntityManagerInterface $entityManager
     * @param EmployeRepository $employeRepository
     */
    public function __construct(LoggerInterface $logger,CountryRepository $countryRepository,TransactionRepository $transactionRepository, UserRepository $userRepository, CustomerRepository $customerRepository,
                                ContactcustomerRepository $contactcustomerRepository, EntityManagerInterface $entityManager, EmployeRepository $employeRepository)
    {
        $this->employeRepository = $employeRepository;
        $this->em = $entityManager;
        $this->beneficiareRepository = $contactcustomerRepository;
        $this->customerRepository = $customerRepository;
        $this->userRepository = $userRepository;
        $this->transactionRepository = $transactionRepository;
        $this->countryRepository=$countryRepository;
        $this->logger=$logger;
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
     * @Rest\Get("/v1/transactions", name="app_transactions")
     */
    public function transactions(): Response
    {
        $data = $this->transactionRepository->findAll();
        $view = $this->view($data, Response::HTTP_OK, []);

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
        $transaction->setNumerotransaction($this->generateNumero());
        $transaction->setMontant($data['t_montant']);
        $country=$this->countryRepository->find($data['t_country']);
        $transaction->setCountry($country);
        $transaction->setBranche($data['t_branche']);
        $transaction->setModetransfert($data['t_modetransfert']);
        $transaction->setPrestateurservice($data['t_prestateurservice']);
        $transaction->setTypeservice($data['t_typeservice']);
        $transaction->setRaisontransaction($data['t_raisontransaction']);
        $user = $this->userRepository->find($data['user']);
        if (is_null($user)) {
            throw new InvalidArgumentException("Resource not found", 400);
        }

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
        }else{
            $customer=$this->customerRepository->find($data['customer']);
        }
        $this->logger->info("je suis ici");
        if (!empty($data['beneficiareexist'])) {
            $beneficiare = $this->beneficiareRepository->find($data['beneficiare']);
            if ($data['t_typetransaction']=="cardbank"){
                $transaction->setTypetransaction("bancaire");
            }else{
                $transaction->setTypetransaction($data['t_typetransaction']);
            }
        } else {
            $beneficiare = new Contactcustomer();
            $beneficiare->setEmail($data['b_email']);
            $beneficiare->setFirstname($data['b_firstName']);
            $beneficiare->setEmail($data['b_lastName']);
            $beneficiare->setPhone($data['b_phone']);
            if ($data['t_typetransaction']=="cardbank"){
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
                $beneficiare->setBanksignature($data['b_banksignature']);
                if (!empty($data['b_bankname'])){
                    $beneficiare->setBankname($data['b_bankname']);
                }
            }else{
                $transaction->setTypetransaction($data['t_typetransaction']);
            }

            $beneficiare->setCustomer($customer);
            $this->em->persist($beneficiare);
        }
        $transaction->setBeneficiare($beneficiare);
        $transaction->setCustomer($customer);
        $transaction->setDatetransaction(new \DateTime('now',new \DateTimeZone('Africa/Brazzaville')));
        $this->em->persist($transaction);
        $this->em->flush();
        $view = $this->view(['numero'=>$transaction->getNumerotransaction()], Response::HTTP_OK, []);
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
        $transaction = $this->transactionRepository->findOneBy(['numerotransaction'=>$id]);
        $transaction_ = [
            'numero' => $transaction->getNumerotransaction(),
            'type' => $transaction->getTypetransaction(),
            'numerocompte'=>$transaction->getBeneficiare()->getBankaccountnumber(),
            'montant'=>$transaction->getMontant(),
            'frais'=>$transaction->getId(),
            'pays'=>$transaction->getCountry()->getLibelle(),
            'motif'=>$transaction->getCustomer()->getMotif(),
            'montanttotal'=>$transaction->getMontant(),
            'datetransaction'=>$transaction->getDatetransaction()->format('Y-m-d H:m:s'),
            'b_name'=>$transaction->getBeneficiare()->getFirstname(),
            'b_lastname'=>$transaction->getBeneficiare()->getLastname(),
            'b_phone'=>$transaction->getBeneficiare()->getPhone(),
            'b_codeswift'=>$transaction->getBeneficiare()->getBankswiftcode(),
            'b_codeiban'=>$transaction->getBeneficiare()->getBankiban(),
            'e_name'=>$transaction->getCustomer()->getFirstname(),
            'e_lastname'=>$transaction->getCustomer()->getLastname(),
            'e_phone'=>$transaction->getCustomer()->getPhone(),
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
        for ($i = 1; $i <= 12; $i++) {
            $transaction_numero .= $allowed_characters[rand(0, count($allowed_characters) - 1)];
        }
        $txt=$transaction_numero.($last+1);
        return  str_pad($txt, 4, 0, STR_PAD_LEFT);
    }
}
