<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\Company;

class CompaniesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function edit(Company $company)
    {
        return view('companies.edit', compact('company'));
    }

    public function update(CompanyFormRequest $request, Company $company)
    {
        $company->update($request->all());
        return redirect()->to(route('companies.edit'))->with('success', 'Company info updated successfully');
    }
}
