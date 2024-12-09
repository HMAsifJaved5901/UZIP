<?php

namespace App\Http\Requests\Stations;

use Illuminate\Foundation\Http\FormRequest;

class Store extends FormRequest 
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
            'category_id' => 'required|integer',
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255',
            'company_id' => 'required|integer|exists:companies,id',
            'manager_id' => 'nullable|integer|exists:users,id',
            'location' => 'required|string|max:255',
            'phone' => 'nullable|string|max:15',
        ];
    }

    /**
    * Get the error messages for the defined validation rules.
    *
    * @return array
    */
    public function messages()
    {
        return [
     
        ];
    }

}
