<?php

namespace App\Providers;

use GuzzleHttp\Psr7\Response;

class ApiLayer extends AbstractHttpProvider
{
    protected bool $requiresAuthentication = false;
    protected string $url = 'https://api.apilayer.com/exchangerates_data/latest';

    public function fetchRates(): array|bool
    {
        $options = [
            'headers' => [
                'apikey' => getenv('APILAYER_KEY')
            ]
        ];

        $response = $this->fetch($this->url, 'GET', '', $options);

        if (!$response) {
            return false;
        }

        if (!$response->getStatusCode() == self::STATUS_OK) {
            $this->errors[] = "Error from APILayer.io";
            return false;
        }

        return $this->getRates($response);
    }

    protected function authenticate(): bool
    {
        return true;
    }

    private function getRates(Response $response): array|bool
    {
        $body = json_decode($response->getBody(), true);

        if (!isset($body['rates'])) {
            $this->errors[] = "Invalid rates response structure.";
            return false;
        }

        return $body['rates'];
    }

}
