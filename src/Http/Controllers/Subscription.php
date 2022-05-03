<?php

namespace AgenciaMav\LaravelCashierGerencianet\Http\Controllers;

use Gerencianet\Exception\GerencianetException;
use \Exception;
use Illuminate\Support\Facades\Validator;

class Subscription extends Controller
{

	/**
	 * Cancel a subscription
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function cancelSubscription(int $id)
	{
		$params = Validator::validate([
			'id' => intval($id)
		], [
			'id' => 'required|integer'
		]);

		try {
			$response = $this->api->cancelSubscription($params, []);

			return $response;
		} catch (GerencianetException $e) {
			throw new Exception($e->errorDescription);
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	/**
	 * Create a Plan
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function createPlan(array $body)
	{
		$body = Validator::validate($body, [
			'name' => 'required|string',
			'interval' => 'required|integer',
			'repeats' => 'sometimes|integer',
		]);

		try {
			$response = $this->api->createPlan([], $body);

			return $response;
		} catch (GerencianetException $e) {
			throw new Exception($e->errorDescription);
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	/**
	 * Create a Subscription
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function createSubscription(int $id, array $body)
	{
		$params = Validator::validate([
			'id' => intval($id)
		], [
			'id' => 'required|integer'
		]);

		$body = Validator::validate($body, [
			'items' => 'required|array',
			'items.*.name' => 'required|string',
			'items.*.value' => 'sometimes|numeric',
			'items.*.amount' => 'sometimes|integer'
		]);

		try {
			$response = $this->api->createSubscription($params, $body);

			return $response;
		} catch (GerencianetException $e) {
			throw new Exception($e->errorDescription);
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	/**
	 * Create subscription history
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function createSubscriptionHistory(int $id, array $body)
	{
		$params = Validator::validate(
			[
				'id' => intval($id),
			],
			[
				'id' => 'required|integer'
			]
		);

		$body = Validator::validate(
			$body,
			[
				'description' => 'required|string',
			]
		);

		try {
			$response = $this->api->createSubscriptionHistory($params, $body);

			return $response;
		} catch (GerencianetException $e) {
			throw new Exception($e->errorDescription);
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	/**
	 * Delete a Plan
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function deletePlan(int $id)
	{
		// validate the id
		$params = Validator::validate(
			[
				'id' => $id
			],
			[
				'id' => 'required|integer'
			]
		);

		try {
			$response = $this->api->deletePlan($params, []);

			return $response;
		} catch (GerencianetException $e) {
			throw new Exception($e->errorDescription);
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	/**
	 * Details of a Subscription
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function detailSubscription(int $id)
	{
		$params = Validator::validate(
			[
				'id' => intval($id),
			],
			[
				'id' => 'required|integer'
			]
		);

		try {
			$response = $this->api->detailSubscription($params, []);

			return $response;
		} catch (GerencianetException $e) {
			throw new Exception($e->errorDescription);
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	/**
	 * List plans
	 *
	 * @param  int  $limit
	 * @param  int  $offset
	 * @return Response
	 */
	public function getPlans(array $params = [])
	{
		$params = Validator::validate(
			$params,
			[
				'limit' => 'integer',
				'offset' => 'integer',
			]
		);

		try {
			$response = $this->api->getPlans($params, []);

			return collect($response['data']);
		} catch (GerencianetException $e) {
			throw new Exception($e->errorDescription);
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	/**
	 * Pay Subscription
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function paySubscription(int $id, array $body)
	{
		$params = Validator::validate(
			[
				'id' => intval($id),
			],
			[
				'id' => 'required|integer'
			]
		);

		$body = Validator::validate(
			$body,
			[
				'payment.credit_card.billing_address.street' => 'required|string',
				'payment.credit_card.billing_address.number' => 'required|integer',
				'payment.credit_card.billing_address.neighborhood' => 'required|string',
				'payment.credit_card.billing_address.zipcode' => 'required|string',
				'payment.credit_card.billing_address.city' => 'required|string',
				'payment.credit_card.billing_address.state' => 'required|string',
				'payment.credit_card.payment_token' => 'required|string',
				'payment.credit_card.customer.name' => 'required|string',
				'payment.credit_card.customer.cpf' => 'required|string',
				'payment.credit_card.customer.phone_number' => 'required|string',
				'payment.credit_card.customer.email' => 'required|email',
				'payment.credit_card.customer.birth' => 'required|string',
			]
		);

		try {
			$response = $this->api->paySubscription($params, $body);

			return $response;
		} catch (GerencianetException $e) {
			throw new Exception($e->errorDescription);
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	/**
	 * Update a Plan
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function updatePlan(int $id, array $body)
	{
		$params = Validator::validate(
			[
				'id' => intval($id),
			],
			[
				'id' => 'required|integer'
			]
		);

		$body = Validator::validate(
			$body,
			[
				'name' => 'required|string',
			]
		);

		try {
			$response = $this->api->updatePlan($params, $body);

			return $response;
		} catch (GerencianetException $e) {
			throw new Exception($e->errorDescription);
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	/**
	 * Update a Subscription Metadata
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function updateSubscriptionMetadata(int $id, array $body)
	{
		$params = Validator::validate(
			[
				'id' => intval($id),
			],
			[
				'id' => 'required|integer'
			]
		);

		$body = Validator::validate(
			$body,
			[
				'notification_url' => 'required|url',
				'custom_id' => 'sometimes|string',
			]
		);

		try {
			$response = $this->api->updateSubscriptionMetadata($params, $body);

			return $response;
		} catch (GerencianetException $e) {
			throw new Exception($e->errorDescription);
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}
}
