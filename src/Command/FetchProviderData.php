<?php

namespace App\Command;

use Doctrine\DBAL\DBALException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Repository\ProvidersRepository;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Doctrine\ORM\EntityManagerInterface;

class FetchProviderData extends Command
{

    protected static $defaultName = 'app:fetch-providers';

    const name = 'fetch-providers';

    /**
     * @var ProvidersRepository
     */
    private $providersRepository;
    /**
     * @var HttpClientInterface
     */
    private $client;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(ProvidersRepository $providersRepository,HttpClientInterface $client, EntityManagerInterface $entityManager)
    {
        parent::__construct(self::name);
        $this->providersRepository = $providersRepository;
        $this->client = $client;
        $this->entityManager = $entityManager;
    }

    protected function configure()
    {

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $providers = $this->providersRepository->findAll();

        $connection = $this->entityManager->getConnection();
        try {
            $platform = $connection->getDatabasePlatform();

            foreach ($providers as $provider)
            {
                $className = "App\\Entity\\" . $provider->getCode();
                $class = new $className($this->client);
                $data = $class->getData($provider->getUrl());

                try {
                    $connection->executeUpdate($platform->getTruncateTableSQL(strtolower($provider->getCode()), true));
                } catch (DBALException $e) {
                    echo $e->getMessage();
                }

                foreach ($data as $job)
                {
                    $class = new $className();
                    $job = $class->prepareJob($job);

                    foreach ($class->getFields() as $key => $method)
                    {
                        $class->$method($job[$key]);
                    }

                    $this->entityManager->persist($class);
                    $this->entityManager->flush();
                }

            }
        } catch (DBALException $e) {
            echo $e->getMessage();
        }

        return 0;
    }
}
