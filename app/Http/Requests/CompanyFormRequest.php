<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyFormRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'company_name' => 'required|max:255',
            'mail_addr' => 'required|max:255',
            'mail_postal' => 'required|size:6|regex:/^[A-Za-z][0-9][A-Za-z][0-9][A-Za-z][0-9]$/i',
            'warehouse_addr' => 'required|max:255',
            'warehouse_postal' => 'required|size:6|regex:/^[A-Za-z][0-9][A-Za-z][0-9][A-Za-z][0-9]$/i',
            'email' => 'required|max:255|email',
            'website' => 'nullable|max:255',
            'tax_reg' => 'required|max:255',
            'tel' => 'required|min:10|max:11|regex:/^[0-9]{10,11}$/i',
            'fax' => 'nullable|min:10|max:11|regex:/^[0-9]{10,11}$/i',
            'toll_free' => 'nullable|min:10|max:11|regex:/^[0-9]{10,11}$/i',
            'contact1_firstname' => 'required|max:30|regex:/^[A-Z][A-Za-z \-\'\.]*$/i',
            'contact1_lastname' => 'nullable|max:30|regex:/^[A-Z][A-Za-z \-\'\.]*$/i',
            'contact1_tel' => 'required|min:10|max:11|regex:/[0-9]{10,11}/i',
            'contact1_ext' => 'nullable|max:6|regex:/^[0-9]*$/i',
            'contact1_email' => 'nullable|max:255|email',
            'contact1_cell' => 'nullable|min:10|max:11|regex:/^[0-9]{10,11}$/i',
            'contact2_firstname' => 'nullable|max:30|regex:/^[A-Z][A-Za-z \-\'\.]*$/i',
            'contact2_lastname' => 'nullable|max:30|regex:/^[A-Z][A-Za-z \-\'\.]*$/i',
            'contact2_tel' => 'nullable|min:10|max:11|regex:/[0-9]{10,11}/i',
            'contact2_ext' => 'nullable|max:6|regex:/^[0-9]*$/i',
            'contact2_email' => 'nullable|max:255|email',
            'contact2_cell' => 'nullable|min:10|max:11|regex:/^[0-9]{10,11}$/i',
            'logo' => 'nullable|file|mimes:png,jpg,jpeg,gif'
        ];

        switch($this->method()){
            case 'POST':
                return $rules;

            case 'PUT':
            case 'PATCH':
            {
                $rules['id'] = 'required';
                return $rules;
            }

            case 'GET':
            case 'DELETE':
            default:
            {
                return [];
            }
        }
    }

    public function messages()
    {
        return [
            'id.required' => 'Company ID cannot be empty',
            'company_name.required' => 'Company name cannot be empty',
            'company_name.max' => 'Company name cannot be longer than 255 characters',
            'mail_addr.required' => 'Mailing address cannot be empty',
            'mail_addr.max' => 'Mailing address cannot be longer than 255 characters',
            'mail_postal.required' => 'Mailing postal code cannot be empty',
            'mail_postal.size' => 'Mailing postal code must be 6 characters long',
            'mail_postal.regex' => 'Incorrect mailing postal code format',
            'warehouse_addr.required' => 'Warehouse address cannot be empty',
            'warehouse_addr.max' => 'Warehouse address cannot be longer than 255 characters',
            'warehouse_postal.required' => 'Warehouse postal code cannot be empty',
            'warehouse_postal.size' => 'Warehouse postal code must be 6 characters long',
            'warehouse_postal.regex' => 'Incorrect warehouse postal code format',
            'email.required' => 'Company email cannot be empty',
            'email.max' => 'Company email cannot be longer than 255 characters',
            'email.email' => 'Incorrect company email format',
            'website.max' => 'Company website cannot be longer than 255 characters',
            'tax_reg.required' => 'Tax registration number cannot be empty',
            'tax_reg.max' => 'Tax registration number cannot be longer than 255 characters',
            'tel.required' => 'Company telephone number cannot be empty',
            'tel.min' => 'Company telephone number must be 10 digits or more',
            'tel.max' => 'Company telephone number must be 11 digits or less',
            'tel.regex' => 'Incorrect company telephone number format',
            'fax.min' => 'Company fax number must be 10 digits or more',
            'fax.max' => 'Company fax number must be 11 digits or less',
            'fax.regex' => 'Incorrect company fax number format',
            'toll_free.min' => 'Company toll free number must be 10 digits or more',
            'toll_free.max' => 'Company toll free number must be 11 digits or less',
            'toll_free.regex' => 'Incorrect company toll free number format',
            'contact1_firstname.required' => 'Contact 1 first name cannot be empty',
            'contact1_firstname.max' => 'Contact 1 first name cannot be longer than 30 characters',
            'contact1_firstname.regex' => 'Incorrect contact 1 first name format',
            'contact1_lastname.max' => 'Contact 1 last name cannot be longer than 30 characters',
            'contact1_lastname.regex' => 'Incorrect contact 1 last name format',
            'contact1_tel.required' => 'Contact 1 telephone number cannot be empty',
            'contact1_tel.min' => 'Contact 1 telephone number must be 10 digits or more',
            'contact1_tel.max' => 'Contact 1 telephone number must be 11 digits or less',
            'contact1_tel.regex' => 'Incorrect contact 1 telephone number format',
            'contact1_ext.max' => 'Contact 1 phone extention cannot be longer than 6 digits',
            'contact1_ext.regex' => 'Incorrect contact 1 phone extention format',
            'contact1_email.max' => 'Contact 1 email cannot be longer than 255 characters long',
            'contact1_email.email' => 'Incorrect contact 1 email format',
            'contact1_cell.min' => 'Company contact 1 cellphone number must be 10 digits or more',
            'contact1_cell.max' => 'Company contact 1 cellphone number must be 11 digits or less',
            'contact1_cell.regex' => 'Incorrect company contact 1 cellphone number format',
            'contact2_firstname.max' => 'Contact 2 first name cannot be longer than 30 characters',
            'contact2_firstname.regex' => 'Incorrect contact 2 first name format',
            'contact2_lastname.max' => 'Contact 2 last name cannot be longer than 30 characters',
            'contact2_lastname.regex' => 'Incorrect contact 2 last name format',
            'contact2_tel.min' => 'Contact 2 telephone number must be 10 digits or more',
            'contact2_tel.max' => 'Contact 2 telephone number must be 11 digits or less',
            'contact2_tel.regex' => 'Incorrect contact 2 telephone number format',
            'contact2_ext.max' => 'Contact 2 phone extention cannot be longer than 6 digits',
            'contact2_ext.regex' => 'Incorrect contact 2 phone extention format',
            'contact2_email.max' => 'Contact 2 email cannot be longer than 255 characters long',
            'contact2_email.email' => 'Incorrect contact 2 email format',
            'contact2_cell.min' => 'Company contact 2 cellphone number must be 10 digits or more',
            'contact2_cell.max' => 'Company contact 2 cellphone number must be 11 digits or less',
            'contact2_cell.regex' => 'Incorrect company contact 2 cellphone number format',
            'logo.file' => 'Company logo must be a valid file',
            'logo.mimes' => 'Company logo must be a valid image'
        ];
    }
}
