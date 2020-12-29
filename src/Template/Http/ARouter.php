<?php

namespace Kentron\Template\Http;

use Kentron\AApp;

abstract class ARouter
{
    protected static $apiRoutePath;
    protected static $ajaxRoutePath;
    protected static $systemRoutePath;

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
    protected static function loadApiRoutes (AApp $app): void
    {
        if (is_string(static::$apiRoutePath)) {
            require_once static::$apiRoutePath;
        }
    }

    /**
     * Load all the AJAX specific routes only
     *
     * @param AApp $app
     *
     * @return void
     */
    protected static function loadAjaxRoutes (AApp $app): void
    {
        if (is_string(static::$ajaxRoutePath)) {
            require_once static::$ajaxRoutePath;
        }
    }

    /**
     * Load all the System specific routes only
     *
     * @param AApp $app
     *
     * @return void
     */
    protected static function loadSystemRoutes (AApp $app): void
    {
        if (is_string(static::$systemRoutePath)) {
            require_once static::$systemRoutePath;
        }
    }
}
