<?php

namespace App\Command;

use App\Controller\RetailCRMCreateTestWorkINeedDoItController;
use RetailCrm\ApiClient;
use RetailCrm\Exception\CurlException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class LookOrdersCommand extends Command
{
    public $url = 'https://weresttalk.retailcrm.ru/';
    public $key = 'iuGaclQUT9vJruuDPFv56CZMVdd43nRE';
    /**
     * @var ApiClient
     */
    public $client;

    protected static $defaultName = 'LookOrders';

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);


        try {
            //3
            $io->writeln("<info>Working...</info>");
            $this->ordersR();
            $io->success('UPDATED');



        } catch (CurlException $e) {
            $io->warning("Connection error: " . $e->getMessage());
        }

        return 0;
    }

    protected function ordersR(){
        $this->client = new ApiClient($this->url, $this->key);

        $f = array(
            'orderMethods' => 'phone',
            'extendedStatus' => 'assembling'
        );
        $orders = $this->client->request->ordersList($filter = $f);
        $orders = $orders->getResponse()['orders'];


        foreach ($orders as $item){
            try {
                $op = array(
                    'id' => $item['number'],
                    'email' => 'weresttrade@yandex.ru',
                    'managerComment'=> 'Комлектация началась ' . $item['statusUpdatedAt']
                );
                $this->client->request->ordersEdit($op, $by='id');

            } catch (CurlException $e) {
                echo "Connection error: " . $e->getMessage();
            }
        }
    }
}
