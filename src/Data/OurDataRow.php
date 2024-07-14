<?php

namespace App\Data;

class OurDataRow extends DataRow
{
    protected float $commission;

    public function __construct(
        protected int    $bin,
        protected float  $amount,
        protected string $currency)
    {
        $this->commission = 0.00;
    }

    public function toArray(): array
    {
        return [
            'bin' => $this->bin,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'commission' => $this->commission
        ];
    }

    public function commission(): float
    {
        return $this->commission;
    }

    public function setCommission(float $commission): void {
        $this->commission = $commission;
    }

}
