<?php
namespace Laravel\Cashier;

use Exception;

use Gerencianet\Exception\GerencianetException;
use Gerencianet\Gerencianet;

class GerencianetExtraService
{	
	protected $options;
	
	protected $api;

	public function __construct()
	{
		$this->options = [
	        'client_id'       => getenv('GERENCIANET_CLIENT_ID'),
	        'client_secret'   => getenv('GERENCIANET_CLIENT_SECRET'),
	        'sandbox'         => getenv('GERENCIANET_SANDBOX')
	    ]; 

	    $this->api = new Gerencianet( $this->options );
	}
	
	public function getInstallments (){	 

	}
	
}