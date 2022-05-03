<?php

namespace AgenciaMav\LaravelCashierGerencianet\Http\Controllers;

use Exception;
use Gerencianet\Exception\GerencianetException;
use Illuminate\Support\Facades\Validator;

class Charge extends Controller
{
	/**	
	 * 		Generate a billet
	 * 		
	 * 		@param 		int 				$charge_id
	 * 		@param 		array 			$body
	 * 		@return 	collection
	 */
	public function billet(int $charge_id, array $body)
	{
		$params = Validator::validate(
			[
				'id' => intval($charge_id),
			],
			[
				'id' => 'required|integer'
			]
		);

		$body = Validator::validate(
			$body,
			[
				'payment.banking_billet.expire_at' => 'required|date',
				'payment.banking_billet.customer.name' => 'required|string',
				'payment.banking_billet.customer.cpf' => 'required|string',
				'payment.banking_billet.customer.phone_number' => 'required|string',
			]
		);

		try {
			$response = $this->api->payCharge($params, $body);

			return collect($response['data']);
		} catch (GerencianetException $e) {
			throw new Exception($e->errorDescription);
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	public function cancel(int $charge_id)
	{
		$params = Validator::validate(
			[
				'id' => intval($charge_id),
			],
			[
				'id' => 'required|integer'
			]
		);

		try {
			$response = $this->api->cancelCharge($params, []);

			return $response;
		} catch (GerencianetException $e) {
			throw new Exception($e->errorDescription);
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	public function card()
	{
		$params = ['id' => 0];

		$paymentToken = 'Insira_aqui_seu_paymentToken';

		$customer = [
			'name' => 'Gorbadoc Oldbuck',
			'cpf' => '04267484171',
			'phone_number' => '5144916523',
			'email' => 'oldbuck@gerencianet.com.br',
			'birth' => '1990-01-15'
		];

		$billingAddress = [
			'street' => 'Av JK',
			'number' => 909,
			'neighborhood' => 'Bauxita',
			'zipcode' => '35400000',
			'city' => 'Ouro Preto',
			'state' => 'MG',
		];

		$body = [
			'payment' => [
				'credit_card' => [
					'installments' => 1,
					'billing_address' => $billingAddress,
					'payment_token' => $paymentToken,
					'customer' => $customer
				]
			]
		];

		try {
			$api = new Gerencianet($options);
			$response = $api->payCharge($params, $body);

			echo '<pre>' . json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</pre>';
		} catch (GerencianetException $e) {
			throw new Exception($e->errorDescription);
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	public function create(array $body)
	{
		$body = Validator::validate(
			$body,
			[
				'items' => 'required|array',
				'items.*.name' => 'required|string',
				'items.*.value' => 'required|numeric',
				'items.*.amount' => 'required|integer',
			]
		);

		try {
			$response = $this->api->createCharge([], $body);

			return $response['data'];
		} catch (GerencianetException $e) {
			throw new Exception($e->errorDescription);
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	public function createChargeBalanceSheet()
	{
		$params = ['id' => 1];

		$body = [
			'title' => 'Balancete Demonstrativo',
			'body' =>
			[
				0 =>
				[
					'header' => 'Demonstrativo de Consumo',
					'tables' =>
					[
						0 =>
						[
							'rows' =>
							[
								0 =>
								[
									0 =>
									[
										'align' => 'left',
										'color' => '#000000',
										'style' => 'bold',
										'text' => 'Exemplo de despesa',
										'colspan' => 2,
									],
									1 =>
									[
										'align' => 'left',
										'color' => '#000000',
										'style' => 'bold',
										'text' => 'Total lançado',
										'colspan' => 2,
									],
								],
								1 =>
								[
									0 =>
									[
										'align' => 'left',
										'color' => '#000000',
										'style' => 'normal',
										'text' => 'Instalação',
										'colspan' => 2,
									],
									1 =>
									[
										'align' => 'left',
										'color' => '#000000',
										'style' => 'normal',
										'text' => 'R$ 100,00',
										'colspan' => 2,
									],
								],
							],
						],
					],
				],
				1 =>
				[
					'header' => 'Balancete Geral',
					'tables' =>
					[
						0 =>
						[
							'rows' =>
							[
								0 =>
								[
									0 =>
									[
										'align' => 'left',
										'color' => '#000000',
										'style' => 'normal',
										'text' => 'Confira na documentação da Gerencianet todas as configurações possíveis de um boleto balancete.',
										'colspan' => 4,
									],
								],
							],
						],
					],
				],
			],
		];

		try {
			$api = new Gerencianet($options);
			$response = $api->createChargeBalanceSheet($params, $body);

			echo '<pre>' . json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</pre>';
		} catch (GerencianetException $e) {
			throw new Exception($e->errorDescription);
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	public function createChargeHistory()
	{
		$params = ['id' => 0];

		$body = [
			'description' => 'This charge was not fully paid'
		];

		try {
			$api = new Gerencianet($options);
			$response = $api->createChargeHistory($params, $body);

			echo '<pre>' . json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</pre>';
		} catch (GerencianetException $e) {
			throw new Exception($e->errorDescription);
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	public function detail(int $charge_id)
	{
		$params = Validator::validate(
			[
				'id' => intval($charge_id),
			],
			[
				'id' => 'required|integer'
			]
		);

		try {
			$response = $this->api->detailCharge($params, []);

			return collect($response['data']);
		} catch (GerencianetException $e) {
			throw new Exception($e->errorDescription);
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	public function link()
	{
		$params = ['id' => 0];

		$body = [
			'billet_discount' => 0,
			'card_discount' => 0,
			'message' => '',
			'expire_at' => '2021-12-10',
			'request_delivery_address' => false,
			'payment_method' => 'all'
		];

		try {
			$api = new Gerencianet($options);
			$response = $api->chargeLink($params, $body);

			echo '<pre>' . json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</pre>';
		} catch (GerencianetException $e) {
			throw new Exception($e->errorDescription);
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	public function oneStepBillet(array $body)
	{
		$body = Validator::validate(
			$body,
			[
				'items' => 'required|array',
				'items.*.name' => 'required|string',
				'items.*.amount' => 'required|integer',
				'items.*.value' => 'required|integer',
				'metadata' => 'required|array',
				'metadata.notification_url' => 'required|url',
				'payment' => 'required|array',
				'payment.banking_billet' => 'required|array',
				'payment.banking_billet.expire_at' => 'required|date',
				'payment.banking_billet.customer' => 'required|array',
				'payment.banking_billet.customer.name' => 'required|string',
				'payment.banking_billet.customer.cpf' => 'required|string',
				'payment.banking_billet.customer.phone_number' => 'required|string',
				'payment.banking_billet.discount' => 'required|array',
				'payment.banking_billet.discount.type' => 'required|string',
				'payment.banking_billet.discount.value' => 'required|integer',
				'payment.banking_billet.conditional_discount' => 'required|array',
				'payment.banking_billet.conditional_discount.type' => 'required|string',
				'payment.banking_billet.conditional_discount.value' => 'required|integer',
				'payment.banking_billet.conditional_discount.until_date' => 'required|date',
			]
		);

		try {
			$response = $this->api->oneStep([], $body);

			return $response;
		} catch (GerencianetException $e) {
			throw new Exception($e->errorDescription);
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	public function oneStepBilletMarketplace(array $body)
	{
		$body = Validator::validate(
			$body,
			[
				'items' => 'required|array',
				'items.*.name' => 'required|string',
				'items.*.amount' => 'required|integer',
				'items.*.value' => 'required|integer',
				'metadata' => 'required|array',
				'metadata.notification_url' => 'required|url',
				'payment' => 'required|array',
				'payment.banking_billet' => 'required|array',
				'payment.banking_billet.expire_at' => 'required|date',
				'payment.banking_billet.customer' => 'required|array',
				'payment.banking_billet.customer.name' => 'required|string',
				'payment.banking_billet.customer.cpf' => 'required|string',
				'payment.banking_billet.customer.phone_number' => 'required|string',
				'payment.banking_billet.discount' => 'required|array',
				'payment.banking_billet.discount.type' => 'required|string',
				'payment.banking_billet.discount.value' => 'required|integer',
				'payment.banking_billet.conditional_discount' => 'required|array',
				'payment.banking_billet.conditional_discount.type' => 'required|string',
				'payment.banking_billet.conditional_discount.value' => 'required|integer',
				'payment.banking_billet.conditional_discount.until_date' => 'required|date',
			]
		);


		try {
			$response = $this->api->oneStep([], $body);

			return $response;
		} catch (GerencianetException $e) {
			throw new Exception($e->errorDescription);
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	public function oneStepCard()
	{

		$paymentToken = 'Insira_aqui_seu_paymentToken';

		$item_1 = [
			'name' => 'Gorbadoc Oldbuck',
			'amount' => 1,
			'value' => 3000
		];

		$items = [
			$item_1
		];

		$metadata = array('notification_url' => 'https://meuip.in/xxxxx.php');

		$customer = [
			'name' => 'Gorbadoc Oldbuck',
			'cpf' => '04267484171',
			'phone_number' => '5144916523',
			'email' => 'oldbuck@gerencianet.com.br',
			'birth' => '1990-01-15'
		];

		$billingAddress = [
			'street' => 'Av JK',
			'number' => 909,
			'neighborhood' => 'Bauxita',
			'zipcode' => '35400000',
			'city' => 'Ouro Preto',
			'state' => 'MG'
		];

		$discount = [
			'type' => 'currency',
			'value' => 599
		];

		$configurations = [
			'fine' => 200,
			'interest' => 33
		];

		$credit_card = [
			'customer' => $customer,
			'installments' => 1,
			'discount' => $discount,
			'billing_address' => $billingAddress,
			'payment_token' => $paymentToken,
			'message' => 'teste\nteste\nteste\nteste'
		];

		$payment = [
			'credit_card' => $credit_card
		];

		$body = [
			'items' => $items,
			'metadata' => $metadata,
			'payment' => $payment
		];

		try {
			$api = new Gerencianet($options);
			$response = $api->oneStep([], $body);

			echo '<pre>' . json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</pre>';
		} catch (GerencianetException $e) {
			throw new Exception($e->errorDescription);
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	public function oneStepCardMarketplace()
	{
		$paymentToken = 'Insira_aqui_seu_paymentToken';

		$repass_1 = [
			'payee_code' => "Insira_aqui_o_indentificador_da conta_destino", // identificador da conta Gerencianet (repasse 1)
			'percentage' => 2500 // porcentagem de repasse (2500 = 25%)
		];

		$repass_2 = [
			'payee_code' => "Insira_aqui_o_indentificador_da conta_destino", // identificador da conta Gerencianet (repasse 2)
			'percentage' => 1500 // porcentagem de repasse (1500 = 15%)
		];

		$repasses = [
			$repass_1,
			$repass_2
		];

		$item_1 = [
			'name' => 'Item 1', // nome do item, produto ou serviço
			'amount' => 1, // quantidade
			'value' => 1500, // valor (1000 = R$ 10,00) (Obs: É possível a criação de itens com valores negativos. Porém, o valor total da fatura deve ser superior ao valor mínimo para geração de transações.)
			'marketplace' => array('repasses' => $repasses)
		];

		$items = [
			$item_1
		];

		$metadata = array('notification_url' => 'https:/seu.dominio/retorno');

		$customer = [
			'name' => 'Gorbadoc Oldbuck',
			'cpf' => '04267484171',
			'phone_number' => '5144916523',
			'email' => 'oldbuck@gerencianet.com.br',
			'birth' => '1990-01-15'
		];

		$billingAddress = [
			'street' => 'Av JK',
			'number' => 909,
			'neighborhood' => 'Bauxita',
			'zipcode' => '35400000',
			'city' => 'Ouro Preto',
			'state' => 'MG'
		];

		$discount = [
			'type' => 'currency',
			'value' => 599
		];

		$configurations = [
			'fine' => 200,
			'interest' => 33
		];

		$credit_card = [
			'customer' => $customer,
			'installments' => 1,
			'discount' => $discount,
			'billing_address' => $billingAddress,
			'payment_token' => $paymentToken,
			'message' => 'teste\nteste\nteste\nteste'
		];

		$payment = [
			'credit_card' => $credit_card
		];

		$body = [
			'items' => $items,
			'metadata' => $metadata,
			'payment' => $payment
		];

		try {
			$api = new Gerencianet($options);
			$response = $api->oneStep([], $body);

			echo '<pre>' . json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</pre>';
		} catch (GerencianetException $e) {
			throw new Exception($e->errorDescription);
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	public function resendBillet(int $charge_id, array $body)
	{
		$params = Validator::validate(
			[
				'id' => intval($charge_id),
			],
			[
				'id' => 'required|integer'
			]
		);

		$body = Validator::validate(
			$body,
			[
				'email' => 'required|email',
			]
		);


		try {
			$response = $this->api->resendBillet($params, $body);

			return $response;
		} catch (GerencianetException $e) {
			throw new Exception($e->errorDescription);
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	public function settleCharge()
	{
		$params = ['id' => 0];

		try {
			$api = new Gerencianet($options);
			$response = $api->settleCharge($params, []);

			echo '<pre>' . json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</pre>';
		} catch (GerencianetException $e) {
			throw new Exception($e->errorDescription);
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	public function shipping()
	{

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

		$shippings = [
			[
				'name' => 'My Shipping',
				'value' => 2000
			]
		];

		$body = [
			'items' => $items,
			'shippings' => $shippings
		];

		try {
			$api = new Gerencianet($options);
			$response = $api->createCharge([], $body);

			echo '<pre>' . json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</pre>';
		} catch (GerencianetException $e) {
			throw new Exception($e->errorDescription);
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	public function updateBillet()
	{
		$params = ['id' => 0];

		$body = [
			'expire_at' => '2021-12-10'
		];

		try {
			$api = new Gerencianet($options);
			$response = $api->updateBillet($params, $body);

			echo '<pre>' . json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</pre>';
		} catch (GerencianetException $e) {
			throw new Exception($e->errorDescription);
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	public function updateLink()
	{

		$params = ['id' => 0];

		$body = [
			'billet_discount' => 0,
			'card_discount' => 0,
			'message' => '',
			'expire_at' => '2021-12-10',
			'request_delivery_address' => false,
			'payment_method' => 'all'
		];

		try {
			$api = new Gerencianet($options);
			$response = $api->updateChargeLink($params, $body);

			echo '<pre>' . json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</pre>';
		} catch (GerencianetException $e) {
			throw new Exception($e->errorDescription);
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	public function updateMetadata()
	{
		$params = ['id' => 0];

		$body = [
			'custom_id' => 'Product 0001',
			'notification_url' => 'http://domain.com/notification'
		];

		try {
			$api = new Gerencianet($options);
			$response = $api->updateChargeMetadata($params, $body);

			echo '<pre>' . json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</pre>';
		} catch (GerencianetException $e) {
			throw new Exception($e->errorDescription);
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}
}
