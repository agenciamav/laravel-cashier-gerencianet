<?php

namespace AgenciaMav\LaravelCashierGerencianet\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \AgenciaMav\LaravelCashierGerencianet\Skeleton\SkeletonClass
 */
class Subscription extends Facade
{

    public static function __callStatic($method, $args)
    {
        return static::getFacadeRoot()->$method(...$args);
    }

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'subscription';
    }
}
