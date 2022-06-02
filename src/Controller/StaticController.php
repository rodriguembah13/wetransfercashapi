<?php


namespace App\Controller;


use App\Entity\Grilletarifaire;
use App\Entity\Tauxechange;
use App\Repository\GrilletarifaireRepository;
use App\Repository\TauxechangeRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class StaticController extends AbstractFOSRestController
{
    private $grilletarifaireRepository;
    private $tauxRepository;
    private EntityManagerInterface $em;
    /**
     * StaticController constructor.
     * @param $grilletarifaireRepository
     * @param $tauxRepository
     */
    public function __construct(EntityManagerInterface $entityManager,GrilletarifaireRepository $grilletarifaireRepository, TauxechangeRepository $tauxRepository)
    {
        $this->grilletarifaireRepository = $grilletarifaireRepository;
        $this->tauxRepository = $tauxRepository;
        $this->em=$entityManager;
    }

    /**
     * @Rest\Get("/v1/grilletarifaires", name="app_grilletarifaire")
     */
    public function grilletarifaire(): Response
    {
        $grilletarifaires=$this->grilletarifaireRepository->findAll();
        $view = $this->view($grilletarifaires, Response::HTTP_OK, []);
        return $this->handleView($view);
    }
    /**
     * @Rest\Get("/v1/tauxechanges", name="app_tauxechange")
     */
    public function tauxechange(): Response
    {
        $tauxechanges=$this->tauxRepository->findAll();
        $view = $this->view($tauxechanges, Response::HTTP_OK, []);
        return $this->handleView($view);
    }
    /**
     * @Rest\Post("/v1/tauxechanges", name="app_tauxechange_new")
     * @param Request $request
     * @return Response
     */
    public function createTauxechange(Request $request): Response
    {
        $res = json_decode($request->getContent(), true);
        $data=$res['data'];
        if ($data['id']){
            $taux=$this->tauxRepository->find($data['id']);
        }else{
            $taux=new Tauxechange();
        }
        $taux->setMontant($data['montant']);
        $taux->setCode($data['code']);
        $taux->setMontant2($data['montantinter']);
        $taux->setSymbole($data['symbole']);
        $this->em->persist($taux);
        $this->em->flush();
        $view = $this->view($taux, Response::HTTP_OK, []);
        return $this->handleView($view);
    }
    /**
     * @Rest\Post("/v1/grilletarifaires", name="app_grilletarifaires_new")
     * @param Request $request
     * @return Response
     */
    public function createGrille(Request $request): Response
    {
        $res = json_decode($request->getContent(), true);
        $data=$res['data'];
        if ($data['id']){
            $grille=$this->grilletarifaireRepository->find($data['id']);
        }else{
            $grille=new Grilletarifaire();
        }
        $grille->setFrais($data['frais']);
        $grille->setTrancheA($data['tranche_a']);
        $grille->setTrancheB($data['tranche_b']);
        $grille->setZone($data['zone']);
        $this->em->persist($grille);
        $this->em->flush();
        $view = $this->view($grille, Response::HTTP_OK, []);
        return $this->handleView($view);
    }
}
