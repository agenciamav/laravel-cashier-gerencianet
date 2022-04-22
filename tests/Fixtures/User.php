<?php

namespace AgenciaMav\LaravelCashierGerencianet\Tests\Fixtures;

use Illuminate\Foundation\Auth\User as Model;
use Illuminate\Notifications\Notifiable;
use AgenciaMav\LaravelCashierGerencianet\Traits\Billable;

class User extends Model
{
	use Billable, Notifiable;

	protected $guarded = [];

	/**
	 * Get the address to sync with Gerencianet.
	 *
	 * @return array|null
	 */
	public function GerencianetAddress()
	{
		return [
			'city' => 'Passo Fundo',
			'country' => 'BR',
			'line1' => 'Avenida Brasil Sul',
			'line2' => 'Apartamento 503',
			'postal_code' => '99020-180',
			'state' => 'Rio Grande do Sul',
		];
	}
}
