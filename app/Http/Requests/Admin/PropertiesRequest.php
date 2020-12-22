<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseRequest;
use App\Rules\PasswordValidateRule;

class PropertiesRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'mobile' => 'bail|required|regex:/^1[345789][0-9]{9}$/|unique:properties' . $this->id,
            'email' => 'bail|required|unique:properties' . $this->id,
            'password' => ['bail', 'required', 'string', 'min:8', 'max:16', new PasswordValidateRule()],
            'password_confirm' => 'required|string|same:password',
            'name' => 'bail|required|max:20',
            'departments' => 'bail|required',
            'roles' => 'bail|required'
        ];
    }

}
