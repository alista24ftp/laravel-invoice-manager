<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\Shipping;
use Illuminate\Support\Facades\Validator;

class ShippingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Shipping $opt)
    {
        $options = $opt->paginate(10);
        return view('shipping.index', compact('options'));
    }

    public function create(Shipping $opt)
    {
        return view('shipping.create_or_edit', compact('opt'));
    }

    public function store(Request $request, Shipping $opt)
    {
        // First way to validate input
        Validator::make($request->all(), [
            'option' => ['required', 'unique:shipping', 'min:3', 'max:30']
        ], [
            'option.required' => 'Shipping option cannot be empty',
            'option.unique' => 'Shipping option name already exists',
            'option.min' => 'Shipping option name must be 3 characters or longer',
            'option.max' => 'Shipping option name must be 30 characters or less'
        ])->validate();

        $opt->fill($request->all());
        $opt->save();
        return redirect()->to(route('shipping.index'))
            ->with('success', 'Shipping option added successfully');
    }

    public function edit(Shipping $opt)
    {
        return view('shipping.create_or_edit', compact('opt'));
    }

    public function update(Request $request, Shipping $opt)
    {
        // Second way to validate input
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'option' => 'required|unique:shipping|min:3|max:30'
        ], [
            'id.required' => 'Shipping option ID cannot be empty',
            'option.unique' => 'Shipping option name already exists',
            'option.required' => 'Shipping option name cannot be empty',
            'option.min' => 'Shipping option name must be 3 characters or longer',
            'option.max' => 'Shipping option name must be 30 characters or less'
        ]);
        if($validator->fails()){
            return redirect()->to(route('shipping.edit', $opt->id))->withErrors($validator)->withInput();
        }

        $opt->update($request->all());
        return redirect()->to(route('shipping.index'))
            ->with('success', 'Shipping option updated successfully');
    }

    public function destroy(Shipping $opt)
    {
        $opt->delete();
        return redirect()->to(route('shipping.index'))
            ->with('success', 'Shipping option deleted successfully');
    }
}
