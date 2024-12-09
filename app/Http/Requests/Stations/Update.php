<?php

namespace App\Http\Requests\Stations;

use Illuminate\Foundation\Http\FormRequest;

class Update extends FormRequest 
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
			'category_id' => 'required|numeric',
			'name' => 'required|max:100',
			'code' => 'required|max:100',
			'company_id' => 'required|numeric',
			'manager_id' => 'required|numeric',
			'location' => 'required|max:255',
			'latitude' => 'required|numeric',
			'longitude' => 'required|numeric',
			'phone' => 'nullable|max:15',
			'opening_hours' => 'nullable|max:50',
			'is_active' => 'nullable|numeric',
			'is_deleted' => 'nullable|boolean',
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
