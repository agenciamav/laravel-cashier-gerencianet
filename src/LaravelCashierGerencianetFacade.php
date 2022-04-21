<?php

namespace AgenciaMav\LaravelCashierGerencianet;

use Illuminate\Support\Facades\Facade;

/**
 * @see \AgenciaMav\LaravelCashierGerencianet\Skeleton\SkeletonClass
 */
class LaravelCashierGerencianetFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laravel-cashier-gerencianet';
    }
}
