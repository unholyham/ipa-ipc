<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller
{
    public function showCompanyForm()
    {
        return view('proposal.registercompany');
    }

    public function index() {
        $companies = Company::all();
        return view('proposal.viewallcompanies', ['companies' => $companies]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'companyName' => ['required', 'string', 'max:255'],
            'registrationNumber' => ['required'],
            'companyEmail' => ['required', 'string', 'email', 'max:255'],
            'companyAddress' => ['required', 'string'],
], [
    //Custom Error Message
    'companyName.required' => 'Please enter company name',
    'registrationNumber.required' => 'Please provide company registration number',
    'companyEmail.required' => 'Please provide company email address',
    'companyAddress.required' => 'Please provide company address',
]);

    if ($validator->fails()) {
        return back()->withErrors($validator)->withInput();
    }
        $data = $validator->validated();
        $data['contractorType'] = 'sub contractor';
        $company = Company::create($data);

        return redirect()->route('company.index')->with('success', 'Company added to Database');
    }

    public function viewCompany(Company $company) {
        return view('proposal.viewcompany', ['company' => $company]);
    }
}