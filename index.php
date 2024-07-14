<?php

require_once realpath(__DIR__ . '/vendor/autoload.php');

use App\Calculator\OurCalculator;
use App\Config\Config;
use App\DataReader\JsonCsvReader;
use App\Validator\JsonCsvValidator;
use App\Providers\BinList;
use App\Providers\ApiLayer;

Config::loadConfig();

$fileName = $argv[1] ?? "";

function printErrors(array $errors): void
{
    foreach($errors as $error) {
        print($error);
        print PHP_EOL;
    }
}
$calculator = new OurCalculator(
    new JsonCsvReader($fileName),
    new JsonCsvValidator(),
    new BinList(),
    new ApiLayer()
);

if (!$calculator->loadData()) {
    printErrors($calculator->errors());
    return -1;
}


if (!$calculator->calculateCommission()) {
    printErrors($calculator->errors());
    return -1;
}


$commission = $calculator->getCommission();
foreach($commission as $row) {
    print($row);
    print PHP_EOL;
}
