<?php

namespace Laravel\Cashier;

use Laravel\Cashier\Services\GerencianetCharge;
use Illuminate\Database\Eloquent\Model;

use Exception;

class Charge extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    protected $fillable = [
        'id', 'charge_id', 'status', 'total', 'custom_id', 'created_at', 'updated_at',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [       
        'created_at', 'updated_at',
    ];

    /**
     * Get the user that owns the subscription.
     */
    public function user()
    {
        $model = getenv('GERENCIANET_MODEL') ?: config('services.gerencianet.model', 'User');
        return $this->belongsTo($model, 'user_id');
    }

    /**
     *  Create a new Charge
     * @param  [type] $amount  [description]
     * @param  [type] $options [description]
     * @return [type]          [description]
     */
    public function new($amount, $options)
    {
        $charge = new GerencianetCharge;
        $charge = $charge->create($amount, $options);

        if( isset($charge['code']) && $charge['code'] == 200 ){           
           $this->fill( $charge['data'] );
    	   return $this->toArray();
        }

        return $charge;

    }
    
}