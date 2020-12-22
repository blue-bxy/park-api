<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseRequest;
use App\Rules\PasswordValidateRule;
use App\Rules\PhoneNumberValidate;

class AdminUserRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->method()) {
            case 'POST':
                return [
                    'name' => 'required',
                    // 'account_name' => 'required',
                    'email' => 'required|string|email',
                    'password' => ['required', 'string', 'min:8','max:16', new PasswordValidateRule()],
                    'password_confirm' => 'required|string|same:password',
                    // 'departments' => 'sometimes|required|string',
                    // 'roles' => 'sometimes|required|string',
                    'mobile' => ['required', new PhoneNumberValidate()]
                ];
            case "PUT":
                return [
                    'name' => 'required',
                    // 'account_name' => 'required',
                    'email' => 'required|string|email',
                    // 'password' => ['required', 'string', 'min:8','max:16', new PasswordValidateRule()],
                    // 'password_confirm' => 'required|string|same:password',
                    // 'departments' => 'sometimes|required|string',
                    // 'roles' => 'sometimes|required|string',
                    'mobile' => ['required', new PhoneNumberValidate()]
                ];
            default:
                break;
        }

    }
}
