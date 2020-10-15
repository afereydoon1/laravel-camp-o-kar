<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApplyDiscountRequest extends FormRequest
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
            'price' => 'required',
            'discount' => 'required|string|exists:discounts,code'
        ];
    }

    public function messages()
    {
        return [
            'discount.exists' => 'کد تخفیف ارسال شده وجود ندارد'
        ];
    }
}
