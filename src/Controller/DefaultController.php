<?php

namespace App\Controller;

use App\Repository\EmployeRepository;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractFOSRestController
{
    private $employeRepository;

    /**
     * DefaultController constructor.
     * @param $employeRepository
     */
    public function __construct(EmployeRepository $employeRepository)
    {
        $this->employeRepository = $employeRepository;
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
}
