<?php

namespace Laravel\Cashier;

use Gerencianet\Exception\GerencianetException;
use Exception;
use Laravel\Cashier\Services\GerencianetApiService;

/**
 * The Subscription model
 */
class GerencianetSubscription extends GerencianetApiService
{

	/**
	 * Create a plan
	 * @param  [type] $params [description]
	 * @return [type]         [description]
	 */
	public function createPlan($params)
	{
		if( isset($params[0]) && is_array( $params[0] ) ){
			$plans = [];
			foreach ($params as $p) {
				$plans[] = self::createPlan( $p );
			}
			return $plans;
		}

		$params = array_merge(array(
			"name"     => "",
			"interval" => 1,
			"repeats"  => null
		), $params);

		try {
			$plan = self::$api->createPlan([], $params);
			if( isset($plan['code']) && $plan['code'] == 200 ){
				return $plan['data'];
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

	/**
 	 * Delete a Gerencianet plan
	 * @param int $id The plann ID
	 * @return array
	 */
	public function deletePlan($id)
	{
		$params = ['id' => $id];

		try {
			$plan = self::$api->deletePlan($params, []);
			if( isset($plan['code']) && $plan['code'] == 200 ){
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

	/**
	 * List all the Gerencianet plans
	 * @param 	string 	$search 	[description]
	 * @param 	int	 	$limit 		[description]
	 * @param 	int 	$offset 	[description]
	 */
	public function listPlans($search = "", $limit = 40, $offset = 0)
	{
		$params = ["name" => $search, "limit" => $limit, "offset" => $offset];
		try {
			$plans = self::$api->getPlans($params, []);
			if( isset($plans['code']) && $plans['code'] == 200 ){
				return $plans['data'];
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


	/**
	 * [createSubscription description]
	 * @param  [type] $plan_id [description]
	 * @param  [type] $items   [description]
	 * @param  [type] $user    [description]
	 * @param  [type] $name    [description]
	 * @return [type]          [description]
	 */
	public function createSubscription($plan_id, $items, $user, $name)
    {
  // 		$params = ['id' => $plan_id ];
		//
		// $body = [
		// 	'items' => $items
		// ];
		//
	    // $subscription 	= $this->api->createSubscription($params, $body);
		//
		// $subscription 	= $subscription['data'];
		//
		// if( ! $subscription ){
		// 	return false;
		// }
		//
		// $newSubscription = new Subscription;
		//
		// if ($newSubscription->skipTrial) {
        //     $trialEndsAt = null;
        // } else {
        //     $trialEndsAt = $newSubscription->trialDays ? Carbon::now()->addDays($newSubscription->trialDays) : null;
        // }
		//
		// return $newSubscription->create([
		// 						    	"name" 				=> $name,
		// 						    	"user_id"        	=> $user->id,
		// 							    "subscription_id" 	=> $subscription['subscription_id'],
		// 							    "status" 			=> "new",
		// 							    "custom_id" 		=> null,
		// 							    "notification_url" 	=> null,
		// 							    "payment_method"	=> null,
		// 							    "next_execution"	=> null,
		// 							    "next_expire_at" 	=> null,
		// 							    "plan_id" 			=> $plan_id,
		// 							    "occurrences" 		=> 0,
		// 							    // local
		// 							    'trial_ends_at' => $trialEndsAt,
		// 	        					'ends_at' 		=> null,
		// 	        				]);

    }

	/**
	 * [cancelSubscription description]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
    public function cancelSubscription( $id )
    {
    	$params = ['id' => $id];
	    return $this->api->cancelSubscription($params, []);
    }

	/**
	 * [detailSubscription description]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
    public function detailSubscription( $id )
    {
   		$params = ['id' => intval( $id ) ];
    	return $this->api->detailSubscription($params, []);
    }

	/**
	 * [paySubscription description]
	 * @return [type] [description]
	 */
	public function paySubscription()
    {

    }

	/**
	 * [updateSubscriptionMetadata description]
	 * @return [type] [description]
	 */
	public function updateSubscriptionMetadata()
    {

    }

}
