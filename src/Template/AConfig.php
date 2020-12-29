<?php

namespace Kentron\Template\Http;

use Kentron\Store\Variable\AVariable;
use Kentron\Service\Assert;
use Kentron\Service\System\Client;

/**
 * A collection of functions for retrieving config data
 */
abstract class AConfig
{
    /**
     * The config path
     *
     * @var string
     */
    protected static $configPath;

    /**
     * The config data
     *
     * @var array
     */
    private static $config;

    /**
     * Get the Slim application config
     *
     * @return array
     */
    public static function getSlimConfig (): array
    {
        return self::$config["slim"];
    }

    /**
     * Get the application specific data
     *
     * @return array
     */
    public static function getAppConfig (): array
    {
        return self::$config["app"];
    }

    /**
     * Get the database credentials
     *
     * @return array
     */
    public static function getDatabaseConfig (): array
    {
        return self::$config["database"]["settings"];
    }

    /**
     * Sets the database key for decoding the system_var table
     *
     * @return string
     */
    public static function getDatabaseKey (): string
    {
        return self::$config["database"]["database_key"];
    }

    /**
     * Loads the application config and the database credentials
     *
     * @throws \Exception If the config could not be decoded from JSON
     *
     * @return void
     */
    public static function load (): void
    {
        if (!file_exists(self::$configPath) || !is_readable(self::$configPath)) {
            throw new \ErrorException("Config file '" . self::$configPath . "' does not exist or is not readable");
        }

        $appConfig = json_decode(file_get_contents(self::$configPath), true);

        if (is_null($appConfig)) {
            throw new \UnexpectedValueException("Config file '" . self::$configPath . "' is not valid JSON");
        }

        self::$config = $appConfig;

        $env = self::getAppConfig()["environment"] ?? null;

        if  (is_null($env)) {
            throw new \ErrorException("Environment not set");
        }

        if (Assert::same($env, "dev")) {
            if (!Client::isPrivate()) {
                throw new \UnexpectedValueException("Environment set to dev but IP is not private");
            }
            AVariable::setEnvironment(AVariable::ENV_DEV);
        }
        else if (Assert::same($env, "live")) {
            AVariable::setEnvironment(AVariable::ENV_LIVE);
        }
        else {
            throw new \InvalidArgumentException("Unkown environment");
        }
    }
}
