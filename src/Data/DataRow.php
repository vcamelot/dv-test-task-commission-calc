<?php

namespace App\Data;

abstract class DataRow
{
    abstract public function toArray(): array;

    public function toJson(): string
    {
        return json_encode($this->toArray(), true);
    }
}
