<?php

namespace Modules\Product\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required'],
            'category_ids' => ['required'],
            'brand_id' => ['required'],
          
        ];
    }

    public function messages()
    {
        return [
            'stock.min' => 'Need to add 1 stock minimun.',
            'price.min' => 'Need to add 1 price minimun.',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
