<?php

namespace App\DataReader;

use App\DataReader\AbstractReader;
use Exception;

class JsonCsvReader extends AbstractReader
{



    /**
     *
     */
    public function read(): array|bool
    {
        if (!parent::fileExists()) {
            return false;
        }

        $handle = fopen($this->fileName, "r");
        while (($buffer = fgets($handle)) !== false) {
            $this->data[] = json_decode($buffer, true);
        }

        fclose($handle);

        return $this->data;
    }

}
