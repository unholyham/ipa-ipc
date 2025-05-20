<?php

namespace App\Http\Controllers;

use App\Models\Proposal;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class ProposalController extends Controller
{
    public function index(){
        if (auth()->user()->role === 'admin') {
            // If admin, fetch all proposals
            $proposals = Proposal::all();
            return view('admin.index', ['proposals' => $proposals]);
        } else {
            // If not admin, fetch only the user's proposals
            $proposals = Proposal::where('ownerId', auth()->id())->get();
            return view('proposal.index', ['proposals' => $proposals]);
        }
    }

    public function create(){
        if (auth()->user()->role !== 'admin') {
            return view('proposal.create');
        }
        else {
            abort(403, 'Unauthorized.');
        }

    }

    public function store(Request $request){
        if (auth()->user()->role !== 'admin') {
            
        $validator = Validator::make($request->all(),[
            'projectTitle' => 'required|unique:proposals',
            'projectNumber' => 'nullable|unique:proposals',
            'region' => 'required',
            'preparedBy' => 'required',
            'mainContractor' => 'required',
            'reviewStatus' => 'nullable',
            'approvedStatus' => 'nullable',
            'pathToTP' => 'nullable|file|mimes:pdf,doc,docx|max:20480'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated(); // Get the validated data

        $data['id'] = Str::uuid();
        $data['ownerId'] = auth()->id();
        $data['reviewStatus'] = $data['reviewStatus'] ?? 'Not Started';
        $data['approvedStatus'] = $data['approvedStatus'] ?? 'Not Started';

        if ($request->hasFile('pathToTP')) {
            $file = $request->file('pathToTP');
            $extension = $file->getClientOriginalExtension();
            $projectTitle = $data['projectTitle'];
            $dateSubmitted = now()->format('Ymd');
            $filename = $dateSubmitted . '_' . $projectTitle . '.' . $extension;
            $path = $file->storeAs('public/Technical_Proposal_Uploads', $filename);
            $data['pathToTP'] = $path;
        }

        $newProposal = Proposal::create($data); // Use the $data array containing all validated fields

        return redirect(route('proposal.index'));
    }
    else {
        abort(403, 'Unauthorized.');
    }
}

    public function edit(Proposal $proposal){
        return view('proposal.edit', ['proposal' => $proposal]);
    }

    public function update(Proposal $proposal, Request $request){
        $data = $request->validate([
            'projectTitle' => 'required',
            'projectNumber' => 'nullable',
            'region' => 'required',
            'preparedBy' => 'required',
            'mainContractor' => 'required',
            'reviewStatus' => 'nullable',
            'approvedStatus' => 'nullable',
            'pathToTP' => 'nullable'
        ]);

        $data['reviewStatus'] = $data['reviewStatus'] ?? 'Not Started';
        $data['approvedStatus'] = $data['approvedStatus'] ?? 'Not Started';
        $proposal->update($data);
        return redirect(route('proposal.index'))->with('success', 'Proposal Update Successfully');
    }

    public function destroy(Proposal $proposal){
        if ($proposal->pathToTP) {
            Storage::delete($proposal->pathToTP);
        }
        $proposal->delete();
        return redirect(route('proposal.index'))->with('success', 'Proposal Deleted Successfully');
    }

    public function displayPdf(Proposal $proposal)
    {
        if (!$proposal->pathToTP) {
            abort(404, 'PDF not found');
        }

        $filePath = $proposal->pathToTP;
        $downloadName = basename($filePath);


       return Storage::download($filePath, $downloadName, [
        'Content-Type' => Storage::mimeType($filePath),
        ]);
    }
}
