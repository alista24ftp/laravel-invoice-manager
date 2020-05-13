<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'InvoicesController@index')->name('invoices.index');
Route::get('invoices/create', 'InvoicesController@create')->name('invoices.create'); // very important to put this here (above invoices.show route)
Route::get('invoices/restore', 'InvoicesController@restoreProgress')->name('invoices.restore');
Route::get('invoices/getspreadsheet/{invoice}', 'InvoicesController@getSpreadsheet')->name('invoices.getspreadsheet');
Route::get('invoices/{invoice}', 'InvoicesController@show')->name('invoices.show');
Route::post('invoices', 'InvoicesController@store')->name('invoices.store');
Route::post('invoices/save', 'InvoicesController@saveProgress')->name('invoices.save');
Route::get('invoices/{invoice}/edit', 'InvoicesController@edit')->name('invoices.edit');
Route::put('invoices/{invoice}', 'InvoicesController@update')->name('invoices.update');
//Route::get('invoices/{invoice}/duplicate', 'InvoicesController@duplicate')->name('invoices.duplicate');
Route::get('invoices/{invoice}/duplicate', 'InvoicesController@create')->name('invoices.duplicate');
Route::delete('invoices/delete_progress', 'InvoicesController@deleteProgress')->name('invoices.delete_progress');

Route::post('proofs', 'PaymentProofsController@store')->name('proofs.upload');
Route::delete('proofs/delete_temp', 'PaymentProofsController@deleteTemp')->name('proofs.delete_temp');
Route::delete('proofs/{proof}', 'PaymentProofsController@delete')->name('proofs.delete');

Route::get('login', 'UsersController@login')->name('login');
Route::post('login', 'UsersController@loginUser')->name('login');
Route::delete('logout', 'UsersController@logout')->name('logout');

Route::get('customers', 'CustomersController@index')->name('customers.index');
//Route::get('customers/{customer}', 'CustomersController@show')->name('customers.show');
Route::get('customers/create', 'CustomersController@create')->name('customers.create');
Route::post('customers', 'CustomersController@store')->name('customers.store');
Route::get('customers/{customer}/edit', 'CustomersController@edit')->name('customers.edit');
Route::patch('customers/{customer}', 'CustomersController@update')->name('customers.update');
Route::get('customers/autocomplete', 'CustomersController@autocomplete')->name('customers.autocomplete');

Route::get('sales', 'SalesController@index')->name('sales.index');

Route::get('users/{user}/edit', 'UsersController@edit')->name('users.edit');

Route::get('companies/{company}/edit', 'CompaniesController@edit')->name('companies.edit');

Route::get('products', 'ProductsController@index')->name('products.index');
Route::get('products/selections', 'ProductsController@selections')->name('products.selections');

Route::get('shipping', 'ShippingController@index')->name('shipping.index');

Route::get('terms', 'TermsController@index')->name('terms.index');

Route::get('taxes', 'TaxesController@index')->name('taxes.index');
