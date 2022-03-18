<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class HealthController
{
    /**
     * @Route("/health", methods={"GET"})
     */
    public function healthCheckAction(): JsonResponse
    {
        return new JsonResponse(['status' => 'UP']);
    }
}