<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OfferRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
//    public function withValidator($validator)
//    {
//        $validator->after(function ($validator) {
//            $validator->errors()->add('status', false);
//
//        });
//    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            // 'number'=>"required|string",
            'title'=>"required",
            'price'=>'required',
            'status'=>'required',
            'images'=>'required'
        ];
    }
}
