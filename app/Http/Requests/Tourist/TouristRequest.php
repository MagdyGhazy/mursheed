<?php

namespace App\Http\Requests\Tourist;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            "email" => ["required", "email", Rule::unique('mursheed_users'), Rule::unique('tourists')],
            "password" => "required|string",
            "nationality" => "nullable|string",
            'dest_city_id' => "nullable|int",
            "gender" => "nullable|int",
            'personal_pictures' => "nullable",
        ];
    }
}
