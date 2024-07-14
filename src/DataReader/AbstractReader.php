<?php

namespace App\DataReader;

use App\Validator\Validator;
use Exception;

abstract class AbstractReader
{
    protected array $data;
    protected array $errors;
    protected int $rowCount;

    public function __construct(
        protected readonly string $fileName,
    )
    {
    }

    /**
     * Checks if input file exists
     *
     * @return bool
     */
    public function fileExists(): bool
    {
        if (!file_exists($this->fileName)) {
            $this->errors[] = "Input file `{$this->fileName}` not found.";
            return false;
        }

        return true;
    }

    /**
     * Read data from input file
     *
     * @return array|bool
     */
    abstract public function read(): array|bool;

    /**
     * Return number of rows in input file
     *
     * @return int
     */
    public function rowCount(): int
    {
        return $this->rowCount;
    }

    public function errors(): array
    {
        return $this->errors;
    }

}
