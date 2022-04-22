<?php

namespace AgenciaMav\LaravelCashierGerencianet\Tests;

use AgenciaMav\LaravelCashierGerencianet\LaravelCashierGerencianetServiceProvider;
use AgenciaMav\LaravelCashierGerencianet\LaravelCashierGerencianet;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use InvalidArgumentException;

class TestCase extends \Orchestra\Testbench\TestCase
{
	use DatabaseMigrations;

	public function setUp(): void
	{
		if (!getenv('GERENCIANET_CLIENT_ID') || !getenv('GERENCIANET_CLIENT_SECRET')) {
			$this->markTestSkipped('Gerencianet client_id and client_secret keys are not defined.');
		}

		$this->faker = \Faker\Factory::create();

		// $this->artisan('migrate:fresh', []);

		parent::setUp();
	}

	protected function getPackageProviders($app)
	{
		return [
			LaravelCashierGerencianetServiceProvider::class,
		];
	}

	protected function getEnvironmentSetUp($app)
	{
		$client_id = config('cashier-gerencianet.client_id');
		$client_secret = config('cashier-gerencianet.client_secret');
		$sandbox = config('cashier-gerencianet.sandbox');
		$debug = config('cashier-gerencianet.debug');
		$timeout = config('cashier-gerencianet.timeout');
		$pix_cert = config('cashier-gerencianet.pix_cert');

		if (!$client_id) throw new InvalidArgumentException('Tests cannot be run without "GERENCIANET_CLIENT_ID" environment key.');
		if (!$client_secret) throw new InvalidArgumentException('Tests cannot be run without "GERENCIANET_CLIENT_SECRET" environment key.');
		if (!$debug) throw new InvalidArgumentException('Tests cannot be run without "GERENCIANET_DEBUG" environment key.');
		if (!$sandbox) throw new InvalidArgumentException('Tests cannot be run without "GERENCIANET_SANDBOX" environment key.');
		if (!$timeout) throw new InvalidArgumentException('Tests cannot be run without "GERENCIANET_TIMEOUT" environment key.');
		if (!$pix_cert) throw new InvalidArgumentException('Tests cannot be run without "GERENCIANET_PIX_CERT" environment key.');

		LaravelCashierGerencianet::useCustomerModel(User::class);
	}
}
