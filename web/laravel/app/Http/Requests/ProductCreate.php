<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductCreate extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'productname' => 'required',
            'alldot' => 'numeric|between:100,1600',
            'linedot' => 'required|numeric|between:10,40',
        ];
    }

    public function messages()
    {
        return[
            'productname.required' => '作品名を入力してください。',
            'linedot.required' => 'ドット数を入力してください。',
            'linedot.numeric' => 'ドット数は整数で入力してください。',
            'linedot.between:10,40' => '1列に配置できるドット数は10個〜40個までです。'
        ];
    }
}
