<?php

namespace AgenciaMav\LaravelCashierGerencianet;

use Carbon\Carbon;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use InvalidArgumentException;
// use AgenciaMav\LaravelCashierGerencianet\GerencianetCharge as Charge;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

trait Billable
{

    public $billing_address = [
        'street'       => 'Av. JK',
        'number'       => 909,
        'neighborhood' => 'Bauxita',
        'zipcode'      => '35400000',
        'city'         => 'Ouro Preto',
        'state'        => 'MG',
    ];

    /**
    * Make a charge on the customer for the given amount or collection of items.
    * [charge description]
    * @param  [type] $amount  [description]
    * @param  [type] $options [description]
    * @return [type]          [description]
    */
    public function charge($amount, array $options = [])
    {
        $charge = new GerencianetCharge;
        return $charge->create($amount, $options);
    }

    /**
    * [payCharge description]
    * @param  [type] $charge_id [description]
    * @param  [type] $method    [description]
    * @param  [type] $options   [description]
    * @return [type]            [description]
    */
    public function payCharge($charge_id, $method, array $options = [])
    {
        if( !$method || !$charge_id){
            return "Wrong gateway payment or charge ID";
        }

        $user = $this->toArray();
        $charge = new GerencianetCharge;
        switch ($method) {
            case 'billet':
                return $charge->billet( $charge_id, $user, $options );
            break;
            case 'card':
                return $charge->card( $charge_id, $user, $options );
            break;
            default:
                # code...
            break;
        }
    }

    public function getCharge($id)
    {
        $charge = new GerencianetCharge;
        return $charge->detail($id);
    }

    public function updateCharge($id, $options = [])
    {
        $charge = new GerencianetCharge;
        return $charge->updateMetadata($id, $options);
    }

    public function resendBillet($id, $options = [])
    {
        $charge = new GerencianetCharge;
        return $charge->resendBillet($id, $options);
    }

    public function createChargeHistory( $id, $description )
    {
        $charge = new GerencianetCharge;
        return $charge->createChargeHistory($id, $description);
    }

    public function cancelCharge( $id )
    {
        $charge = new GerencianetCharge;
        return $charge->cancel($id);
    }


    public function updateBillet( $id, $new_date )
    {
        $charge = new GerencianetCharge;
        return $charge->updateBillet( $id, $new_date );
    }

    public function newSubscription( $plan, $items )
    {
        $charge = new GerencianetSubscription;
        return $charge->createSubscription( $plan, $items, $this->id );
    }
}
