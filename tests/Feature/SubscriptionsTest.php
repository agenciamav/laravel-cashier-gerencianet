<?php

namespace AgenciaMav\LaravelCashierGerencianet\Tests\Feature;

use AgenciaMav\LaravelCashierGerencianet\Facades\Subscription;
use AgenciaMav\LaravelCashierGerencianet\Tests\Fixtures\User;
use Illuminate\Database\Eloquent\Collection;

class SubscriptionsTest extends FeatureTestCase
{

	protected $plan_id = null;
	protected $subscription_id = null;

	public function test_create_tests_customer()
	{
		$user = $this->createCustomer();

		$this->assertInstanceOf(User::class, $user);
		$this->assertNotNull($user->email);
		$this->assertNotNull($user->name);
		$this->assertNotNull($user->password);
	}
	
	public function test_cancel_subscription()
	{
		if (is_null($this->subscription_id)) {
			$this->test_create_subscription();
		}

		$response = Subscription::cancelSubscription($this->subscription_id);

		$this->assertEquals(200, $response['code']);
	}

	public function test_create_plan()
	{
		$plan = Subscription::createPlan([
			'name' => 'Test Plan - ' . $this->faker->Uuid,
			'interval' => 10,
			'repeats' => 2,
		]);

		$this->assertNotNull($plan);
		$this->assertEquals(200, $plan['code']);

		$this->plan_id = $plan['data']['plan_id'];
	}

	public function test_create_subscription()
	{
		if (is_null($this->plan_id)) {
			$this->test_create_plan();
		}

		// Prepare a subscription 
		$items = [
			[
				'name' => 'Item 1',
				'amount' => 1,
				'value' => 1000
			],
			[
				'name' => 'Item 2',
				'amount' => 2,
				'value' => 2000
			]
		];

		$body = [
			'items' => $items
		];

		// Create a subscription
		$subscription = Subscription::createSubscription($this->plan_id, $body);
		$this->assertNotNull($subscription);
		$this->assertEquals(200, $subscription['code']);
		$this->assertNotNull($subscription['data']['subscription_id']);

		$this->subscription_id = $subscription['data']['subscription_id'];
	}

	public function test_create_subscription_history()
	{
		if (is_null($this->plan_id)) {
			$this->test_create_plan();
		}

		if (is_null($this->subscription_id)) {
			$this->test_create_subscription();
		}

		// Create a subscription history
		$body = ['description' => 'This carnet is about a service'];

		$subscription = Subscription::createSubscriptionHistory($this->subscription_id, $body);
		$this->assertEquals(200, $subscription['code']);
	}

	public function test_delete_plan()
	{
		if (is_null($this->plan_id)) {
			$this->test_create_plan();
		}

		// Delete the plan
		$plan = Subscription::deletePlan($this->plan_id);
		$this->assertEquals(200, $plan['code']);
	}

	public function test_detail_subscription()
	{
		if (is_null($this->subscription_id)) {
			$this->test_create_subscription();
		}

		// detail the subscription
		$subscription = Subscription::detailSubscription($this->subscription_id);
		$this->assertEquals(200, $subscription['code']);
	}

	public function test_get_plans()
	{
		$response = Subscription::getPlans();

		$this->assertNotNull($response);
	}

	// public function test_pay_subscription()
	// {
	// 	if (is_null($this->subscription_id)) {
	// 		$this->test_create_subscription();
	// 		$payment_token = '';
	// 	}

	// 	$customer = [
	// 		'name' => 'Gorbadoc Oldbuck',
	// 		'cpf' => '04267484171',
	// 		'phone_number' => '5144916523',
	// 		'email' => 'oldbuck@gerencianet.com.br',
	// 		'birth' => '1977-01-15'
	// 	];

	// 	$billing_address = [
	// 		'street' => 'Av. JK',
	// 		'number' => 909,
	// 		'neighborhood' => 'Bauxita',
	// 		'zipcode' => '35400000',
	// 		'city' => 'Ouro Preto',
	// 		'state' => 'MG',
	// 	];

	// 	$body = [
	// 		'payment' => [
	// 			'credit_card' => [
	// 				'billing_address' => $billing_address,
	// 				'payment_token' => $payment_token,
	// 				'customer' => $customer
	// 			]
	// 		]
	// 	];

	// 	$response = Subscription::paySubscription($this->subscription_id, $body);
	// 	$this->assertNotNull($response);
	// 	$this->assertEquals(200, $response['code']);
	// 	$this->assertNotEmpty($response['data']);
	// }

	// public function test_update_plan()
	// {
	// }

	// public function test_update_subscription_metadata()
	// {
	// }

}
