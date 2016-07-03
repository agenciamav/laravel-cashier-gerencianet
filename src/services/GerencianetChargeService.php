<?php
namespace Laravel\Cashier;

use Exception;

use Gerencianet\Exception\GerencianetException;
use Gerencianet\Gerencianet;

class GerencianetChargeService
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
	
	public function billet (){	 

	}
	
	public function cancel (){

	}
	
	public function card (){

	}
	
	public function create(){

	}
	
	public function createChargeHistory (){

	}
	
	public function detail(){
	
	}
	
	public function resendBillet (){

	}
	
	public function shipping (){

	}
	
	public function updateBillet (){

	}
	
	public function updateMetadata (){

	}

}