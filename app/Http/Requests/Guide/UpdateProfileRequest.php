<?php

namespace App\Http\Requests\Guide;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

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
          //  "email" => ["nullable", "email", Rule::unique('mursheed_users')->ignore($this->user()->id, 'id')],
            "nationality" => "nullable|string",
            "country_id" => "nullable|int",
            "state_id" => "nullable|int",
            "password" => "nullable|string",

            "phone" => "nullable|string",
            "bio" => "nullable|string",
            "gender" => "nullable|int",
            'personal_pictures' => "nullable",
            'languages' => "nullable"
        ];
    }
}
