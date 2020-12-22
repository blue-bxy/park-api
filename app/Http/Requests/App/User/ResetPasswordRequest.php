<?php

namespace App\Http\Requests\App\User;

use App\Http\Requests\BaseRequest;
use App\Rules\UserPasswordValide;

class ResetPasswordRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'old_password' => ['required', new UserPasswordValide()],
			'password' => 'required',
			'password_confirmation' => ['required',"same:password"],
        ];
    }
}
