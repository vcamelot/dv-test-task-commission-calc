<?php

namespace App\Calculator;

use App\Data\OurDataRow;
use App\DataReader\AbstractReader;
use App\Providers\AbstractProvider;
use App\Validator\Validator;
use Exception;

class OurCalculator extends Calculator
{

    public function __construct(protected readonly AbstractReader $reader,
                                protected readonly Validator      $validator,
                                protected AbstractProvider        $binProvider,
                                protected AbstractProvider        $ratesProvider
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
        foreach ($this->data as $item) {
            $row = $item->toArray();
            $this->calculateSingleRow($row);


        }

        return true;
    }

    public
    function getCommission(): array
    {
        $result = [];
        foreach ($this->data as $row) {
            $result[] = $row->commission();
        }

        return $result;
    }

    private function calculateSingleRow(array $row): float|bool
    {
        $country = $this->binProvider->fetchCountry($row['bin']);

        return $country;
    }
}
