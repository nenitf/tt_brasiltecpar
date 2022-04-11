<?php

namespace App\Repository;

use App\Core\Model\ResultadoCli;
use App\Core\Repository\IResultadosCliRepository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ResultadosCliRepository extends ServiceEntityRepository implements IResultadosCliRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ResultadoCli::class);
    }

    public function save(ResultadoCli $e): ResultadoCli
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($e);
        $entityManager->flush();
        return $e;
    }
}

