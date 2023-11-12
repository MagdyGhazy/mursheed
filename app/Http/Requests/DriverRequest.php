<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DriverRequest extends FormRequest
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

            "name" => "required|string",
            "email" => "required|email|unique:mursheed_users",
            "country_id" => "required|int",
            "state_id" => "required|int",
            "nationality" => "required|string",
            "password" => "required|string",

            "gov_id" => "nullable|string",
            "gender" => "nullable|int",
            "phone" => "nullable|string",
            "bio" => "nullable|string",
            "car_number" => "nullable|string",
            "driver_licence_number" => "nullable|string",
            'car_photos' => "nullable|image",
            'personal_pictures' => "nullable",
            'languages' => 'nullable'
        ];
    }
}
