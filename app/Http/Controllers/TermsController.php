<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\PaymentTerm;
use Illuminate\Support\Facades\Validator;

class TermsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $terms = PaymentTerm::paginate(10);
        return view('terms.index', compact('terms'));
    }

    public function create(PaymentTerm $term)
    {
        return view('terms.create_or_edit', compact('term'));
    }

    public function store(Request $request, PaymentTerm $term)
    {
        Validator::make($request->all(), [
            'option' => 'required|max:60',
            'period' => 'nullable|integer'
        ], [
            'option.required' => 'Payment terms name cannot be empty',
            'option.max' => 'Payment terms name must be 60 characters or less',
            'period.integer' => 'Payment terms period must be an integer'
        ])->validate();

        $term->fill($request->all());
        $term->save();
        return redirect()->to(route('terms.index'))->with('success', 'Payment term added successfully');
    }

    public function edit(PaymentTerm $term)
    {
        return view('terms.create_or_edit', compact('term'));
    }

    public function update(Request $request, PaymentTerm $term)
    {
        Validator::make($request->all(), [
            'option' => 'required|max:60',
            'period' => 'nullable|integer'
        ], [
            'option.required' => 'Payment terms name cannot be empty',
            'option.max' => 'Payment terms name must be 60 characters or less',
            'period.integer' => 'Payment terms period must be an integer'
        ])->validate();

        $term->update($request->all());
        return redirect()->to(route('terms.index'))->with('success', 'Payment term updated successfully');
    }

    public function destroy(PaymentTerm $term)
    {
        $term->delete();
        return redirect()->to(route('terms.index'))->with('success', 'Payment term deleted successfully');
    }
}
