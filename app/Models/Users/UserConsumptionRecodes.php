<?php

namespace App\Models\Users;

use App\Models\EloquentModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Users\UserOrder;


class UserConsumptionRecodes extends EloquentModel
{
	use SoftDeletes;
	
    protected $fillable = [
		'serial_number','car_number','park_name','amount',
		'payment_channel','payment_type','payment_account','channel_transaction_no','status',
		'user_id','user_type','order_id','order_type'
	];
	
}
