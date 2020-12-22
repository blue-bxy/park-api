<?php

namespace App\Http\Requests\App\User;

use App\Http\Requests\BaseRequest;

class UpdateHeadimgRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'headimgurl' => 'required',
        ];
    }
}
