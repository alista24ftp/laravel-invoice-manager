<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InvoiceFormRequest extends FormRequest
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
        $rules = [
            // invoice info
            'create_date' => 'required|regex:/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/',
            'sales_rep' => 'nullable|max:65',
            'po_no' => 'required|max:255',
            'terms' => 'required|max:255',
            'via' => 'required|max:255',
            //'memo' => 'nullable',
            //'notes' => 'nullable',
            'paid' => 'nullable|in:0,1', // nullable since paid checkbox won't submit values if unchecked

            // company info
            'company_name' => 'required|max:255',
            'company_mail_addr' => 'required|max:255',
            'company_mail_postal' => 'required|size:6|regex:/^[A-Za-z][0-9][A-Za-z][0-9][A-Za-z][0-9]$/i',
            'company_email' => 'required|max:255|email',
            'company_website' => ['nullable','max:255'],
            'company_ware_addr' => 'required|max:255',
            'company_ware_postal' => 'required|size:6|regex:/^[A-Za-z][0-9][A-Za-z][0-9][A-Za-z][0-9]$/i',
            'company_fax' => 'nullable|min:10|max:11|regex:/^[0-9]{10,11}$/i',
            'company_tel' => 'required|min:10|max:11|regex:/^[0-9]{10,11}$/i',
            'company_tollfree' => 'nullable|min:10|max:11|regex:/^[0-9]{10,11}$/i',
            'company_contact_fname' => 'required|max:30|regex:/^[A-Za-z \-\']+$/i',
            'company_contact_lname' => 'nullable|max:30|regex:/^[A-Za-z \-\']+$/i',
            'company_contact_tel' => 'required|min:10|max:11|regex:/^[0-9]{10,11}$/i',
            'company_contact_cell' => 'nullable|min:10|max:11|regex:/^[0-9]{10,11}$/i',
            'company_contact_email' => 'nullable|max:255|email',
            'company_tax_reg' => 'required|max:255',

            // customer info
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
            'customer_tel' => 'required|min:10|max:11|regex:/^[0-9]{10,11}$/i',
            'customer_fax' => 'nullable|min:10|max:11|regex:/^[0-9]{10,11}$/i',
            'customer_contact1' => 'required|max:30|regex:/^[A-Za-z \-\']+$/i',
            'customer_contact2' => 'nullable|max:30|regex:/^[A-Za-z \-\']+$/i',

            // order info
            'tax_rate' => 'nullable|numeric|min:0.000|max:99.999',
            'tax_description' => 'required|min:3|max:25',
            'freight' => 'nullable|numeric|min:0.00|max:999.99',
            'orders' => 'required|array|min:1',
            'orders.*.product' => 'required|string|max:255',
            'orders.*.price' => 'nullable|numeric|min:0.00|max:10000.00',
            'orders.*.quantity' => 'required|integer',
            'orders.*.discount' => 'nullable|numeric|min:0|max:1000000.00',
            'orders.*.total' => 'required|numeric|max:1000000.00',
        ];

        switch($this->method())
        {
            // creating new invoice
            case 'POST':
            {
                $rules['invoice_no'] = 'nullable|regex:/^[0-9]*$/|unique:invoices,invoice_no';
                return $rules;
            }

            // updating existing invoice
            case 'PUT':
            case 'PATCH':
            {
                $rules['invoice_no'] = 'required|regex:/^[0-9]*$/|same:old_invoice_no';
                $rules['old_invoice_no'] = 'required|regex:/^[0-9]*$/';
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

    // Validation messages
    public function messages()
    {
        return [
            // invoice info
            'invoice_no.regex' => 'Invoice number must only contain digits',
            'invoice_no.unique' => 'Invoice number already exists',
            'invoice_no.required' => 'Invoice number is required',
            'invoice_no.same' => 'Invoice number must match old existing invoice number',
            'old_invoice_no.required' => 'Old invoice number must exist',
            'old_invoice_no.regex' => 'Old invoice number must only contain digits',
            'create_date.required' => 'Invoice must have a create date',
            'create_date.regex' => 'Incorrect create date format',
            'sales_rep.max' => 'Sales rep name cannot be longer than 65 characters',
            'po_no.required' => 'PO Number cannot be empty',
            'po_no.max' => 'PO Number cannot be longer than 255 characters',
            'terms.required' => 'Payment terms cannot be empty',
            'terms.max' => 'Payment terms cannot be longer than 255 characters',
            'via.required' => 'Shipping option cannot be empty',
            'via.max' => 'Shipping option cannot be longer than 255 characters',
            'paid.in' => 'Payment status must be 0 or 1',

            // company info
            'company_name.required' => 'Company name cannot be empty',
            'company_name.max' => 'Company name cannot be longer than 255 characters',
            'company_mail_addr.required' => 'Company mailing address cannot be empty',
            'company_mail_addr.max' => 'Company mailing address cannot be longer than 255 characters',
            'company_mail_postal.required' => 'Company mailing postal code cannot be empty',
            'company_mail_postal.size' => 'Company mailing postal code must be 6 characters',
            'company_mail_postal.regex' => 'Incorrect company mailing postal code format',
            'company_email.required' => 'Company email cannot be empty',
            'company_email.max' => 'Company email cannot be longer than 255 characters',
            'company_email.email' => 'Company email must be in proper email format',
            'company_website.max' => 'Company website cannot be longer than 255 characters',
            //'company_website.regex' => 'Company website must be a proper URL',
            'company_ware_addr.required' => 'Company warehouse address cannot be empty',
            'company_ware_addr.max' => 'Company warehouse address cannot be longer than 255 characters',
            'company_ware_postal.required' => 'Company warehouse postal code cannot be empty',
            'company_ware_postal.size' => 'Company warehouse postal code must be 6 characters',
            'company_ware_postal.regex' => 'Incorrect company warehouse postal code format',
            'company_fax.min' => 'Company fax number must have minimum 10 digits',
            'company_fax.max' => 'Company fax number must have maximum 11 digits',
            'company_fax.regex' => 'Company fax number must contain only digits',
            'company_tel.required' => 'Company telephone number cannot be empty',
            'company_tel.min' => 'Company telephone number must have minimum 10 digits',
            'company_tel.max' => 'Company telephone number must have maximum 11 digits',
            'company_tel.regex' => 'Company telephone number must contain only digits',
            'company_tollfree.min' => 'Company toll-free number must have minimum 10 digits',
            'company_tollfree.max' => 'Company toll-free number must have maximum 11 digits',
            'company_tollfree.regex' => 'Company toll-free number must contain only digits',
            'company_contact_fname.required' => 'Company contact first name is required',
            'company_contact_fname.max' => 'Company contact first name cannot be longer than 30 characters',
            'company_contact_fname.regex' => 'Company contact first name can only contain letters, dashes, spaces, and apostrophes',
            'company_contact_lname.max' => 'Company contact last name cannot be longer than 30 characters',
            'company_contact_lname.regex' => 'Company contact last name can only contain letters, dashes, spaces, and apostrophes',
            'company_contact_tel.required' => 'Company contact telephone number is required',
            'company_contact_tel.min' => 'Company contact telephone number must have minimum 10 digits',
            'company_contact_tel.max' => 'Company contact telephone number must have maximum 11 digits',
            'company_contact_tel.regex' => 'Company contact telephone number must contain only digits',
            'company_contact_cell.min' => 'Company contact cellphone number must have minimum 10 digits',
            'company_contact_cell.max' => 'Company contact cellphone number must have maximum 11 digits',
            'company_contact_cell.regex' => 'Company contact cellphone number must contain only digits',
            'company_contact_email.max' => 'Company contact email length cannot be longer than 255',
            'company_contact_email.email' => 'Company contact email must be in proper email format',
            'company_tax_reg.required' => 'Tax registration number cannot be empty',
            'company_tax_reg.max' => 'Tax registration number cannot be longer than 255 characters',

            // customer info
            'bill_name.required' => 'Customer billing name is required',
            'bill_name.max' => 'Customer billing name cannot be longer than 255 characters',
            'bill_addr.required' => 'Customer billing address is required',
            'bill_addr.max' => 'Customer billing address cannot be longer than 255 characters',
            'bill_prov.required' => 'Customer billing province is required',
            'bill_prov.in' => 'Customer billing province must be a Canadian province',
            'bill_city.required' => 'Customer billing city is required',
            'bill_city.max' => 'Customer billing city cannot be longer than 50 characters',
            'bill_city.regex' => 'Customer billing city can only contain letters, digits, spaces, dashes, and apostrophes',
            'bill_postal.required' => 'Customer billing postal code is required',
            'bill_postal.size' => 'Customer billing postal code must have length 6',
            'bill_postal.regex' => 'Incorrect customer billing postal code format',
            'ship_name.required' => 'Customer shipping name is required',
            'ship_name.max' => 'Customer shipping name length cannot be longer than 255',
            'ship_addr.required' => 'Customer shipping address is required',
            'ship_addr.max' => 'Customer shipping address length cannot be longer than 255',
            'ship_prov.required' => 'Customer shipping province is required',
            'ship_prov.in' => 'Customer shipping province must be a Canadian province',
            'ship_city.required' => 'Customer shipping city is required',
            'ship_city.max' => 'Customer shipping city cannot be longer than 50 characters',
            'ship_city.regex' => 'Customer shipping city can only contain letters, digits, spaces, dashes, and apostrophes',
            'ship_postal.required' => 'Customer shipping postal code is required',
            'ship_postal.size' => 'Customer shipping postal code must have length 6',
            'ship_postal.regex' => 'Incorrect customer shipping postal code format',
            'customer_tel.required' => 'Customer telephone number is required',
            'customer_tel.min' => 'Customer telephone number cannot be shorter than 10 digits',
            'customer_tel.max' => 'Customer telephone number cannot be longer than 11 digits',
            'customer_tel.regex' => 'Customer telephone number can only contain digits',
            'customer_fax.min' => 'Customer fax number cannot be shorter than 10 digits',
            'customer_fax.max' => 'Customer fax number cannot be longer than 11 digits',
            'customer_fax.regex' => 'Customer fax number can only contain digits',
            'customer_contact1.required' => 'Customer contact 1 is required',
            'customer_contact1.max' => 'Customer contact 1 name length cannot be longer than 30',
            'customer_contact1.regex' => 'Customer contact 1 name can only contain letters, dashes, spaces, and apostrophes',
            'customer_contact2.max' => 'Customer contact 2 name length cannot be longer than 30',
            'customer_contact2.regex' => 'Customer contact 2 name can only contain letters, dashes, spaces, and apostrophes',

            // order info
            'tax_rate.numeric' => 'Tax rate percentage must be a decimal number',
            'tax_rate.min' => 'Tax rate percentage must be greater than or equal to 0',
            'tax_rate.max' => 'Tax rate percentage must be less than or equal to 99.999',
            'tax_description.required' => 'Tax description is required',
            'tax_description.min' => 'Tax description length must be 3 characters or longer',
            'tax_description.max' => 'Tax description length must be shorter than 25',
            'freight.numeric' => 'Freight charge must be a decimal number',
            'freight.min' => 'Freight charge must be greater than or equal to 0',
            'freight.max' => 'Freight charge must be less than or equal to 999.99',
            'orders.required' => 'Orders must exist',
            'orders.array' => 'Orders must be an array',
            'orders.min' => 'Orders array must have at least one item',
            'orders.*.product.required' => 'Order item must have a product name',
            'orders.*.product.string' => 'Order product name must be a string',
            'orders.*.product.max' => 'Order product name has max length of 255',
            'orders.*.price.numeric' => 'Order item price must be a number',
            'orders.*.price.min' => 'Order item has minimum price of 0',
            'orders.*.price.max' => 'Order item has maximum price of 10000.00',
            'orders.*.quantity.required' => 'Order item cannot have quantity of 0',
            'orders.*.quantity.integer' => 'Order item quantity must be an integer',
            'orders.*.discount.numeric' => 'Order item discount is a number',
            'orders.*.discount.min' => 'Minimum order item discount is 0',
            'orders.*.discount.max' => 'Order item discount must be less than or equal to 1000000.00',
            'orders.*.total.required' => 'Order item total is required',
            'orders.*.total.numeric' => 'Order item total must be a number',
            'orders.*.total.max' => 'Order item total has maximum of 1000000.00',
        ];
    }
}
