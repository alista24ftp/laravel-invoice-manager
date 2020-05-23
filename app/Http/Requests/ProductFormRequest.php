<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductFormRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'name' => 'required|min:3|max:100',
            'price' => 'nullable|numeric|min:0|max:10000.00'
        ];
        switch($this->method()){
            case "POST":
            {
                return $rules;
            }
            case 'PUT':
            case 'PATCH':
            {
                $rules['id'] = 'required';
                return $rules;
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
            'id.required' => 'Product ID cannot be empty',
            'name.required' => 'Product name cannot be empty',
            'name.min' => 'Product name cannot be less than 3 characters long',
            'name.max' => 'Product name cannot be longer than 100 characters',
            'price.numeric' => 'Product price must be a number',
            'price.min' => 'Product price must be 0 or more',
            'price.max' => 'Product price must be 10000.00 or less'
        ];
    }
}
