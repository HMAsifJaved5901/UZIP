<?php

namespace App\Http\Requests\Services;

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
			'name' => 'required|unique:services,name|max:100',
			'description' => 'nullable|max:255',
			'category_id' => 'required|numeric',
			'is_active' => 'nullable|numeric',
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
