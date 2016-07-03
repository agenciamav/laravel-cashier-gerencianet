<?php

namespace Laravel\Cashier;

use Carbon\Carbon;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use InvalidArgumentException;
use Laravel\Cashier\GerencianetCharge as Charge;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

trait Billable
{           
    /**
     * Make a charge on the customer for the given amount or collection of items.
     * @param  array|float  $amount     Array of items or single Float value
     * @param  array        $options    [description]
     * @return array                    [description]
     */
    public function charge($amount, array $options = [])
    {        
        return Charge::create($amount, $options);     
    }  
}