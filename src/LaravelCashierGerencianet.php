<?php

namespace AgenciaMav\LaravelCashierGerencianet;

use Gerencianet\Gerencianet;

class LaravelCashierGerencianet
{
    /**
     * The default customer model class name.
     *
     * @var string
     */
    public static $customerModel = 'App\\Models\\User';

    /**
     * Get the Gerencianet SDK client.
     *
     * @param  array  $options
     * @return \Gerencianet\Gerencianet
     */
    public static function LaravelCashierGerencianet(array $options = [])
    {
        return new Gerencianet(array_merge([
            "client_id" => $options['client_id'] ?? config('cashier-gerencianet.client_id'),
            "client_secret" => $options['client_secret'] ?? config('cashier-gerencianet.client_secret'),
            "pix_cert" => $options['pix_cert'] ?? config('cashier-gerencianet.pix_cert'),
            "sandbox" => $options['sandbox'] ?? config('cashier-gerencianet.sandbox'),
            "debug" => $options['debug'] ?? config('cashier-gerencianet.debug'),
            "timeout" => $options['timeout'] ?? config('cashier-gerencianet.timeout'),
        ], $options));
    }


    /**
     * Set the customer model class name.
     *
     * @param  string  $customerModel
     * @return void
     */
    public static function useCustomerModel($customerModel)
    {
        static::$customerModel = $customerModel;
    }
}
