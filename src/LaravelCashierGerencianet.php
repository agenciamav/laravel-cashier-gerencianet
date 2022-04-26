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
        $options = array_merge([
            "client_id" => $options['client_id'] ?? config('cashier-gerencianet.client_id'),
            "client_secret" => $options['client_secret'] ?? config('cashier-gerencianet.client_secret'),
            "sandbox" => $options['sandbox'] ?? config('cashier-gerencianet.sandbox'),
            "debug" => $options['debug'] ?? config('cashier-gerencianet.debug'),
            "timeout" => $options['timeout'] ?? config('cashier-gerencianet.timeout'),
        ], $options);

        if (isset($options['pix_cert']) && is_string($options['pix_cert'])) {
            $options["pix_cert"] = $options["pix_cert"];
        } else 
        if (config('cashier-gerencianet.pix_cert') && is_string(config('cashier-gerencianet.pix_cert'))) {
            $options["pix_cert"] = config('cashier-gerencianet.pix_cert');
        } else {
            unset($options["pix_cert"]);
        }

        return new Gerencianet($options);
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
