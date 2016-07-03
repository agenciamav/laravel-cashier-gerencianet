<?php

namespace Laravel\Cashier;

use Exception;
use InvalidArgumentException;
use Laravel\Cashier\Services\GerencianetApiService;

class GerencianetCharge extends GerencianetApiService
{		
	
	public function billet (){	 

	}
	
	public function cancel (){

	}
	
	public function card (){

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


        #
        $body = [
            'items' => $items
        ];
        $body = array_merge($body, $options);

	    $charge = self::$api->createCharge([], $body);
        return $charge;
	}
	
	public function createChargeHistory (){

	}
	
	public function detail(){
	
	}
	
	public function resendBillet (){

	}
	
	public function shipping (){

	}
	
	public function updateBillet (){

	}
	
	public function updateMetadata (){

	}

}