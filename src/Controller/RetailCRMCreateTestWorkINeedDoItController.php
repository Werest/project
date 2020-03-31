<?php

namespace App\Controller;

use RetailCrm\Exception\CurlException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use RetailCrm\ApiClient;

class RetailCRMCreateTestWorkINeedDoItController
{
    public $url = 'https://weresttalk.retailcrm.ru/';
    public $key = 'iuGaclQUT9vJruuDPFv56CZMVdd43nRE';
    /**
     * @var ApiClient
     */
    public $client;


    public function index()
    {
        $this->client = new ApiClient($this->url, $this->key);

        try {
            $code = 'consultant';
            //1 - non DB
            $r = array(
                "code" => "awesometransport-101",
                "name" => "Awesome Transport",
                "logo"=> "https://image.flaticon.com/icons/svg/1793/1793188.svg",
                "clientId" => 10,
                "active" => true

            );
            $this->client->request->integrationModulesEdit($r);

            $active = $this->client->request->integrationModulesGet($code)->getResponse();

            //2 non DB
            $active = $active['integrationModule']['actions']['activity'];



        } catch (CurlException $e) {
            echo "Connection error: " . $e->getMessage();
        }



        return $this->render('retail_crm_create_test_work_i_need_do_it/index.html.twig', [
            'controller_name' => 'RetailCRMCreateTestWorkINeedDoItController'
        ]);
    }


}
