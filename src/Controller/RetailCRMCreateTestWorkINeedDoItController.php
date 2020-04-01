<?php

namespace App\Controller;

use App\Entity\Ola;
use RetailCrm\Exception\CurlException;
use RetailCrm\ApiClient;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RetailCRMCreateTestWorkINeedDoItController extends AbstractController
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
            $act = $active['integrationModule']['actions']['activity'];

            // 2 - 3 - with DB
            $code = $active['integrationModule']['code'];
            $name = $active['integrationModule']['name'];
            $logo = $active['integrationModule']['logo'];
            $freeze = $active['integrationModule']['freeze'];

            $entM = $this->getDoctrine()->getManager();

            $ola = new Ola();
            $ola->setCode($code);
            $ola->setName($name);
            $ola->setLogo($logo);
            $ola->setActive($act);
            $ola->setFreeze($freeze);

            $entM->persist($ola);
            $entM->flush();



        } catch (CurlException $e) {
            echo "Connection error: " . $e->getMessage();
        }

        return $this->render('retail_crm_create_test_work_i_need_do_it/index.html.twig', [
            'controller_name' => 'RetailCRMCreateTestWorkINeedDoItController',
            'ola' => $ola->getId()
        ]);
    }


}
