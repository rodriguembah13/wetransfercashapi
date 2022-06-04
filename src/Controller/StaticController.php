<?php


namespace App\Controller;


use App\Entity\Country;
use App\Entity\Grilletarifaire;
use App\Entity\Tauxechange;
use App\Entity\Zone;
use App\Repository\CountryRepository;
use App\Repository\GrilletarifaireRepository;
use App\Repository\TauxechangeRepository;
use App\Repository\ZoneRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class StaticController extends AbstractFOSRestController
{
    private $grilletarifaireRepository;
    private $tauxRepository;
    private $countryRepository;
    private $zoneRepository;
    private EntityManagerInterface $em;
    /**
     * StaticController constructor.
     * @param $grilletarifaireRepository
     * @param $tauxRepository
     */
    public function __construct(CountryRepository $countryRepository,ZoneRepository $zoneRepository,EntityManagerInterface $entityManager,GrilletarifaireRepository $grilletarifaireRepository, TauxechangeRepository $tauxRepository)
    {
        $this->grilletarifaireRepository = $grilletarifaireRepository;
        $this->tauxRepository = $tauxRepository;
        $this->countryRepository=$countryRepository;
        $this->zoneRepository=$zoneRepository;
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
     * @Rest\Get("/v1/countries", name="app_country")
     */
    public function countries(): Response
    {
        $countries=$this->countryRepository->findAll();
        $view = $this->view($countries, Response::HTTP_OK, []);
        return $this->handleView($view);
    }
    /**
     * @Rest\Get("/v1/zones", name="app_zones")
     */
    public function zones(): Response
    {
        $zones=$this->zoneRepository->findAll();
        $view = $this->view($zones, Response::HTTP_OK, []);
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
        if ($data['zone']){
            $zone=$this->zoneRepository->find($data['zone']);
            $taux->setZone($zone);
        }
        $taux->setMontant2($data['montantinter']);
        $taux->setSymbole($data['symbole']);
        $this->em->persist($taux);
        $this->em->flush();
        $view = $this->view($taux, Response::HTTP_OK, []);
        return $this->handleView($view);
    }
    /**
     * @Rest\Post("/v1/zones", name="app_zones_new")
     * @param Request $request
     * @return Response
     */
    public function createZone(Request $request): Response
    {
        $res = json_decode($request->getContent(), true);
        $data=$res['data'];
        if ($data['id']){
            $zone=$this->zoneRepository->find($data['id']);
        }else{
            $zone=new Zone();
        }
        $zone->setLibelle($data['libelle']);
        $zone->setCode($data['code']);
        $this->em->persist($zone);
        $this->em->flush();
        $view = $this->view($zone, Response::HTTP_OK, []);
        return $this->handleView($view);
    }
    /**
     * @Rest\Post("/v1/countries", name="app_countries_new")
     * @param Request $request
     * @return Response
     */
    public function createCountries(Request $request): Response
    {
        $res = json_decode($request->getContent(), true);
        $data=$res['data'];
        if ($data['id']){
            $country=$this->countryRepository->find($data['id']);
        }else{
            $country=new Country();
        }
        $country->setLibelle($data['libelle']);
        $country->setCode($data['code']);
        $country->setFlag($data['flag']);
        if ($data['zone']){
            $zone=$this->zoneRepository->find($data['zone']);
            $country->setZone($zone);
        }
        $country->setCode($data['code']);
        $country->setMonaire($data['monaire']);
        $this->em->persist($country);
        $this->em->flush();
        $view = $this->view($country, Response::HTTP_OK, []);
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
