<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerFormRequest;
use Illuminate\Http\Request;
use Auth;
use App\Models\Customer;

class CustomersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request, Customer $customer)
    {
        // get params
        $keyword = isset($request->cust_name) ? $request->cust_name : null;
        $order = isset($request->order_by) ? intval($request->order_by) : 0;
        $pagesize = isset($request->page_size) ? intval($request->page_size) : 10;
        $customers = $customer->findByKeyword($keyword)
            ->withOrder($order)
            ->paginate($pagesize);
        return view('customers.index', compact('customers', 'keyword', 'order', 'pagesize'));
    }

    public function create(Customer $customer)
    {
        $provinces = canadian_provinces();
        return view('customers.edit', compact('customer', 'provinces'));
    }

    public function store(CustomerFormRequest $request, Customer $customer)
    {
        $customer->fill($request->all());
        $customer->save();
        return redirect()->to('/customers')->with('success', 'Customer created successfully');
    }

    public function edit(Customer $customer)
    {
        $provinces = canadian_provinces();
        return view('customers.edit', compact('customer', 'provinces'));
    }

    public function update(CustomerFormRequest $request, Customer $customer)
    {
        $customer->update($request->all());
        return redirect()->to('/customers')->with('success', 'Customer updated successfully');
    }

    public function autocomplete(Request $request, Customer $customer)
    {
        if($request->isXmlHttpRequest()){
            $keyword = isset($request->term) ? $request->term : '';
            $customers = $customer->findByKeyword($keyword)->get()->toArray();
            return response()->json($customers);
        }
        return response(null, 404);
    }
}
