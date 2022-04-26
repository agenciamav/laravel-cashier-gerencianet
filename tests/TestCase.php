<?php

namespace AgenciaMav\LaravelCashierGerencianet\Tests;

use AgenciaMav\LaravelCashierGerencianet\LaravelCashierGerencianetServiceProvider;
use AgenciaMav\LaravelCashierGerencianet\LaravelCashierGerencianet;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use InvalidArgumentException;
use AgenciaMav\LaravelCashierGerencianet\Tests\Fixtures\User;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
	use DatabaseMigrations;

	public function setUp(): void
	{
		if (!getenv('GERENCIANET_CLIENT_ID') || !getenv('GERENCIANET_CLIENT_SECRET')) {
			$this->markTestSkipped('Gerencianet client_id and client_secret keys are not defined.');
		}

		$this->faker = \Faker\Factory::create();

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
		LaravelCashierGerencianet::useCustomerModel(User::class);
	}
}