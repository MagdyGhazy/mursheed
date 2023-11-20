<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
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
            'name'=>'required|string ',
            'owner_info'=>'required|string ',
            'description'=>'required|string ',
            'address'=>'required|string ',
            'country_id'=>'required|int ',
            'state_id'=>'required|int ',
            'city_id'=>'required|int ',
            'aval_status'=>'required|int ',
            'info_status'=>'required|int ',
            'category_accommodations_id'=>'required|int ',
        ];
    }
}
