<?php

namespace App\Http\Requests\Driver;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [

            "name" => "nullable|string",
            "email" => ["nullable", "email", Rule::unique('mursheed_users')->ignore($this->user()->id, 'id')],
            "country_id" => "nullable|int",
            "state_id" => "nullable|int",
            "nationality" => "nullable|string",
            "password" => "nullable|string",

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
