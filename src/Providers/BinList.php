<?php

namespace App\Providers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Response;

class BinList extends AbstractHttpProvider
{

    protected bool $requiresAuthentication = false;
    protected string $url = 'https://lookup.binlist.net/';

    /**
     * Get country from Bin provider
     *
     * @param int $bin
     * @return string|bool
     */
    public function fetchCountry(int $bin): string|bool
    {

        $response = $this->fetch($this->url . '/' . $bin);
        if (!$response) {
            return false;
        }

        if (!$response->getStatusCode() == self::STATUS_OK) {
            $this->errors[] = "Invalid bin value `{$bin}`.";
            return false;
        }

        return $this->getCountry($response);
    }


    protected function authenticate(): bool
    {
        return true;
    }


    /**
     * Analyze response structure and return country
     *
     * @param Response $response
     * @return string|bool
     */
    private function getCountry(Response $response): string|bool
    {
        $body = json_decode($response->getBody(), true);

        if (!isset($body['country']['alpha2'])) {
            $this->errors[] = "Invalid Bin response structure.";
            return false;
        }

        return $body['country']['alpha2'];
    }


}
