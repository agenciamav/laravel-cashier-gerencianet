<?php

namespace Laravel\Cashier;

use Exception;
use Laravel\Cashier\Services\GerencianetApiService;

class GerencianetSubscriptionService extends GerencianetApiService
{	
	
	/**
	 * Create a plan
	 * @param  [type] $params [description]
	 * @return [type]         [description]
	 */
	public function createPlan($params)
	{		
		$plan = $this->api->createPlan([], $params);
		return $plan;
		
	}

	/**
	 * Delete a plan
	 * @param  [type] $params [description]
	 * @return [type]         [description]
	 */
	public function deletePlan($id)
	{
		$params = ['id' => $id];
				
		$plan = $this->api->deletePlan($params, []);
		print_r( $plan );
		return $plan;

	}

	/**
	 * List all the Gerencianet plans
	 * @param  [type] $params [description]
	 * @return [type]         [description]
	 */
	public function listPlans($limit = 20, $offset = 0)
	{
		$params = [$limit, $offset];
	    
	    $plans = $this->api->getPlans($params, []);

	    return $plans;
	}

    /**
     * Get the Gerencianet plan that has the given ID or Name.
     *
     * @param  string  $id
     * @return \Gerencianet\Plan
     */
    public function findPlan($string)
    {    	
    	$plans = $this->getPlans();  

    	$string = (Array)$string;  	    	

    	foreach ($plans['data'] as $plan) {    		
    		
    		if( is_array($string) && in_array($plan['name'], $string) || in_array($plan['plan_id'], $string) ){
    			$reponse[] = $plan;
    		} 

			if( $plan['name'] === $string || $plan['plan_id'] === intval($string) ){
    			return $plan;
    		} 
    	}   

    	if( @$reponse ){
    		return ( count($reponse) == 1) ? $reponse[0] : $reponse;
    	}

    	return false;

    }


    
    public function createSubscription($plan_id, $items, $user, $name)
    {
  		$params = ['id' => $plan_id ];
		
		$body = [
			'items' => $items
		];
	    
	    $subscription 	= $this->api->createSubscription($params, $body);

		$subscription 	= $subscription['data'];

		if( ! $subscription ){
			return false;
		}

		$newSubscription = new Subscription;

		if ($newSubscription->skipTrial) {
            $trialEndsAt = null;
        } else {
            $trialEndsAt = $newSubscription->trialDays ? Carbon::now()->addDays($newSubscription->trialDays) : null;
        }

		return $newSubscription->create([
								    	"name" 				=> $name,
								    	"user_id"        	=> $user->id,
									    "subscription_id" 	=> $subscription['subscription_id'],    
									    "status" 			=> "new",
									    "custom_id" 		=> null,
									    "notification_url" 	=> null,
									    "payment_method"	=> null,
									    "next_execution"	=> null,
									    "next_expire_at" 	=> null,    
									    "plan_id" 			=> $plan_id,    
									    "occurrences" 		=> 0,
									    // local
									    'trial_ends_at' => $trialEndsAt,
			        					'ends_at' 		=> null,
			        				]);		

    }


    public function cancelSubscription( $id )
    {
    	$params = ['id' => $id];
	    return $this->api->cancelSubscription($params, []);	    
    }

    public function detailSubscription( $id )
    {
   		$params = ['id' => intval( $id ) ];
    	return $this->api->detailSubscription($params, []);
    }

    public function paySubscription()
    {
    	
    }

    public function updateSubscriptionMetadata()
    {
    	
    }

}