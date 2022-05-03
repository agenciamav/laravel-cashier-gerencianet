<?php

namespace AgenciaMav\LaravelCashierGerencianet\Tests\Feature;

use AgenciaMav\LaravelCashierGerencianet\Facades\Charge;
use AgenciaMav\LaravelCashierGerencianet\Tests\Fixtures\User;

class ChargesTest extends FeatureTestCase
{

	protected $charge_id = null;
	protected $waiting_charge_id = null;

	public function test_create_tests_customer()
	{
		$user = $this->createCustomer();

		$this->assertInstanceOf(User::class, $user);
		$this->assertNotNull($user->email);
		$this->assertNotNull($user->name);
		$this->assertNotNull($user->password);
	}

	public function test_billet()
	{
		if ($this->charge_id == null) {
			$this->test_create();
		}

		$customer = [
			'name' => 'Gorbadoc Oldbuck',
			'cpf' => '04267484171',
			'phone_number' => '5144916523'
		];

		$body = [
			'payment' => [
				'banking_billet' => [
					'expire_at' => \Carbon\Carbon::now()->addDays(30)->format('Y-m-d'),
					'customer' => $customer
				]
			]
		];

		$response = Charge::billet($this->charge_id, $body);
		$this->assertNotNull($response);
	}

	public function test_cancel()
	{
		if ($this->charge_id == null) {
			$this->test_create();
		}

		$response = Charge::cancel($this->charge_id);

		$this->assertNotNull($response);
		$this->assertEquals(200, $response['code']);
	}

	public function _test_card()
	{
		if (!$this->charge_id) {
			$this->test_create();
		}

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

		$response = Charge::card($this->charge_id, $body);
	}

	public function test_create()
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

		$body = [
			'items' => $items
		];

		$response = Charge::create($body);

		$this->assertNotNull($response);
		$this->assertIsArray($response);
		$this->assertEquals($response['status'], 'new');
		$this->assertEquals($response['total'], 5000);

		$this->charge_id = $response['charge_id'];
	}


	// public function test_createChargeBalanceSheet()
	// {
	// 	$params = ['id' => 1];

	// 	$body = [
	// 		'title' => 'Balancete Demonstrativo',
	// 		'body' =>
	// 		[
	// 			0 =>
	// 			[
	// 				'header' => 'Demonstrativo de Consumo',
	// 				'tables' =>
	// 				[
	// 					0 =>
	// 					[
	// 						'rows' =>
	// 						[
	// 							0 =>
	// 							[
	// 								0 =>
	// 								[
	// 									'align' => 'left',
	// 									'color' => '#000000',
	// 									'style' => 'bold',
	// 									'text' => 'Exemplo de despesa',
	// 									'colspan' => 2,
	// 								],
	// 								1 =>
	// 								[
	// 									'align' => 'left',
	// 									'color' => '#000000',
	// 									'style' => 'bold',
	// 									'text' => 'Total lançado',
	// 									'colspan' => 2,
	// 								],
	// 							],
	// 							1 =>
	// 							[
	// 								0 =>
	// 								[
	// 									'align' => 'left',
	// 									'color' => '#000000',
	// 									'style' => 'normal',
	// 									'text' => 'Instalação',
	// 									'colspan' => 2,
	// 								],
	// 								1 =>
	// 								[
	// 									'align' => 'left',
	// 									'color' => '#000000',
	// 									'style' => 'normal',
	// 									'text' => 'R$ 100,00',
	// 									'colspan' => 2,
	// 								],
	// 							],
	// 						],
	// 					],
	// 				],
	// 			],
	// 			1 =>
	// 			[
	// 				'header' => 'Balancete Geral',
	// 				'tables' =>
	// 				[
	// 					0 =>
	// 					[
	// 						'rows' =>
	// 						[
	// 							0 =>
	// 							[
	// 								0 =>
	// 								[
	// 									'align' => 'left',
	// 									'color' => '#000000',
	// 									'style' => 'normal',
	// 									'text' => 'Confira na documentação da Gerencianet todas as configurações possíveis de um boleto balancete.',
	// 									'colspan' => 4,
	// 								],
	// 							],
	// 						],
	// 					],
	// 				],
	// 			],
	// 		],
	// 	];


	// 		$api = new Gerencianet($options);
	// 		$response = $api->createChargeBalanceSheet($params, $body);
	// }


	// public function test_createChargeHistory()
	// {
	// 	$params = ['id' => 0];

	// 	$body = [
	// 		'description' => 'This charge was not fully paid'
	// 	];


	// 		$api = new Gerencianet($options);
	// 		$response = $api->createChargeHistory($params, $body);
	// }


	public function test_detail()
	{
		if ($this->charge_id == null) {
			$this->test_create();
		}

		$response = Charge::detail($this->charge_id);

		$this->assertNotNull($response);
	}


	// public function test_link()
	// {
	// 	$params = ['id' => 0];

	// 	$body = [
	// 		'billet_discount' => 0,
	// 		'card_discount' => 0,
	// 		'message' => '',
	// 		'expire_at' => '2021-12-10',
	// 		'request_delivery_address' => false,
	// 		'payment_method' => 'all'
	// 	];


	// 		$api = new Gerencianet($options);
	// 		$response = $api->chargeLink($params, $body);
	// }


	public function test_one_step_billet()
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

		$metadata = array('notification_url' => 'https://www.your-site.com/notification');

		$customer = [
			'name' => 'Gorbadoc Oldbuck',
			'cpf' => '94271564656',
			'phone_number' => '5144916523'
		];

		$discount = [
			'type' => 'currency',
			'value' => 599
		];

		$configurations = [
			'fine' => 200,
			'interest' => 33
		];

		$conditional_discount = [
			'type' => 'percentage',
			'value' => 500,
			'until_date' => \Carbon\Carbon::now()->addDays(5)->format('Y-m-d')
		];

		$bankingBillet = [
			'expire_at' => \Carbon\Carbon::now()->addDays(10)->format('Y-m-d'),
			'message' => 'teste\nteste\nteste\nteste',
			'customer' => $customer,
			'discount' => $discount,
			'conditional_discount' => $conditional_discount
		];

		$payment = [
			'banking_billet' => $bankingBillet
		];

		$body = [
			'items' => $items,
			'metadata' => $metadata,
			'payment' => $payment
		];


		$response = Charge::oneStepBillet($body);

		$this->assertNotNull($response);

		$this->assertEquals(200, $response['code']);
		$this->assertArrayHasKey('data', $response);

		$this->assertEquals("waiting", $response['data']['status']);

		$this->waiting_charge_id = $response['data']['charge_id'];
	}


	public function test_one_step_billet_marketplace()
	{
		$repass_1 = [
			'payee_code' => "2b52b7acd1a50d8210f09375f35e13f3", // identificador da conta Gerencianet (repasse 1)
			'percentage' => 100 // porcentagem de repasse (100 = 1%)
		];

		$repasses = [
			$repass_1
		];

		$item_1 = [
			'name' => 'Item qualquer', // nome do item, produto ou serviço
			'amount' => 3, // quantidade
			'value' => 1500, // valor (1000 = R$ 10,00) (Obs: É possível a criação de itens com valores negativos. Porém, o valor total da fatura deve ser superior ao valor mínimo para geração de transações.)
			'marketplace' => array('repasses' => $repasses)
		];

		$items = [
			$item_1
		];

		$metadata = array('notification_url' => 'https://www.your-site.com/notification'); //Url de notificações

		$customer = [
			'name' => 'Gorbadoc Oldbuck', // nome do cliente
			'cpf' => '94271564656', // cpf válido do cliente
			'phone_number' => '5144916523', // telefone do cliente
		];

		$discount = [ // configuração de descontos
			'type' => 'currency', // tipo de desconto a ser aplicado
			'value' => 200 // valor de desconto
		];

		// $configurations = [ // configurações de juros e mora
		// 	'fine' => 200, // porcentagem de multa
		// 	'interest' => 33 // porcentagem de juros
		// ];

		$conditional_discount = [ // configurações de desconto condicional
			'type' => 'percentage', // seleção do tipo de desconto
			'value' => 500, // porcentagem de desconto
			'until_date' => \Carbon\Carbon::now()->addDays(5)->format('Y-m-d') // data máxima para aplicação do desconto
		];

		$bankingBillet = [
			'expire_at' => \Carbon\Carbon::now()->addDays(10)->format('Y-m-d'), // data de vencimento do titulo
			'message' => 'teste\nteste\nteste\nteste', // mensagem a ser exibida no boleto
			'customer' => $customer,
			'discount' => $discount,
			'conditional_discount' => $conditional_discount
		];

		$payment = [
			'banking_billet' => $bankingBillet // forma de pagamento (banking_billet = boleto)
		];

		$body = [
			'items' => $items,
			'metadata' => $metadata,
			'payment' => $payment
		];

		$response = Charge::oneStepBilletMarketplace($body);

		$this->assertNotNull($response);

		$this->assertEquals(200, $response['code']);
		$this->assertArrayHasKey('data', $response);

		$this->assertArrayHasKey('barcode', $response['data']);
		$this->assertArrayHasKey('pix', $response['data']);

		$this->assertEquals("banking_billet", $response['data']['payment']);
		$this->assertEquals("waiting", $response['data']['status']);
	}

	// public function test_oneStepCard()
	// {
	// 	$paymentToken = 'Insira_aqui_seu_paymentToken';

	// 	$item_1 = [
	// 		'name' => 'Gorbadoc Oldbuck',
	// 		'amount' => 1,
	// 		'value' => 3000
	// 	];

	// 	$items = [
	// 		$item_1
	// 	];

	// 	$metadata = array('notification_url' => 'https://meuip.in/xxxxx.php');

	// 	$customer = [
	// 		'name' => 'Gorbadoc Oldbuck',
	// 		'cpf' => '04267484171',
	// 		'phone_number' => '5144916523',
	// 		'email' => 'oldbuck@gerencianet.com.br',
	// 		'birth' => '1990-01-15'
	// 	];

	// 	$billingAddress = [
	// 		'street' => 'Av JK',
	// 		'number' => 909,
	// 		'neighborhood' => 'Bauxita',
	// 		'zipcode' => '35400000',
	// 		'city' => 'Ouro Preto',
	// 		'state' => 'MG'
	// 	];

	// 	$discount = [
	// 		'type' => 'currency',
	// 		'value' => 599
	// 	];

	// 	$configurations = [
	// 		'fine' => 200,
	// 		'interest' => 33
	// 	];

	// 	$credit_card = [
	// 		'customer' => $customer,
	// 		'installments' => 1,
	// 		'discount' => $discount,
	// 		'billing_address' => $billingAddress,
	// 		'payment_token' => $paymentToken,
	// 		'message' => 'teste\nteste\nteste\nteste'
	// 	];

	// 	$payment = [
	// 		'credit_card' => $credit_card
	// 	];

	// 	$body = [
	// 		'items' => $items,
	// 		'metadata' => $metadata,
	// 		'payment' => $payment
	// 	];


	// 		$api = new Gerencianet($options);
	// 		$response = $api->oneStep([], $body);
	// }


	// public function test_oneStepCardMarketplace()
	// {
	// 	$paymentToken = 'Insira_aqui_seu_paymentToken';

	// 	$repass_1 = [
	// 		'payee_code' => "Insira_aqui_o_indentificador_da conta_destino", // identificador da conta Gerencianet (repasse 1)
	// 		'percentage' => 2500 // porcentagem de repasse (2500 = 25%)
	// 	];

	// 	$repass_2 = [
	// 		'payee_code' => "Insira_aqui_o_indentificador_da conta_destino", // identificador da conta Gerencianet (repasse 2)
	// 		'percentage' => 1500 // porcentagem de repasse (1500 = 15%)
	// 	];

	// 	$repasses = [
	// 		$repass_1,
	// 		$repass_2
	// 	];

	// 	$item_1 = [
	// 		'name' => 'Item 1', // nome do item, produto ou serviço
	// 		'amount' => 1, // quantidade
	// 		'value' => 1500, // valor (1000 = R$ 10,00) (Obs: É possível a criação de itens com valores negativos. Porém, o valor total da fatura deve ser superior ao valor mínimo para geração de transações.)
	// 		'marketplace' => array('repasses' => $repasses)
	// 	];

	// 	$items = [
	// 		$item_1
	// 	];

	// 	$metadata = array('notification_url' => 'https:/seu.dominio/retorno');

	// 	$customer = [
	// 		'name' => 'Gorbadoc Oldbuck',
	// 		'cpf' => '04267484171',
	// 		'phone_number' => '5144916523',
	// 		'email' => 'oldbuck@gerencianet.com.br',
	// 		'birth' => '1990-01-15'
	// 	];

	// 	$billingAddress = [
	// 		'street' => 'Av JK',
	// 		'number' => 909,
	// 		'neighborhood' => 'Bauxita',
	// 		'zipcode' => '35400000',
	// 		'city' => 'Ouro Preto',
	// 		'state' => 'MG'
	// 	];

	// 	$discount = [
	// 		'type' => 'currency',
	// 		'value' => 599
	// 	];

	// 	$configurations = [
	// 		'fine' => 200,
	// 		'interest' => 33
	// 	];

	// 	$credit_card = [
	// 		'customer' => $customer,
	// 		'installments' => 1,
	// 		'discount' => $discount,
	// 		'billing_address' => $billingAddress,
	// 		'payment_token' => $paymentToken,
	// 		'message' => 'teste\nteste\nteste\nteste'
	// 	];

	// 	$payment = [
	// 		'credit_card' => $credit_card
	// 	];

	// 	$body = [
	// 		'items' => $items,
	// 		'metadata' => $metadata,
	// 		'payment' => $payment
	// 	];


	// 		$api = new Gerencianet($options);
	// 		$response = $api->oneStep([], $body);
	// }


	public function test_resendBillet()
	{
		if ($this->waiting_charge_id == null) {
			$this->test_one_step_billet();
		}

		$body = [
			'email' => 'oldbuck@gerencianet.com.br'
		];

		$response = Charge::resendBillet($this->waiting_charge_id, $body);

		$this->assertEquals(200, $response['code']);
	}


	// public function test_settleCharge()
	// {
	// 	$params = ['id' => 0];


	// 		$api = new Gerencianet($options);
	// 		$response = $api->settleCharge($params, []);

	// }


	// public function test_shipping()
	// {

	// 	$items = [
	// 		[
	// 			'name' => 'Item 1',
	// 			'amount' => 1,
	// 			'value' => 1000
	// 		],
	// 		[
	// 			'name' => 'Item 2',
	// 			'amount' => 2,
	// 			'value' => 2000
	// 		]
	// 	];

	// 	$shippings = [
	// 		[
	// 			'name' => 'My Shipping',
	// 			'value' => 2000
	// 		]
	// 	];

	// 	$body = [
	// 		'items' => $items,
	// 		'shippings' => $shippings
	// 	];


	// 		$api = new Gerencianet($options);
	// 		$response = $api->createCharge([], $body);

	// }


	// public function test_updateBillet()
	// {
	// 	$params = ['id' => 0];

	// 	$body = [
	// 		'expire_at' => '2021-12-10'
	// 	];


	// 		$api = new Gerencianet($options);
	// 		$response = $api->updateBillet($params, $body);

	// }


	// public function test_updateLink()
	// {

	// 	$params = ['id' => 0];

	// 	$body = [
	// 		'billet_discount' => 0,
	// 		'card_discount' => 0,
	// 		'message' => '',
	// 		'expire_at' => '2021-12-10',
	// 		'request_delivery_address' => false,
	// 		'payment_method' => 'all'
	// 	];


	// 		$api = new Gerencianet($options);
	// 		$response = $api->updateChargeLink($params, $body);

	// }


	// public function test_updateMetadata()
	// {
	// 	$params = ['id' => 0];

	// 	$body = [
	// 		'custom_id' => 'Product 0001',
	// 		'notification_url' => 'http://domain.com/notification'
	// 	];


	// 		$api = new Gerencianet($options);
	// 		$response = $api->updateChargeMetadata($params, $body);

	// }

}
