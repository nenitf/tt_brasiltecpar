<?php

namespace App\Controller;

use App\Core\Service\Hashing\GeracaoComPrefixoDeZerosService;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Symfony\Component\RateLimiter\RateLimiterFactory;

class HashController extends AbstractController
{
    public function create(
        string $entry,
        Request $request,
        GeracaoComPrefixoDeZerosService $service,
        RateLimiterFactory $anonymousApiLimiter
    ): JsonResponse {
        $limiter = $anonymousApiLimiter->create($request->getClientIp());

        if (false === $limiter->consume(1)->isAccepted()) {
            throw new TooManyRequestsHttpException();
        }

        $resultado = $service->execute($entry);
        return new JsonResponse($resultado);
    }
}
