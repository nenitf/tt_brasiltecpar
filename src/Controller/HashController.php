<?php

namespace App\Controller;

use App\Core\Service\Hashing\GeracaoComPrefixoDeZerosService;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\RateLimiter\RateLimiterFactory;

class HashController extends AbstractController
{
    public function create(
        string $entry,
        Request $request,
        GeracaoComPrefixoDeZerosService $service,
        RateLimiterFactory $anonymousApiLimiter,
        ?bool $shouldReserve = null
    ): JsonResponse {
        $limiter = $anonymousApiLimiter->create($request->getClientIp());

        if($shouldReserve) {
            $limiter->reserve(1)->wait();
        } else {
            $limiter->consume(1)->ensureAccepted();
        }

        $resultado = $service->execute($entry);
        return new JsonResponse($resultado);
    }
}
