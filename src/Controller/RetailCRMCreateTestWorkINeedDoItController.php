<?php

namespace App\Controller;

use App\Entity\Ola;
use App\Entity\TinyKangaroo;
use RetailCrm\Exception\CurlException;
use RetailCrm\ApiClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RetailCRMCreateTestWorkINeedDoItController extends AbstractController
{
    /**
     * @var ApiClient
     */
    private $client;

    public function index()
    {
        //url and key select from db

        $rep = $this->getDoctrine()->getRepository(TinyKangaroo::class)->findAll();
        foreach ($rep as $item) {
            $url = $item->getUrl();
            $key = $item->getK();


            $this->client = new ApiClient($url, $key);

            try {
                $code = 'consultant';
                //1 - non DB
                $r = array(
                    "code" => "awesometransport-101",
                    "name" => "Awesome Transport",
                    "logo" => "https://image.flaticon.com/icons/svg/1793/1793188.svg",
                    "clientId" => 10,
                    "active" => true

                );
                $this->client->request->integrationModulesEdit($r);

                $active = $this->client->request->integrationModulesGet($code)->getResponse();


                // 2 - 3 - with DB
                $code = $active['integrationModule']['code'];
                $name = $active['integrationModule']['name'];
                $logo = $active['integrationModule']['logo'];
                $freeze = $active['integrationModule']['freeze'];
                $act = $active['integrationModule']['actions']['activity'];

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
        }

        return $this->render('retail_crm_create_test_work_i_need_do_it/index.html.twig', [
            'controller_name' => 'RetailCRMCreateTestWorkINeedDoItController',
            'ola' => $ola->getId()
        ]);
    }
}
