<?php

namespace App\Service;

abstract class BaseService
{
    /**
     * @var array
     */
    private static $instance = [];

    /**
     * @return $this
     */
    public static function getInstance()
    {
        $className = get_called_class();
        if (!isset(self::$instance[$className]) || !self::$instance[$className] instanceof self) {
            self::$instance[$className] = new static;
        }
        return self::$instance[$className];
    }
}
