<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TouristRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

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
            "password" => "required|string",
            "nationality" => "nullable|string",
            'dest_city_id' => "nullable|int",
            "gender" => "nullable|int",
            'personal_pictures' => "nullable",
        ];
    }
}
