<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseRequest;

class BookingFeeRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'pro_plat_apt'=> "numeric",
            'pro_park_apt'=> "numeric",
            'pro_owner_apt'=> "numeric",
            'per_plat_apt'=> "numeric",
            'per_owner_apt'=> "numeric",
            'pro_plat_stop'=> "numeric",
            'pro_owner_stop'=> "numeric",
            'per_plat_stop'=> "numeric",
            'pro_park_stop'=> "numeric",
            'per_owner_stop'=> "numeric"
        ];
    }

    public function attributes()
    {
        return [
            'pro_plat_apt'=> "分成比例必须为数字",
            'pro_park_apt'=> "分成比例必须为数字",
            'pro_owner_apt'=> "分成比例必须为数字",
            'per_plat_apt'=> "分成比例必须为数字",
            'per_owner_apt'=> "分成比例必须为数字",
            'pro_plat_stop'=> "分成比例必须为数字",
            'pro_owner_stop'=> "分成比例必须为数字",
            'per_plat_stop'=> "分成比例必须为数字",
            'pro_park_stop'=> "分成比例必须为数字",
            'per_owner_stop'=> "分成比例必须为数字"
        ];
    }
}
