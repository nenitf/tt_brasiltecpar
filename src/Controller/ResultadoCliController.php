<?php

namespace App\Controller;

use App\Core\Model\ResultadoCli;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use OpenApi\Annotations as OA;
use Symfony\Component\Routing\Annotation\Route;

class ResultadoCliController extends AbstractController
{
    /**
     * @Route("/api/resultados-cli", methods={"GET"})
     * @OA\Parameter(
     *     name="page",
     *     in="query",
     *     description="Página",
     *     @OA\Schema(type="integer", example=1),
     * )
     * @OA\Parameter(
     *     name="limit",
     *     in="query",
     *     description="Itens por página",
     *     @OA\Schema(type="integer", example=5),
     * )
     * @OA\Parameter(
     *     name="tentativas",
     *     in="query",
     *     description="Resultados que tiveram menos de X tentativas",
     *     @OA\Schema(type="integer", example=50000),
     * )
     */
    public function index(
        Request $request,
        EntityManagerInterface $em,
    ): JsonResponse {
        $queryString = $request->query->all();

        $page = $queryString['page'] ?? 1;
        $limit = $queryString['limit'] ?? 5;
        $maxDeTentativas = $queryString['tentativas'] ?? null;

        $qb = $em->createQueryBuilder();
        $qb->select('r')->from(ResultadoCli::class, 'r');

        if(!is_null($maxDeTentativas)) {
            $qb->where('r.tentativas < ?1')
               ->setParameter(1, $maxDeTentativas);
        }

        $qb->setFirstResult($limit * ($page - 1))
           ->setMaxResults($limit)
           ->orderBy('r.id', 'ASC');

        $resultados = $qb->getQuery()->getResult();

        return new JsonResponse($resultados);
    }
}
