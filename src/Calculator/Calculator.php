<?php

namespace App\Calculator;

use App\Providers\AbstractHttpProvider;

abstract class Calculator
{
    protected array $errors;

    protected array $data;

    protected array $rates;

    protected array $bins;


    public function __construct(
        protected AbstractHttpProvider $binProvider,
        protected AbstractHttpProvider $ratesProvider
    )
    {
        $this->data = [];
        $this->errors = [];
        $this->rates = [];
        $this->bins = [];
    }

    public function errors(): array
    {
        return $this->errors;
    }

    abstract public function loadData(): bool;

    abstract public function calculateCommission(): bool;

    abstract public function getCommission(): array;

    /**
     * Fetch rates using provided API and save into class variable
     *
     * @return bool
     */
    protected function fetchRates(): bool
    {
        $rates = $this->ratesProvider->fetchRates();

        if (!$rates) {
            $this->errors = array_merge($this->errors, $this->ratesProvider->errors());
            return false;
        }

        $this->rates = $rates;

        return true;
    }

    /**
     * Find rate by currency name
     *
     * @param string $currency
     * @return float|bool
     */
    protected function getRate(string $currency): float|bool
    {
        if (!array_key_exists($currency, $this->rates)) {
            return false;
        }

        return $this->rates[$currency];
    }

    /**
     * Look for bin in $bins array and return country. If country not found, fetch using provided Bin API
     *
     * @param int $bin
     * @return string|bool
     */
    protected function countryFindOrUpdate(int $bin): string|bool
    {
        if (array_key_exists($bin, $this->bins)) {
            $country = $this->bins[$bin];
        } else {
            $country = $this->binProvider->fetchCountry($bin);
            if (!$country) {
                $this->errors = array_merge($this->errors, $this->binProvider->errors());
                return false;
            }
            $this->bins[$bin] = $country;
        }

        return $country;
    }

}
