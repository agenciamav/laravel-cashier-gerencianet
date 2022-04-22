<?php

namespace AgenciaMav\LaravelCashierGerencianet\Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use AgenciaMav\LaravelCashierGerencianet\LaravelCashierGerencianet;
use AgenciaMav\LaravelCashierGerencianet\Tests\Fixtures\User;

abstract class FeatureTestCase extends \Orchestra\Testbench\TestCase
{
	use DatabaseMigrations;

	protected $faker = null;


	public function setUp(): void
	{
		if (!getenv('GERENCIANET_CLIENT_ID') || !getenv('GERENCIANET_CLIENT_SECRET')) {
			$this->markTestSkipped('Gerencianet client_id and client_secret keys are not defined.');
		}

		$this->faker = \Faker\Factory::create();

		// $this->artisan('migrate:fresh', []);

		parent::setUp();
	}

	protected function defineDatabaseMigrations()
	{
		// $this->loadLaravelMigrations();
	}

	protected static function LaravelCashierGerencianet(array $options = []): LaravelCashierGerencianet
	{
		return new LaravelCashierGerencianet(array_merge([
			'client_id' => getenv('GERENCIANET_CLIENT_ID'),
			'client_secret' => getenv('GERENCIANET_CLIENT_SECRET'),
			'pix_cert' => getenv('GERENCIANET_PIX_CERT'),
			'sandbox' => getenv('GERENCIANET_SANDBOX'),
			'debug' => getenv('GERENCIANET_DEBUG'),
			'timeout' => getenv('GERENCIANET_TIMEOUT'),
		], $options));
	}

	protected function createCustomer($description = 'Customer', array $options = []): User
	{
		return new User(array_merge([
			'email' => $description . '@laravel-cashier-gerencianet.test',
			'name' => $this->faker->name(),
			'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
		], $options));
	}
}
