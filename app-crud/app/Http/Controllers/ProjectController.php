<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    public function showProjectForm() 
    {
        if (!Auth::check() || !Auth::user()->role || Auth::user()->role->roleName === 'admin') {
            abort(403, 'Unauthorized. Admins cannot create projects.');
        }
        $companies = Company::all();
        return view('proposal.createproject', compact('companies'));
    }

    public function index() 
    {
        
        $userCompanyId = Auth::user()->companyID;
        $query = Project::with('subContractorCompany', 'mainContractorCompany');
        if (Auth::user()->role->roleName !== 'admin') {
            // If not admin, filter projects where the main contractor company matches the user's company ID
            $query->whereHas('mainContractorCompany', function ($subQuery) use ($userCompanyId) {
            $subQuery->where('companyID', $userCompanyId);
            });
        }
        $projects = $query->get();
        return view('proposal.viewallprojects', compact('projects'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'projectTitle' => ['required', 'string', 'max:255'],
            'projectNumber' => ['required', 'string', 'max:255'],
            'subContractor' => ['required', 'uuid', 'exists:companies,companyID'],
        ], [
            //Custom Error Message
            'projectTitle.required' => 'Please provide project title',
            'projectNumber.required' => 'Please provide project number',
            'subContractor.required' => 'Please select a sub contractor',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $data = $validator->validated();
        $data['mainContractor'] = Auth::user()->companyID;
        $project = Project::create($data);
        return redirect()->route('project.index')->with('success', 'Project created successfully.');
    }

    public function viewProject(Project $project) {
        $project->load('proposals', 'subContractorCompany', 'mainContractorCompany');
        return view('proposal.viewproject', ['project' => $project]);
        
    }
}
