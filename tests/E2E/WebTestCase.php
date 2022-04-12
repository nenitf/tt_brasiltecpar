<?php

namespace App\Tests\E2E;

use App\Tests\Util\Database;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as TestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

class WebTestCase extends TestCase
{
    use Database;

    protected EntityManagerInterface $entityManager;
    protected KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();

        $this->entityManager = self::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->database_initDatabase($this->entityManager);
    }

    public function executeQuery(
        string $query,
    ) {
        return $this->database_executeQuery(
            $this->entityManager, $query
        );
    }

    public function request(
        string $method,
        string $endpoint,
        array $parameters = [],
        array $files = [],
        array $server = [],
        $content = null,
        $changeHistory = true
    ) {
        $this->client->request(
            $method,
            $endpoint,
            $parameters,
            $files,
            $server,
            $content,
            $changeHistory
        );
        $response = $this->client->getResponse();
        return json_decode($response->getContent());
    }
}

