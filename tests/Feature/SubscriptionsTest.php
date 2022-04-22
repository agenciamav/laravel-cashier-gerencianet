<?php

namespace AgenciaMav\LaravelCashierGerencianet\Tests\Feature;

use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Str;
use AgenciaMav\LaravelCashierGerencianet\LaravelCashierGerencianet;
use AgenciaMav\LaravelCashierGerencianet\Models\Plan;
use AgenciaMav\LaravelCashierGerencianet\Tests\Fixtures\User;

class SubscriptionsTest extends FeatureTestCase
{
	public static function setUpBeforeClass(): void
	{
		if (!getenv('GERENCIANET_CLIENT_ID') || !getenv('GERENCIANET_CLIENT_SECRET')) {
			return;
		}

		parent::setUpBeforeClass();
	}

	public function test_customers_can_be_creted()
	{
		$user = $this->createCustomer();

		$this->assertInstanceOf(User::class, $user);
		$this->assertNotNull($user->id);
		$this->assertNotNull($user->email);
		$this->assertNotNull($user->name);
		$this->assertNotNull($user->password);
	}

	public function test_subscriptions_can_be_created()
	{
		$this->assertEquals(1, 1);
	}
}
