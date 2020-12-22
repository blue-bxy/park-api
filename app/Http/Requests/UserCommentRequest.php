<?php

namespace App\Http\Requests;

class UserCommentRequest extends BaseRequest
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
            'img' => 'sometimes|required|array|min:1|max:9',
            'img.*' => 'required|image',
            'rate' => 'required|Integer|max:5',
        ];
    }
    public function messages()
    {
        return [
            'content.required' => '请填写评论内容',
            'img.required' => '图片不能为空',
            'img.min' => '请上传至少:min张图片',
            'img.max' => '最多上传:max张图片',
            'img.*.image' => '请上传图片格式',
            'rate.required' => '评价星级不能为空',
        ];
    }
}
