<?php


namespace App\Service\Scripe;


use GuzzleHttp\Client;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ProductService
{
    private ParameterBagInterface $params;
    /**
     * @var Client
     */
    private Client $client;

    /**
     * ProductService constructor.
     * @param $params
     * @param Client $client
     */
    public function __construct(ParameterBagInterface $params, Client $client)
    {
        $this->params = $params;
        $this->client = $client;
    }

}