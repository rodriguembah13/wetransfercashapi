<?php


namespace App\Service\Scripe;


use App\Entity\Contactcustomer;
use App\Repository\ContactcustomerRepository;
use Doctrine\ORM\EntityManagerInterface;
use DwollaSwagger\ApiClient;
use DwollaSwagger\Configuration;
use DwollaSwagger\CustomersApi;
use DwollaSwagger\FundingsourcesApi;
use DwollaSwagger\models\CreateCustomer;
use DwollaSwagger\models\CreateFundingSourceRequest;
use DwollaSwagger\models\TransferRequestBody;
use DwollaSwagger\TokensApi;
use DwollaSwagger\TransfersApi;
use GuzzleHttp\Client;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class CustomerService
{
    private ParameterBagInterface $params;
    public ApiClient $apiClient;
    private EntityManagerInterface $em;
    private $contactRepository;

    /**
     * CustomerService constructor.
     * @param ParameterBagInterface $params
     */
    public function __construct(ContactcustomerRepository $contactRepository,EntityManagerInterface $entityManager,ParameterBagInterface $params)
    {
        $this->params = $params;
        $this->em=$entityManager;
        $this->contactRepository=$contactRepository;
        Configuration::$username = $params->get('DWOLLA_PUBLIC');
        Configuration::$password = $params->get('DWOLLA_SECRET');
        $this->apiClient = new ApiClient("https://api-sandbox.dwolla.com");

    }

    public function createCustomer($data)
    {
        $apiClient = new ApiClient("https://api-sandbox.dwolla.com");
        $tokensApi = new TokensApi($apiClient);
        $appToken = $tokensApi->token();
        //$res = json_decode($appToken);
       // $data=$res['data'];
        Configuration::$access_token = $appToken->access_token;
        $customersApi = new CustomersApi($apiClient);
      //  $customers = $customersApi->_list(10);
        $location = $customersApi->create([
            'firstName' => 'Jennifer',
            'lastName' => 'Smith',
            'email' => 'jsmith@gmail.com',
            'phone' => '7188675309'
        ],
            [
                'Idempotency-Key' => '9051a62-3403-11e6-ac61-9e71128cae77'
            ]);
        return [$location];
    }
    public function createContact($data)
    {
        $apiClient = new ApiClient("https://api-sandbox.dwolla.com");
        $tokensApi = new TokensApi($apiClient);
        $appToken = $tokensApi->token();
        Configuration::$access_token = $appToken->access_token;
        $customersApi = new CustomersApi($apiClient);
        $founder= new FundingsourcesApi($apiClient);
        $email=$data['email'];
        $firstName=$data['firstName'];
        $lastName=$data['lastName'];
        $businessName=$data['businessName'];
        $routingNumber=$data['routingNumber'];
        $accountNumber=$data['accountNumber'];
        $type=$data['type'];
        $name=$data['name'];
      $location = $customersApi->create([
            'firstName' => $firstName,
            'lastName' => $lastName,
            'email' => $email,
            'type'=>"receive-only",
            'businessName' => $businessName,
            'phone' => '7188675309',
            "ipAddress"=> "99.99.99.99",
        ]);
        $req=new CreateFundingSourceRequest(
            [
                "routing_number"=>$routingNumber,
                "account_number"=>$accountNumber,
                "type"=>$type,
                "name"=>$name,
            ]
        );
       if (substr($location,12,7) === 'sandbox') {
            $iddwolla = substr($location,41);
        } else {
            $iddwolla =substr($location,33);
        }
        try {
            $founderval=$founder->createCustomerFundingSource($req,"2c076af3-7159-46d9-96b9-71f7817c8e21");
        }catch (\Exception $exception){
            return $exception;
        }
        if (substr($founderval,12,7) === 'sandbox') {
            $bankid = substr($founderval,47);
        } else {
            $bankid =substr($founderval,39);
        }
       // $bankid=$founderval->id;
        $contact=new Contactcustomer();
        $contact->setEmail($email);
        $contact->setLastname($lastName);
        $contact->setBankaccountnumber($accountNumber);
        $contact->setBankname($name);
        $contact->setBankroutingnumber($routingNumber);
        $contact->setBanktype($type);
        $contact->setBussinesname($businessName);
        $contact->setFirstname($firstName);
        $contact->setDwollaid($iddwolla);
        $contact->setBankid($bankid);
       $this->em->persist($contact);
        $this->em->flush();
        return $contact;
    }
    public function getContacts($id){
        return $this->contactRepository->findAll();
    }
    public function createTransfert($id){
        $baseurl=$this->params->get('DWOLLA_URL');
        $tokensApi = new TokensApi($this->apiClient);
        $appToken = $tokensApi->token();
        Configuration::$access_token = $appToken->access_token;
        $transfertApi=new TransfersApi($this->apiClient);
        $source=[
           "sources"=>[
               'href'=>$baseurl."funding-sources/"
           ]
        ];
        $destination=[
           "destination"=> [
               'href'=>$baseurl."funding-sources/"
           ]
        ];
        $_linl=[
            "sources"=>[
                'href'=>$baseurl."funding-sources/"
            ],
            "destination"=> [
                'href'=>$baseurl."funding-sources/"
            ]
        ];
        $transfertbody=new TransferRequestBody(
            [
                "_link"=>array_map('cube',$_linl),
                "amount"=>[
                    "currency"=>"USD",
                    "values"=>12.0
                ]
            ]
        );
        //$transfertApi->create($transfertbody);
        return $transfertbody;
    }
}
