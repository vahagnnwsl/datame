<?php

namespace App\Packages\Loggers;


class ApiLog
{
    /**
     * @var CustomLogger
     */
    private static $logger;

    private function __construct()
    {

    }

    /**
     * @return CustomLogger
     */
    public static function newInstance()
    {
            return new CustomLogger("api");
    }

    /**
     * @return CustomLogger
     */
    public static function staticInstance()
    {
        if(is_null(self::$logger)) {
            self::$logger = new CustomLogger("api");
        }
        return self::$logger;

    }

    public static function setInstance(CustomLogger $logger)
    {
        self::$logger = $logger;
    }
}