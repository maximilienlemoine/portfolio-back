<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class SecurityApiService
{
    private const AUTHORIZATION_HEADER = 'Authorization';
    private string $apiSecret;

    public function __construct(string $apiSecret)
    {
        $this->apiSecret = $apiSecret;
    }

    public function checkApiKey(Request $request): ?JsonResponse
    {
        if (!$request->headers->has(self::AUTHORIZATION_HEADER)) {
            return new JsonResponse(['error' => 'No API secret provided.'], 401);
        }

        $authorization = $request->headers->get(self::AUTHORIZATION_HEADER);
        $bearer = 'Bearer ';

        $secret = null;
        if (
            null !== $authorization
            && strlen($authorization) > strlen($bearer)
            && str_starts_with($authorization, $bearer)
        ) {
            $secret = substr($authorization, strlen($bearer));
        }

        if (null === $secret) {
            return new JsonResponse(['error' => 'No API secret provided.'], 401);
        }

        if ($secret !== $this->apiSecret) {
            return new JsonResponse(['error' => 'Invalid API secret.'], 401);
        }

        return null;
    }
}