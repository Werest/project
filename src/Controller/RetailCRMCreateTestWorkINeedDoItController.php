<?php

namespace App\Controller;

use RetailCrm\Exception\CurlException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use RetailCrm\ApiClient;

class RetailCRMCreateTestWorkINeedDoItController extends AbstractController
{
    public function index()
    {
        $url = 'https://weresttalk.retailcrm.ru/';
        $key = 'iuGaclQUT9vJruuDPFv56CZMVdd43nRE';
        $client = new ApiClient($url, $key);



        try {
            $code = 'consultant';
            $r = array(
                "code" => "awesometransport-101",
                "name" => "Awesome Transport",
                "logo"=> "https://image.flaticon.com/icons/svg/1793/1793188.svg",
                "clientId" => 10,
                "active" => true

            );
            $client->request->integrationModulesEdit($r);

            $active = $client->request->integrationModulesGet($code)->getResponse();

            //2
            $active = $active['integrationModule']['actions']['activity'];

        } catch (CurlException $e) {
            echo "Connection error: " . $e->getMessage();
        }



        return $this->render('retail_crm_create_test_work_i_need_do_it/index.html.twig', [
            'controller_name' => 'RetailCRMCreateTestWorkINeedDoItController'
        ]);
    }
}
