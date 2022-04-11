<?php

namespace App\Tests\E2E;

use App\Tests\Util\Database;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CommandTestCase extends KernelTestCase
{
    use Database;

    protected EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        self::bootKernel();

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
}
