<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Company;
use App\Models\Project;
use App\Models\Proposal;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\ProposalApproved;
use App\Mail\ProposalRejected;
//use App\Imports\ProposalImport; 
use App\Mail\ProposalUnderReview;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Notifications\ProposalApprovedNotification;
use App\Notifications\ProposalRejectedNotification;
use App\Notifications\ProposalUnderReviewNotification;
use App\Notifications\NewProposalSubmittedNotification;

class ProposalController extends Controller
{
    public function index(){
        if (Auth::user()->role->roleName === 'admin') {
            // If admin, fetch all proposals
            $proposals = Proposal::with(['owner', 'getProject', 'company'])->get();
            return view('proposal.index', ['proposals' => $proposals]);
        } else {
            // If not admin, fetch only the user's proposals
            $proposals = Proposal::with(['owner', 'getProject', 'company'])->where('ownerID', Auth::id())->get();
            return view('proposal.index', ['proposals' => $proposals]);
        }
    }

    public function showProposalForm(){
        $companies = Company::all();
        $projects = Project::all();
        return view('proposal.create', 
        [
            'companies' => $companies,
            'projects' => $projects,
        ]);
                    
    }

    public function store(Request $request){
        if (Auth::user()->role->roleName !== 'admin') {
            
        $validator = Validator::make($request->all(),[
            'project' => ['required', 'uuid', 'exists:projects,projectID'],
            'region' => ['required'],
            'preparedBy' => ['required'],
            'mainContractor' => ['required', 'uuid', 'exists:companies,companyID'],
            'reviewStatus' => ['nullable'],
            'approvedStatus' => ['nullable'],
            'remarks' => ['nullable'],
            'pathToTP' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:61440'],
            'pathToJMS' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:61440'],
        ], [
            'project.required' => 'Please select the project',
            'region.required' => 'Please select a region',
            'preparedBy.required' => 'Please provide the name of the preparer',
            'mainContractor.required' => 'Please select the main contractor',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated(); // Get the validated data

        $data['ownerID'] = Auth::id();
        $data['reviewStatus'] = $data['reviewStatus'] ?? 'Not Started';
        $data['approvedStatus'] = $data['approvedStatus'] ?? 'Not Started';
        $data['remarks'] = $data['remarks'] ?? null;
        $data['pathToTP'] = $data['pathToTP'] ?? null;
        $data['pathToJMS'] = $data['pathToJMS'] ?? null;

        $project = Project::find($data['project']);
        $projectTitle = $project->projectTitle;
        if ($request->hasFile('pathToTP')) {
            $file = $request->file('pathToTP');
            $extension = $file->getClientOriginalExtension();
            $dateSubmitted = now()->format('Ymd');
            $filename = $dateSubmitted . '_' . 'TP'. '_' . $projectTitle . '.' . $extension;
            $path = $file->storeAs('public/Technical_Proposal_Uploads', $filename);
            $data['pathToTP'] = $path;
        }

        if ($request->hasFile('pathToJMS')) {
            $file = $request->file('pathToJMS');
            $extension = $file->getClientOriginalExtension();
            $dateSubmitted = now()->format('Ymd');
            $filename = $dateSubmitted . '_' . 'JMS'. '_' . $projectTitle . '.' . $extension;
            $path = $file->storeAs('public/Joint_Measurement_Sheet_Uploads', $filename);
            $data['pathToJMS'] = $path;
        }

        $newProposal = Proposal::create($data); 

        $admins = Account::whereHas('role', function ($query) {
                $query->where('roleName', 'admin');
            })->get();

        foreach ($admins as $admin) {
            $admin->notify(new NewProposalSubmittedNotification($newProposal));
        }
        return redirect()->route('proposal.index')->with('success', 'Technical proposal submitted successfully!');
    }
    else {
        abort(403, 'Unauthorized.');
    }
}

    public function view(Proposal $proposal){
        $proposal->load(['owner', 'getProject', 'company']);
        return view('proposal.view', ['proposal' => $proposal]);
    }

    public function displayTP(Proposal $proposal)
    {
        if (!$proposal->pathToTP) {
            abort(404, 'PDF not found');
        }

        $filePath = $proposal->pathToTP;
        $fileName = basename($filePath);


       return Storage::response($filePath, $fileName, [
        'Content-Type' => Storage::mimeType($filePath),
        'Content-Disposition' => 'inline; filename="' . $fileName . '"',
        ]);
    }

    public function displayJMS(Proposal $proposal)
    {
        if (!$proposal->pathToJMS) {
            abort(404, 'PDF not found');
        }

        $filePath = $proposal->pathToJMS;
        $fileName = basename($filePath);


       return Storage::response($filePath, $fileName, [
        'Content-Type' => Storage::mimeType($filePath),
        'Content-Disposition' => 'inline; filename="' . $fileName . '"',
        ]);
    }

    public function downloadTP(Proposal $proposal)
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

    public function downloadJMS(Proposal $proposal)
    {
        if (!$proposal->pathToJMS) {
            abort(404, 'PDF not found');
        }

        $filePath = $proposal->pathToJMS;
        $downloadName = basename($filePath);


       return Storage::download($filePath, $downloadName, [
        'Content-Type' => Storage::mimeType($filePath),
        ]);
    }

    public function updateReviewStatus(Request $request, Proposal $proposal)
    {
        if (Auth::user()->role->roleName !== 'admin') {
            abort(403, 'Unauthorized.');
        }

        $request->validate([
            'reviewStatus' => 'required|in:Under Review',
        ]);

        $proposal->update(['reviewStatus' => $request->input('reviewStatus')]);
        $owner = $proposal->owner;

        if ($owner && $owner->email) {
            try {
                Mail::to($owner->email)->send(new ProposalUnderReview($proposal));
                Log::info('Under Review email sent for proposal: ' . $proposal->id . ' to ' . $owner->email);
            } catch (\Exception $e) {
                Log::error('Failed to send review email for proposal: ' . $proposal->id . '. Error: ' . $e->getMessage());
            }
            //Dispatch database notification
            $owner->notify(new ProposalUnderReviewNotification($proposal));
        } else {
            Log::warning('Could not send review email for proposal: ' . $proposal->id . '. Owner or owner email not found.');
        }
        return redirect()->route('proposal.view', ['proposal' => $proposal->id])->with('success', 'Review status updated successfully!');
    }

    public function updateApprovedStatus(Request $request, Proposal $proposal)
    {
        if (Auth::user()->role->roleName !== 'admin') {
            abort(403, 'Unauthorized.');
        }

        $rules = [
            'approvedStatus' => 'required|in:Approved,Rejected',
        ];

        if ($request->input('approvedStatus') === 'Rejected') {
            $rules['remarks'] = 'required|string|max:1000';
        }

        $request->validate($rules);

        $newApprovedStatus = $request->input('approvedStatus');
        $remarks = $request->input('remarks', '');
        $successMessage = '';

        $proposal->update([
            'approvedStatus' => $newApprovedStatus,
            'remarks' => $remarks,
            'reviewStatus' => 'Reviewed'
        ]);
        
        $owner = $proposal->owner;

        if ($owner && $owner->email) {
            try {
                if ($newApprovedStatus === 'Approved') {
                    Mail::to($owner->email)->send(new ProposalApproved($proposal));
                    $owner->notify(new ProposalApprovedNotification($proposal));
                    Log::info('Approval email sent for proposal: ' . $proposal->id . ' to ' . $owner->email);
                    $successMessage = 'Proposal approved successfully!';
                } elseif ($newApprovedStatus === 'Rejected') {
                    Mail::to($owner->email)->send(new ProposalRejected($proposal));
                    $owner->notify(new ProposalRejectedNotification($proposal));
                    Log::info('Rejection email sent for proposal: ' . $proposal->id . ' to ' . $owner->email);
                    $successMessage = 'Proposal rejected successfully!';
                }
                
            } catch (\Exception $e) {
                Log::error('Failed to send status update email for proposal: ' . $proposal->id . '. Error: ' . $e->getMessage());
                $successMessage = 'Proposal status updated, but email notification failed.';
            }
        } else {
            Log::warning('Could not send status update email for proposal: ' . $proposal->id . '. Owner or owner email not found.');
            $successMessage = 'Proposal status updated, but user notification could not be sent (owner email missing).';
        }
        return redirect()->route('proposal.index')->with('success', 'Approval status updated successfully!');
    }

}

