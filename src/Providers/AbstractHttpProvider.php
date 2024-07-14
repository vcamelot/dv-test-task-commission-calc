<?php

namespace App\Providers;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Response;

abstract class AbstractHttpProvider
{
    const STATUS_OK = 200;
    const STATUS_BAD_REQUEST = 400;

    protected array $errors;

    protected string $url;

    protected $client;

    protected bool $requiresAuthentication;

    abstract protected function authenticate(): bool;

    /**
     * Send HttpRequest and return the response
     *
     * @param string $uri
     * @return Response|bool
     */
    protected function fetch(
        string $uri,
        string $method = 'GET',
        string $query = "",
        array  $params = []
    ): Response|bool
    {
        try {
            return $this->client->request($method, $uri);
        } catch (ClientException $e) {
            $this->errors[] = $e->getMessage();
            return false;
        }
    }

}
