<?php

namespace App\Models\Financial;

use App\Models\EloquentModel;
use Illuminate\Database\Eloquent\Model;

class ApplyMiddle extends EloquentModel
{
    protected $fillable=['order_type','order_id','apply_id'];

    public function order(){
       return  $this->morphTo();
    }
}
