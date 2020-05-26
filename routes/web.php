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

// Invoice Routes
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

// Invoice Payment Proof Routes
Route::post('proofs', 'PaymentProofsController@store')->name('proofs.upload');
Route::delete('proofs/delete_temp', 'PaymentProofsController@deleteTemp')->name('proofs.delete_temp');
Route::delete('proofs/{proof}', 'PaymentProofsController@delete')->name('proofs.delete');

// Login/Logout Routes
Route::get('login', 'UsersController@login')->name('login');
Route::post('login', 'UsersController@loginUser')->name('login');
Route::delete('logout', 'UsersController@logout')->name('logout');

// Customer Routes
Route::get('customers', 'CustomersController@index')->name('customers.index');
//Route::get('customers/{customer}', 'CustomersController@show')->name('customers.show');
Route::get('customers/create', 'CustomersController@create')->name('customers.create');
Route::post('customers', 'CustomersController@store')->name('customers.store');
Route::get('customers/{customer}/edit', 'CustomersController@edit')->name('customers.edit');
Route::patch('customers/{customer}', 'CustomersController@update')->name('customers.update');
Route::get('customers/autocomplete', 'CustomersController@autocomplete')->name('customers.autocomplete');

// Sales Rep Routes
Route::get('sales', 'SalesController@index')->name('sales.index');
Route::get('sales/create', 'SalesController@create')->name('sales.create');
Route::post('sales', 'SalesController@store')->name('sales.store');
Route::get('sales/{rep}/edit', 'SalesController@edit')->name('sales.edit');
Route::put('sales/{rep}/restore', 'SalesController@restore')->name('sales.restore');
Route::put('sales/{rep}', 'SalesController@update')->name('sales.update');
Route::delete('sales/{rep}', 'SalesController@destroy')->name('sales.destroy');

// User Info Routes
Route::get('users/{user}/edit', 'UsersController@edit')->name('users.edit');

// Company Info Routes
Route::get('companies/{company}/edit', 'CompaniesController@edit')->name('companies.edit');
Route::put('companies/{company}', 'CompaniesController@update')->name('companies.update');

// Product Routes
Route::get('products', 'ProductsController@index')->name('products.index');
Route::get('products/create', 'ProductsController@create')->name('products.create');
Route::post('products', 'ProductsController@store')->name('products.store');
Route::get('products/{product}/edit', 'ProductsController@edit')->name('products.edit');
Route::put('products/{product}', 'ProductsController@update')->name('products.update');
Route::delete('products/{product}', 'ProductsController@destroy')->name('products.destroy');
Route::get('products/selections', 'ProductsController@selections')->name('products.selections');

// Shipping Options Routes
Route::get('shipping', 'ShippingController@index')->name('shipping.index');
Route::get('shipping/create', 'ShippingController@create')->name('shipping.create');
Route::post('shipping', 'ShippingController@store')->name('shipping.store');
Route::get('shipping/{opt}/edit', 'ShippingController@edit')->name('shipping.edit');
Route::put('shipping/{opt}', 'ShippingController@update')->name('shipping.update');
Route::delete('shipping/{opt}', 'ShippingController@destroy')->name('shipping.destroy');

// Payment Terms Routes
Route::get('terms', 'TermsController@index')->name('terms.index');
Route::get('terms/create', 'TermsController@create')->name('terms.create');
Route::post('terms', 'TermsController@store')->name('terms.store');
Route::get('terms/{term}/edit', 'TermsController@edit')->name('terms.edit');
Route::put('terms/{term}', 'TermsController@update')->name('terms.update');
Route::delete('terms/{term}', 'TermsController@destroy')->name('terms.destroy');

// Tax Options Routes
Route::get('taxes', 'TaxesController@index')->name('taxes.index');
