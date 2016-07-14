<?php

namespace Laravel\Cashier;

use Exception;
use InvalidArgumentException;
use Gerencianet\Exception\GerencianetException;
use Laravel\Cashier\Services\GerencianetApiService;
use Carbon\Carbon;

class GerencianetCharge extends GerencianetApiService
{

	//
	public function billet($charge_id, $user, $options){
		$params               = ['id' => $charge_id];

        $user_schema          = ['email', 'name', 'phone_number', 'cpf'];
        $user                 = array_intersect_key( $user, array_flip($user_schema) );

		$expire_at            = Carbon::now()->addWeeks(1)->format('Y-m-d');
		$options['expire_at'] = (isset($options['expire_at'])) ? $options['expire_at'] : $expire_at;

		$options              = array_merge(['customer' => $user], $options);
		$body                 = [
		    'payment' => [
		        'banking_billet' => $options
		    ]
		];

		try {
			$billet = self::$api->payCharge($params, $body);
			if( $billet && $billet['code'] == 200 ){
				return $billet['data'];
			}
		} catch (GerencianetException $e) {
		    return [
				'code'        => $e->code,
		    	'error'       => $e->error,
				'description' => $e->errorDescription
			];
		} catch (Exception $e) {
		    return $e->getMessage();
		}
	}

	public function cancel (){

	}

	public function card ($charge_id, $user, $options){
		$params = ['id' => $charge_id];

		$user_schema  = ['email', 'name', 'phone_number', 'cpf', 'birth'];
		$user         = array_intersect_key( $user, array_flip($user_schema) );
		if( !isset($options['credit_card']['payment_token']) ){
			return "Needs cards data";
		}
		$options['credit_card']['customer']      = $user;
		$options['credit_card']['installments']  = 1;

		$paymentToken = $options['credit_card']['payment_token'];

		$billingAddress_schema = ['street','number','neighborhood','zipcode','city','state'];
		$billingAddress        = array_intersect_key( $options['credit_card']['billing_address'], array_flip($billingAddress_schema) );

		$credit_card_schema = ['installments','billing_address','payment_token','customer'];
		$credit_card        = array_intersect_key( $options['credit_card'], array_flip($credit_card_schema) );

		$body = [
		    'payment' => [
		        'credit_card' => $credit_card
		    ]
		];

		try {
		    $charge = self::$api->payCharge($params, $body);
			if( $charge && $charge['code'] == 200 ){
				return $charge['data'];
			}
		} catch (GerencianetException $e) {
			return [
				'code'        => $e->code,
		    	'error'       => $e->error,
				'description' => $e->errorDescription
			];
		} catch (Exception $e) {
		    return $e->getMessage();
		}
	}

	public function create($amount, $options){

        if( !is_array($amount) ){

        	# Single Value

            $amount = floatval($amount);
            $item = [
                'name' => 'Generic item',
                'amount' => 1,
                'value' => $amount
            ];
            $items = [ $item ];
        }else
        if( is_array($amount) && !is_array(@$amount[0]) ){

        	# Single Item

            if ( empty($amount['value']) ) {
                throw new InvalidArgumentException('No charge value provided.');
            }
            $item = [
                'name' => $amount['name'],
                'amount' => $amount['amount'],
                'value' => floatval($amount['value'])
            ];
            $items = [ $item ];
        }else{

        	# Multiple Items

        	$items = array_map( function ($i){
                if ( !isset( $i['value'] ) || empty( $i['value'] ) ) {
                    throw new InvalidArgumentException('No charge value provided for one of items');
                }else{
                    return $i;
                }
            }, $amount);
        }

        $body = [
            'items' => $items
        ];
        $body = array_merge($body, $options);

		try {
			$charge = self::$api->createCharge([], $body);
			if( $charge && $charge['code'] == 200 ){
				return self::detail( $charge['data']['charge_id'] );
			}
		} catch (GerencianetException $e) {
			return [
				'code'        => $e->code,
				'error'       => $e->error,
				'description' => $e->errorDescription
			];
		} catch (Exception $e) {
			return $e->getMessage();
		}

	}

	public function createChargeHistory (){

	}

	public function detail($id){
		$params = ['id' => $id];
		try {
			$charge = self::$api->detailCharge($params, []);
			if( $charge && $charge['code'] == 200 ){
				return $charge['data'];
			}
		} catch (GerencianetException $e) {
			return [
				'code'        => $e->code,
				'error'       => $e->error,
				'description' => $e->errorDescription
			];
		} catch (Exception $e) {
			return $e->getMessage();
		}
	}

	public function resendBillet ($id, $email){
		$params = ['id' => $id];
		$body   = [ 'email' => $email ];

		try {
		    $response = self::$api->resendBillet($params, $body);
				if( $response['code'] == 200 ){
					// return self::detail( $id );
					return true;
				}
		} catch (GerencianetException $e) {
			return [
				'code'        => $e->code,
				'error'       => $e->error,
				'description' => $e->errorDescription
			];
		} catch (Exception $e) {
			return $e->getMessage();
		}
	}

	public function shipping (){

	}

	public function updateBillet (){

	}

	public function updateMetadata ($id, $options){
		$params = ['id' => $id];

		try {
		    $charge = self::$api->updateChargeMetadata($params, $options);
			if( $charge['code'] == 200 ){
				return self::detail( $id );
			}
		} catch (GerencianetException $e) {
			return [
				'code'        => $e->code,
				'error'       => $e->error,
				'description' => $e->errorDescription
			];
		} catch (Exception $e) {
			return $e->getMessage();
		}
	}

}
