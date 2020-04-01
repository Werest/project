<?php

namespace App\Command;

use App\Entity\TinyKangaroo;
use Composer\Autoload\ClassLoader;
use Doctrine\ORM\EntityManagerInterface;
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
    /**
     * @var ApiClient
     */
    public $client;

    protected static $defaultName = 'LookOrders';

    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        parent::__construct();
    }

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
        $entM = $this->entityManager;
        // for id = 1 - all in controller (RetailCRMCreateTestWorkINeedDoItController)
        $rep = $entM->getRepository(TinyKangaroo::class)->find(1);
        $url = $rep->getUrl();
        $key = $rep->getK();

        $this->client = new ApiClient($url, $key);

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
                    'email' => 'weresttrade@ya.com',
                    'managerComment'=> 'Комлектация началась ' . $item['statusUpdatedAt']
                );
                $this->client->request->ordersEdit($op, $by='id');

            } catch (CurlException $e) {
                echo "Connection error: " . $e->getMessage();
            }
        }
    }
}
