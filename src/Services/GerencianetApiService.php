<?php

namespace Laravel\Cashier\Services;

use Exception;
use Gerencianet\Exception\GerencianetException;
use Gerencianet\Gerencianet;
use InvalidArgumentException;

class GerencianetApiService
{	
	
	static $api;
   
	public function init()
    {    	
    	$options = [
	        'client_id'       => getenv('GERENCIANET_CLIENT_ID'),
	        'client_secret'   => getenv('GERENCIANET_CLIENT_SECRET'),
	        'sandbox'         => ( getenv('GERENCIANET_SANDBOX') ) ? getenv('GERENCIANET_SANDBOX') : true
	    ];	
	    self::$api = new Gerencianet( $options );
    }

}
GerencianetApiService::init();