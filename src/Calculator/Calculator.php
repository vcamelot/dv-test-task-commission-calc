<?php
namespace App\Calculator;

use App\Providers\AbstractProvider;

abstract class Calculator {
    protected array $errors;

    protected array $data;


    public function __construct(
        protected AbstractProvider $binProvider,
        protected AbstractProvider $ratesProvider
    )
    {
        $this->data = [];
        $this->errors = [];
    }

    public function errors(): array
    {
        return $this->errors;
    }

    abstract public function loadData(): bool;

    abstract public function calculateCommission(): bool;

    abstract public function getCommission(): array;

}
