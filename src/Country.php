<?php

namespace App;

class Country {
    public const EU_COUNTRIES = [
         'AT',
         'BE',
         'BG',
         'CY',
         'CZ',
         'DE',
         'DK',
         'EE',
         'ES',
         'FI',
         'FR',
         'GR',
         'HR',
         'HU',
         'IE',
         'IT',
         'LT',
         'LU',
         'LV',
         'MT',
         'NL',
         'PO',
         'PT',
         'RO',
         'SE',
         'SI',
         'SK',
    ];

    public static function isEUCountry(string $country): bool {
        return in_array($country, self::EU_COUNTRIES);
    }
}
