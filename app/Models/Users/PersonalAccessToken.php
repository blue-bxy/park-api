<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class PersonalAccessToken extends Model
{
    use HasApiTokens, Notifiable;
}
