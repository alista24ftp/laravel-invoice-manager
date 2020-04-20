<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerFormRequest extends FormRequest
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
        switch($this->method())
        {
            // CREATE
            case 'POST':

            // UPDATE
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'bill_name' => 'required|max:255',
                    'bill_addr' => 'required|max:255',
                    'bill_prov' => 'required|in:'. implode(',',array_keys(canadian_provinces())),
                    'bill_city' => 'required|max:50|regex:/^[A-Za-z0-9 \'\-]+$/i',
                    'bill_postal' => 'required|size:6|regex:/^[A-Za-z][0-9][A-Za-z][0-9][A-Za-z][0-9]$/i',
                    'ship_name' => 'required|max:255',
                    'ship_addr' => 'required|max:255',
                    'ship_prov' => 'required|in:'. implode(',',array_keys(canadian_provinces())),
                    'ship_city' => 'required|max:50|regex:/^[A-Za-z0-9 \'\-]+$/i',
                    'ship_postal' => 'required|size:6|regex:/^[A-Za-z][0-9][A-Za-z][0-9][A-Za-z][0-9]$/i',
                    'email' => 'nullable|max:255|email',
                    'fax' => 'nullable|min:10|max:11|regex:/^[0-9]{10,11}$/i',
                    'contact1_firstname' => 'required|max:30|regex:/^[A-Za-z \-\']+$/i',
                    'contact1_lastname' => 'nullable|max:30|regex:/^[A-Za-z \-\']+$/i',
                    'contact1_tel' => 'required|min:10|max:11|regex:/^[0-9]{10,11}$/i',
                    'contact1_cell' => 'nullable|min:10|max:11|regex:/^[0-9]{10,11}$/i',
                    'contact2_firstname' => 'nullable|max:30|regex:/^[A-Za-z \-\']+$/i',
                    'contact2_lastname' => 'nullable|max:30|regex:/^[A-Za-z \-\']+$/i',
                    'contact2_tel' => 'nullable|min:10|max:11|regex:/^[0-9]{10,11}$/i',
                    'contact2_cell' => 'nullable|min:10|max:11|regex:/^[0-9]{10,11}$/i',
                ];
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
        // Validation messages
        return [
            'bill_name.required' => 'Billing name cannot be empty',
            'bill_name.max' => 'Billing name length cannot be longer than 255 chars',
            'bill_addr.required' => 'Billing address cannot be empty',
            'bill_addr.max' => 'Billing address length cannot be longer than 255 chars',
            'bill_prov.required' => 'Billing province cannot be empty',
            'bill_prov.in' => 'Billing province is not a valid province',
            'bill_city.required' => 'Billing city cannot be empty',
            'bill_city.max' => 'Billing city name length cannot be longer than 50 chars',
            'bill_city.regex' => 'Billing city incorrect format (alphanumeric, space, dash, apostrophes only)',
            'bill_postal.required' => 'Billing city postal code cannot be empty',
            'bill_postal.size' => 'Billing city postal code must be 6 chars (no spaces)',
            'bill_postal.regex' => 'Incorrect billing city postal code format',

            'ship_name.required' => 'Shipping name cannot be empty',
            'ship_name.max' => 'Shipping name length cannot be longer than 255 chars',
            'ship_addr.required' => 'Shipping address cannot be empty',
            'ship_addr.max' => 'Shipping address length cannot be longer than 255 chars',
            'ship_prov.required' => 'Shipping province cannot be empty',
            'ship_prov.in' => 'Shipping province is not a valid province',
            'ship_city.required' => 'Shipping city cannot be empty',
            'ship_city.max' => 'Shipping city name length cannot be longer than 50 chars',
            'ship_city.regex' => 'Shipping city incorrect format (alphanumeric, space, dash, apostrophes only)',
            'ship_postal.required' => 'Shipping city postal code cannot be empty',
            'ship_postal.size' => 'Shipping city postal code must be 6 chars (no spaces)',
            'ship_postal.regex' => 'Incorrect shipping city postal code format',

            'email.max' => 'Email length cannot be longer than 255 chars',
            'email.email' => 'Incorrect email format',
            'fax.min' => 'Fax number must be greater than or equal to 10 digits',
            'fax.max' => 'Fax number must be less than or equal to 11 digits',
            'fax.regex' => 'Incorrect fax format (must be all digits, no spaces)',

            'contact1_firstname.required' => 'Contact 1 first name cannot be empty',
            'contact1_firstname.max' => 'Contact 1 first name length cannot be longer than 30 chars',
            'contact1_firstname.regex' => 'Contact 1 first name can only contain letters, spaces, dashes, or apostrophes',
            'contact1_lastname.max' => 'Contact 1 last name length cannot be longer than 30 chars',
            'contact1_lastname.regex' => 'Contact 1 last name can only contain letters, spaces, dashes, or apostrophes',
            'contact1_tel.required' => 'Contact 1 telephone cannot be empty',
            'contact1_tel.min' => 'Contact 1 telephone must be greater than or equal to 10 digits',
            'contact1_tel.max' => 'Contact 1 telephone must be less than or equal to 11 digits',
            'contact1_tel.regex' => 'Incorrect contact 1 telephone format (must be all digits, no spaces)',
            'contact1_cell.min' => 'Contact 1 cell phone must be greater than or equal to 10 digits',
            'contact1_cell.max' => 'Contact 1 cell phone must be less than or equal to 11 digits',
            'contact1_cell.regex' => 'Incorrect contact 1 cell phone format (must be all digits, no spaces)',

            'contact2_firstname.max' => 'Contact 2 first name length cannot be longer than 30 chars',
            'contact2_firstname.regex' => 'Contact 2 first name can only contain letters, spaces, dashes, or apostrophes',
            'contact2_lastname.max' => 'Contact 2 last name length cannot be longer than 30 chars',
            'contact2_lastname.regex' => 'Contact 2 last name can only contain letters, spaces, dashes, or apostrophes',
            'contact2_tel.min' => 'Contact 2 telephone must be greater than or equal to 10 digits',
            'contact2_tel.max' => 'Contact 2 telephone must be less than or equal to 11 digits',
            'contact2_tel.regex' => 'Incorrect contact 2 telephone format (must be all digits, no spaces)',
            'contact2_cell.min' => 'Contact 2 cell phone must be greater than or equal to 10 digits',
            'contact2_cell.max' => 'Contact 2 cell phone must be less than or equal to 11 digits',
            'contact2_cell.regex' => 'Incorrect contact 2 cell phone format (must be all digits, no spaces)',

        ];
    }
}
