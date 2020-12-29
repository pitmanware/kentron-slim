<?php

namespace Kentron\Http;

use Kentron\AApp;

abstract class ARouter
{
    /**
     * Loads all routes
     *
     * @param AApp $app This application
     */
    public static function loadAllRoutes (AApp $app): void
    {
        self::loadApiRoutes($app);
        self::loadAjaxRoutes($app);
        self::loadSystemRoutes($app);
    }

    /**
     * Load all the API specific routes only
     *
     * @param AApp $app
     *
     * @return void
     */
    abstract protected static function loadApiRoutes (AApp $app): void;

    /**
     * Load all the AJAX specific routes only
     *
     * @param AApp $app
     *
     * @return void
     */
    abstract protected static function loadAjaxRoutes (AApp $app): void;

    /**
     * Load all the System specific routes only
     *
     * @param AApp $app
     *
     * @return void
     */
    abstract protected static function loadSystemRoutes (AApp $app): void;
}
