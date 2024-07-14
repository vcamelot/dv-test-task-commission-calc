<?php

namespace App\Calculator;

use App\Country;
use App\Data\OurDataRow;
use App\DataReader\AbstractReader;
use App\Providers\AbstractHttpProvider;
use App\Validator\Validator;
use Exception;

class OurCalculator extends Calculator
{

    public function __construct(protected readonly AbstractReader $reader,
                                protected readonly Validator      $validator,
                                protected AbstractHttpProvider    $binProvider,
                                protected AbstractHttpProvider    $ratesProvider
    )
    {
        parent::__construct($binProvider, $ratesProvider);
    }


    public function loadData(): bool
    {

        // Read data from text file
        $rawData = $this->reader->read();
        if (!$rawData) {
            $this->errors = array_merge($this->errors, $this->reader->errors());
            return false;
        }

        // Validate each value
        if (!$this->validator->validate($rawData)) {
            $this->errors = array_merge($this->errors, $this->validator->errors());
            return false;
        }

        // Populate $this->data array with validated values of type `OurDataRow`
        foreach ($rawData as $row) {
            $this->data[] = new OurDataRow(
                intval(trim($row['bin'])),
                round(trim($row['amount'], 2)),
                strtoupper(trim($row['currency']))
            );
        }

        return true;

    }

    public function calculateCommission(): bool
    {
        if (!$this->fetchRates()) {
            return false;
        }

        foreach ($this->data as &$item) {
            $row = $item->toArray();
            $commission = $this->calculateSingleCommission($row['bin'], $row['amount'], $row['currency']);
            if (!$commission) {
                return false;
            }
            $item->setCommission($commission);
        }

        return true;
    }

    public function getCommission(): array
    {
        $result = [];
        foreach ($this->data as $row) {
            $result[] = $this->formatOutput($row);
        }

        return $result;
    }

    private function calculateSingleCommission(int $bin, float $amount, string $currency): float|bool
    {
        $country = $this->countryFindOrUpdate($bin);
        if (!$country) {
            return false;
        }

        $rate = $this->getRate($currency);
        if (!$rate) {
            $this->errors[] = "Rate not found for currency `{$currency}`";
            return false;
        }

        if ($rate != 0) {
            $amount /= $rate;
        }

        if (Country::isEUCountry($country)) {
            $amount *= 0.01;
        } else {
            $amount *= 0.02;
        }

        return $amount;
    }

    private function formatOutput(OurDataRow $row): string {
        return round($row->commission(), 2);
    }
}
