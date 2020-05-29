<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\Company;
use App\Http\Requests\CompanyFormRequest;
use App\Handlers\ImageUploadHandler;

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

    public function update(CompanyFormRequest $request, Company $company, ImageUploadHandler $uploadHandler)
    {
        $companyData = $request->all();
        if($request->logo){
            $uploadResult = $uploadHandler->save($request->logo, 'company_logos/' . $company->id, [
                'company_id' => $company->id
            ]);
            if($uploadResult){
                $companyData['logo'] = $uploadResult['path'];
                if($oldCompanyLogo = $company->logo){
                    unlink(public_path() . $oldCompanyLogo); // remove old company logo image file
                }
            }else{
                return redirect()->route('companies.edit', $company->id)
                    ->with('danger', 'Error: Unable to upload company logo')->withInput();
            }
        }
        $company->update($companyData);
        return redirect()->to(route('companies.edit', $company->id))
            ->with('success', 'Company info updated successfully');
    }
}
