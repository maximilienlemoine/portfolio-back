<?php

namespace App\HttpClient;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class PrivateApiHttpClient
{

    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }


}