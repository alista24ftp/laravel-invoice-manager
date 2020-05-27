<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\Tax;
use Illuminate\Support\Facades\Validator;

class TaxesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $taxes = Tax::all();
        return view('taxes.index', compact('taxes'));
    }

    public function edit(Tax $tax)
    {
        return view('taxes.edit', compact('tax'));
    }

    public function update(Request $request, Tax $tax)
    {
        Validator::make($request->all(), [
            'id' => 'required|exists:taxes',
            'description' => 'required|min:3|max:25',
            'rate' => 'nullable|numeric|min:0.000|max:99.999'
        ], [
            'id.required' => 'Tax ID cannot be empty',
            'id.exists' => 'Tax ID must already exist',
            'description.required' => 'Tax description cannot be empty',
            'description.min' => 'Tax description must be 3 characters or longer',
            'description.max' => 'Tax description must be 25 characters or less',
            'rate.numeric' => 'Tax rate must be a number',
            'rate.min' => 'Tax rate must be 0% or more',
            'rate.max' => 'Tax rate must be 99.999% or less'
        ])->validate();

        $tax->description = $request->description;
        $tax->rate = $request->rate;
        $tax->save();
        return redirect()->to(route('taxes.index'))->with('success', 'Tax info updated successfully');
    }
}
