<?php

namespace App\Tests\Util;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;

trait Database
{
    private function database_initDatabase(EntityManagerInterface $em): void
    {
        $metaData = $em
            ->getMetadataFactory()
            ->getAllMetadata();
        $schemaTool = new SchemaTool($em);
        $schemaTool->dropSchema($metaData);
        $schemaTool->createSchema($metaData);
    }

    public function database_executeQuery(
        EntityManagerInterface $em,
        string $query,
    ) {
        $stmt = $em
            ->getConnection()
            ->prepare($query);

        $execute = $stmt->execute();
        return $execute->fetchAll();
    }
}
