<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ApiController
{
    /**
     * @Route("/", name="index")
     * @Route("/api/", name="index_api")
     */
    public function root(): JsonResponse
    {
        return new JsonResponse(['reachable' => true]);
    }

    /**
     * @Route("/api/chessboard", name="chessboard_create")
     */
    public function create(): JsonResponse
    {
        return new JsonResponse([]);
    }

    /**
     * @Route("/api/chessboard", name="chessboard_list")
     */
    public function list(): JsonResponse
    {
        return new JsonResponse([]);
    }
}
