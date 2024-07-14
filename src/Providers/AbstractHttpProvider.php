<?php

namespace App\Providers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7;


abstract class AbstractHttpProvider
{
    const STATUS_OK = 200;
    const STATUS_BAD_REQUEST = 400;

    protected array $errors;

    protected string $url;

    protected Client $client;

    protected bool $requiresAuthentication;

    abstract protected function authenticate(): bool;

    public function __construct(array $config = []) {
        // Setting 'verify' flag to 'false' to avoid boring stuff with cacert.pem on Windows
        $this->client = new Client(
            [
                'verify' => false
            ]
        );
    }

    public function errors(): array
    {
        return $this->errors;
    }

    /**
     * Send HttpRequest and return the response
     *
     * @param string $uri
     * @param string $method
     * @param array $options
     * @return Response|bool
     * @throws GuzzleException
     */
    protected function fetch(
        string $uri,
        string $method = 'GET',
        array  $options = []
    ): Response|bool
    {
        try {
            return $this->client->request($method, $uri, $options);
        } catch (ClientException $e) {
            $this->errors[] = $e->getMessage();
            return false;
        }

    }

}
