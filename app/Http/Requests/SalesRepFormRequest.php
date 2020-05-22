<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SalesRepFormRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'firstname' => 'required|max:30|regex:/^[A-Z][A-Za-z \-\'\.]*$/i',
            'lastname' => 'required|max:30|regex:/^[A-Z][A-Za-z \-\'\.]*$/i',
            'tel' => 'required|min:10|max:11|regex:/^[0-9]{10,11}$/i',
            'cell' => 'nullable|min:10|max:11|regex:/^[0-9]{10,11}$/i',
            'email' => 'nullable|max:255|email',
        ];
        switch($this->method())
        {
            case 'POST':
            {
                return $rules;
            }

            case 'PUT':
            case 'PATCH':
            {
                $rules['id'] = 'required';
                return $rules;
            }

            case 'DELETE':
            {
                return [
                    'id' => 'required'
                ];
            }

            case 'GET':
            default:
            {
                return [];
            }
        }
    }

    public function messages()
    {
        return [
            'id.required' => 'Sales rep ID cannot be empty',
            'firstname.required' => 'Sales rep first name cannot be empty',
            'firstname.max' => 'Sales rep first name cannot be longer than 30 characters',
            'firstname.regex' => 'Incorrect sales rep first name format',
            'lastname.required' => 'Sales rep last name cannot be empty',
            'lastname.max' => 'Sales rep last name cannot be longer than 30 characters',
            'lastname.regex' => 'Incorrect sales rep last name format',
            'tel.required' => 'Sales rep telephone cannot be empty',
            'tel.min' => 'Sales rep telephone must be 10 digits or more',
            'tel.max' => 'Sales rep telephone must be 11 digits or less',
            'tel.regex' => 'Incorrect sales rep telephone number format',
            'cell.min' => 'Sales rep cellphone must be 10 digits or more',
            'cell.max' => 'Sales rep cellphone must be 11 digits or less',
            'cell.regex' => 'Incorrect sales rep cellphone number format',
            'email.max' => 'Sales rep email cannot be longer than 255 characters',
            'email.email' => 'Incorrect sales rep email format',
        ];
    }
}
