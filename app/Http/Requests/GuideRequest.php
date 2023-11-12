<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GuideRequest extends FormRequest
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
            "nationality" => "required|string",
            "country_id" => "required|int",
            "state_id" => "required|int",
            "password" => "required|string",

            "phone" => "nullable|string",
            "bio" => "nullable|string",
            "gender" => "nullable|int",
            'personal_pictures' => "nullable",
            'languages' => "nullable"
        ];
    }
}
