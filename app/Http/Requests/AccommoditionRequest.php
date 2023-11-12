<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AccommoditionRequest extends FormRequest
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
//                $validator->errors()->add('status', false);
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
            "name" => "required|string",
            "address" => "required|string",
            "country_id" => "required|int",
            "city_id" => "required|int",
            "state_id" => "required|int",
            "owner_info" => "required|string",
            "description" => "required|string",
            "aval_status" => "required",
            "info_status" => "required",
            "category_accommodations_id"=>"required",
            "rooms"=>"required"
        ];
    }
}
