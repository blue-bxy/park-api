<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserComplaintRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'content' => 'required|string',
            'img' => 'required|array|min:1|max:9',
            'img.*' => 'required|image'
        ];
    }

    public function messages()
    {
        return [
            'content.required' => '请填写投诉内容',
            'img.required' => '请上传至少:min张图片',
            'img.max' => '最多上传:max张图片',
            'img.*.image' => '请上传图片格式',
        ];
    }
}
