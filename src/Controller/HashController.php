<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class HashController
{
    public function create($entry): JsonResponse
    {
        return new JsonResponse([
            'hash'       => '0000',
            'key'        => 'a',
            'tentativas' => 1,
        ]);
    }
}
