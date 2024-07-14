<?php

namespace App\Validator;

class JsonCsvValidator extends Validator
{

    private array $keys = [
        'bin',
        'amount',
        'currency'
    ];

    protected function validateKeys(array $row): bool
    {
        foreach ($this->keys as $key) {
            if (!array_key_exists($key, $row)) {
                $this->errors[] = $this->rowIndex() . "Key `{key}` not found in <<<" . json_encode($row, true) . ">>>";
                return false;
            }
        }

        return true;
    }

    protected function validateBin($bin): bool
    {
        if (!is_numeric($bin) || !is_int($bin)) {
            $this->errors[] = $this->rowIndex() . "Bin value `{bin}` is either not numeric or not an integer";
            return false;
        }

        return true;
    }

    protected function validateAmount($amount): bool
    {
        if (!is_numeric($amount) || floatval($amount) < 0) {
            $this->errors[] = $this->rowIndex() . "Amount value `{amount}` is either not numeric or negative";
            return false;
        }

        return true;
    }

    public function validate(array $data): bool
    {
        $this->currentRowIndex = 0;
        $this->errors = [];

        foreach ($data as $row) {
            if ($this->validateKeys($row)) {
                $this->validateBin($row['bin']);
                $this->validateAmount($row['amount']);
            }
        }

        return count($this->errors) > 0;
    }
}
