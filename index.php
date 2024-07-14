<?php

require_once realpath(__DIR__ . '/vendor/autoload.php');

use App\Calculator\OurCalculator;
use App\DataReader\JsonCsvReader;
use App\Validator\JsonCsvValidator;
use App\Providers\BinList;
use App\Providers\ApiLayer;

$fileName = $argv[1] ?? "";

$calculator = new OurCalculator(
    new JsonCsvReader($fileName),
    new JsonCsvValidator(),
    new BinList(),
    new ApiLayer()
);

if (!$calculator->loadData()) {
    foreach($calculator->errors() as $error) {
        print($error);
        print PHP_EOL;
    }
    return -1;
}

$calculator->calculateCommission();
$commission = $calculator->getCommission();
foreach($commission as $row) {
    print($row);
    print PHP_EOL;
}
