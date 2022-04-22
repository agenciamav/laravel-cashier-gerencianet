<?php

namespace AgenciaMav\LaravelCashierGerencianet\Models;

use Carbon\Carbon;
use AgenciaMav\LaravelCashierGerencianet\LaravelCashierGerencianet;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use LogicException;

/**
 * @property \AgenciaMav\LaravelCashierGerencianet\Billable|\Illuminate\Database\Eloquent\Model $owner
 */
class Plan extends Model
{
	use HasFactory;

	/**
	 * The attributes that are not mass assignable.
	 *
	 * @var array
	 */
	protected $guarded = [];

	/**
	 * The attributes that should be cast to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'name' => 'string',
		'interval' => 'integer',
		'repeats' => 'integer',
	];

	/**
	 * The date on which the billing cycle should be anchored.
	 *
	 * @var string|null
	 */
	protected $billingCycleAnchor = null;

	/**
	 * Get the user that owns the subscription.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function user()
	{
		return $this->owner();
	}

	/**
	 * Get the model related to the subscription.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function owner()
	{
		$model = LaravelCashierGerencianet::$customerModel;

		return $this->belongsTo($model, (new $model)->getForeignKey());
	}
}
