<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;

class HealthCheckAction
{
    /**
     * @return JsonResponse
     */
    public function __invoke(): JsonResponse
    {
        return new JsonResponse(['status' => 'ok']);
    }
}