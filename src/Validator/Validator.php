<?php

namespace App\Validator;

abstract class Validator{

    protected array $errors;

    protected int $currentRowIndex;


    abstract public function validate(array $data): bool;

    public function errors(): array
    {
        return $this->errors;
    }

    protected function rowIndex(): string {
        return "Row {$this->currentRowIndex}: ";
    }

}