<?php

namespace App\Controller;

use App\Core\Service\Hashing\GeracaoComPrefixoDeZerosService;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HashController extends AbstractController
{
    public function create(
        $entry, GeracaoComPrefixoDeZerosService $service
    ): JsonResponse {
        $resultado = $service->execute($entry);
        return new JsonResponse($resultado);
    }
}
