<?php

namespace AgenciaMav\LaravelCashierGerencianet\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use AgenciaMav\LaravelCashierGerencianet\LaravelCashierGerencianet;

class Controller extends BaseController
{
	use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

	protected $api;

	public function __construct()
	{
		$api = new LaravelCashierGerencianet();
		$this->api = $api->LaravelCashierGerencianet();
	}
}
