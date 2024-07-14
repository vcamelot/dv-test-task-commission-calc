<?php

namespace App\Config;

class Config
{
    protected static array $envVars;

    public static function loadConfig(): void
    {
        self::$envVars = parse_ini_file('.env');
    }

    public static function getEnv($name): mixed
    {
        if (!array_key_exists($name, self::$envVars)) {
            return false;
        }

        return self::$envVars[$name];
    }
}
