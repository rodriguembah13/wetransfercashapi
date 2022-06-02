<?php

namespace App\Controller;

use App\Entity\Contactcustomer;
use App\Service\Scripe\CustomerService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CustomerController extends AbstractFOSRestController
{
    private $customerService;
    private $logger;

    /**
     * CustomerController constructor.
     * @param $customerService
     */
    public function __construct(LoggerInterface $logger,CustomerService $customerService)
    {
        $this->customerService = $customerService;
        $this->logger=$logger;
    }

    /**
     * @Rest\Get("/v1/customers", name="app_customer")
     */
    public function index(): Response
    {
        $customers=[];
        $view = $this->view($customers, Response::HTTP_OK, []);
        return $this->handleView($view);
    }
    /**
     * @Rest\Get("/v1/customers/contact/{id}", name="app_customer_getcontact")
     */
    public function getcontacts(Request $request): Response
    {
        $customers=$this->customerService->getContacts($request->get('id'));
        $view = $this->view($customers, Response::HTTP_OK, []);
        return $this->handleView($view);
    }
    /**
     * @Rest\Post("/v1/customers", name="app_customer_new")
     * @param Request $request
     * @return Response
     */
    public function createCustomer(Request $request): Response
    {
        $res = json_decode($request->getContent(), true);
        $data=$res['data'];
        $this->logger->debug("icic");
        $customer=$this->customerService->createCustomer($data);
        $view = $this->view($customer, Response::HTTP_OK, []);
        return $this->handleView($view);
    }
    /**
     * @Rest\Post("/v1/customers/contact", name="app_customer_new_contact")
     * @param Request $request
     * @return Response
     */
    public function createContatct(Request $request): Response
    {
        $res = json_decode($request->getContent(), true);
        $data=$res['data'];
        $this->logger->debug("contact".$data["name"]);
        $contact=$this->customerService->createContact($data);
        $view = $this->view($contact, Response::HTTP_OK, []);
        return $this->handleView($view);
    }
    /**
     * @Rest\Post("/v1/transferts", name="app_customer_new_transfert")
     * @param Request $request
     * @return Response
     */
    public function createTransfert(Request $request): Response
    {
        $res = json_decode($request->getContent(), true);
        $data=$res['data'];
        $contact=$this->customerService->createTransfert($data);
        $view = $this->view($contact, Response::HTTP_OK, []);
        return $this->handleView($view);
    }
}
