<?php

namespace App\Models\Customers;

use App\Models\EloquentModel;
use Illuminate\Database\Eloquent\Model;

class ProjectGroup extends EloquentModel
{
    protected $fillable = ['group_name'];
}
