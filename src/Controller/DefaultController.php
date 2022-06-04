<?php

namespace App\Controller;

use App\Entity\Contactcustomer;
use App\Entity\Transaction;
use App\Entity\Zone;
use App\Repository\ContactcustomerRepository;
use App\Repository\CustomerRepository;
use App\Repository\EmployeRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
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
    /**
     * DefaultController constructor.
     * @param $employeRepository
     */
    public function __construct(CustomerRepository $customerRepository, ContactcustomerRepository $contactcustomerRepository,EntityManagerInterface $entityManager,EmployeRepository $employeRepository)
    {
        $this->employeRepository = $employeRepository;
        $this->em=$entityManager;
        $this->beneficiareRepository=$contactcustomerRepository;
        $this->customerRepository=$customerRepository;
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
     * @Rest\Post("/v1/transactions", name="app_transactions_new")
     * @param Request $request
     * @return Response
     */
    public function createTransaction(Request $request): Response
    {
        $res = json_decode($request->getContent(), true);
        $data=$res['data'];
            $transaction=new Transaction();

        $transaction->setMontant($data['t_montant']);
        $transaction->setCountry($data['t_country']);
        $transaction->setBranche($data['t_branche']);
        $transaction->setModetransfert($data['t_modetransfert']);
        $transaction->setPrestateurservice($data['t_prestateurservice']);
        $transaction->setTypeservice($data['t_typeservice']);
        $customer=$this->customerRepository->find($data['customer']);
        if (!$data['customerverify']){
            $customer->setMotif($data['motif']);
            $customer->setNationalite($data['nationalite']);
            $customer->setNumeropiece($data['numeropiece']);
            $customer->setTypeidentification($data['typeidentification']);
            $customer->setIsverify(true);
        }
        if ($data['beneficiareexist']){
            $beneficiare=$this->beneficiareRepository->find(['beneficiare']);
        }else{
            $beneficiare=new Contactcustomer();
            $beneficiare->setEmail($data['b_email']);
            $beneficiare->setFirstname($data['b_firstName']);
            $beneficiare->setEmail($data['b_lastName']);
            $beneficiare->setPhone($data['b_phone']);
            $beneficiare->setBankname($data['b_bankname']);
            $beneficiare->setBanktype($data['b_banktype']);
            $beneficiare->setBankaccountnumber($data['b_bankaccountnumber']);
            $beneficiare->setBankadressephysique($data['b_bankadressephysique']);
            $beneficiare->setBankbranchnumber($data['b_bankbranchnumber']);
            $beneficiare->setBankiban($data['b_bankiban']);
            $beneficiare->setBankifsccode($data['b_bankifsccode']);
            $beneficiare->setBanknationalite($data['b_nationalite']);
            $beneficiare->setBankswiftcode($data['b_bankswiftcode']);
            $beneficiare->setBankrelaction($data['b_relaction']);
            $beneficiare->setBanksignature($data['b_banksignature']);
            $beneficiare->setCustomer($customer);
            $this->em->persist($beneficiare);
        }
        $transaction->setBeneficiare($beneficiare);
        $transaction->setCustomer($customer);
        $this->em->persist($transaction);
        $this->em->flush();
        $view = $this->view($transaction, Response::HTTP_OK, []);
        return $this->handleView($view);
    }
}
