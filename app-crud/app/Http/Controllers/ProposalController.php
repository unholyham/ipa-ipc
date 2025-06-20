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
use App\Notifications\ProposalCheckApprovedNotification;
use App\Notifications\ProposalCheckRejectedNotification;

class ProposalController extends Controller
{
    public function index(){
        $userCompanyId = Auth::user()->companyID;
        if (Auth::user()->role->roleName === 'admin') {
            // If admin, fetch all proposals
            $proposals = Proposal::with(['owner', 'getProject.subContractorCompany', 'getProject.mainContractorCompany'])->get();
            return view('proposal.index', ['proposals' => $proposals]);
        } else {
            // If not admin, fetch only the user's proposals
            $proposals = Proposal::with(['owner', 'getProject.subContractorCompany', 'getProject.mainContractorCompany'])->whereHas('getProject.mainContractorCompany', function ($query) use ($userCompanyId) {
                $query->where('companyID', $userCompanyId);
            })
            ->get();
            return view('proposal.index', ['proposals' => $proposals]);
        }
    }

    public function showProposalForm(){
        if (Auth::user()->designation === 'Contract Executive') {
            $companies = Company::all();
            $projects = Project::all();
            return view('proposal.create', 
            [
                'companies' => $companies,
                'projects' => $projects,
            ]);     
        } else {
            abort(403, 'Unauthorized.');
        }    
    }

    public function store(Request $request){
        if (Auth::user()->designation === 'Contract Executive') {
            
        $validator = Validator::make($request->all(),[
            'project' => ['required', 'uuid', 'exists:projects,projectID'],
            'region' => ['required'],
            'preparedBy' => ['required'],
            'reviewStatus' => ['nullable'],
            'checkedStatus' => ['nullable'],
            'approvedStatus' => ['nullable'],
            'remarks' => ['nullable'],
            'pathToTP' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:61440'],
            'pathToJMS' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:61440'],
        ], [
            'project.required' => 'Please select the project',
            'region.required' => 'Please select a region',
            'preparedBy.required' => 'Please provide the name of the preparer',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated(); // Get the validated data

        $data['ownerID'] = Auth::id();
        $data['reviewStatus'] = $data['reviewStatus'] ?? 'Not Started';
        $data['checkedStatus'] = $data['checkedStatus'] ?? 'Not Started';
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
        $proposal->load(['owner', 'getProject.subContractorCompany', 'getProject.mainContractorCompany']);
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

    public function updateReviewStatus(Request $request, Proposal $proposal)//Contract Executive will handle this
    {
        if (Auth::user()->designation === 'Assistant Contract Manager') {
            $request->validate([
                'reviewStatus' => 'required|in:Under Review',
            ]);

            $proposal->update(['reviewStatus' => $request->input('reviewStatus')]);
            $owner = $proposal->owner;

            if ($owner && $owner->email) {
                try {
                    //Mail::to($owner->email)->send(new ProposalUnderReview($proposal));
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
        } else {
            abort(403, 'Unauthorized');
        }
    }

    public function updateCheckedStatus(Request $request, Proposal $proposal)//Assistant Contract Manager will handle this
    {
        if (Auth::user()->designation === 'Assistant Contract Manager') {
            $rules = [
                'checkedStatus' => 'required|in:Approved,Rejected',
            ];

            $newCheckedStatus = $request->input('checkedStatus');
            if ($newCheckedStatus === 'Rejected') {
                $rules['remarks'] = 'required|string|max:1000';
            }

            $request->validate($rules);

            $remarks = null;
            if ($newCheckedStatus === 'Rejected') {
                $remarks = $request->input('remarks');
            }

            $successMessage = '';

            $proposal->update([
                'checkedStatus' => $newCheckedStatus,
                'remarks' => $remarks,
                'reviewStatus' => 'Reviewed'
            ]);

            $owner = $proposal->owner;

            if ($owner && $owner->email) {
                try {
                    if ($newCheckedStatus === 'Approved') {
                        //Mail::to($owner->email)->send(new ProposalCheckApprovedMail($proposal));
                        $owner->notify(new ProposalCheckApprovedNotification($proposal));
                        Log::info('Check approved email sent for proposal: ' . $proposal->id . ' to ' . $owner->email);
                        $successMessage = 'Proposal approved successfully!';
                    } elseif ($newCheckedStatus === 'Rejected') {
                        //Mail::to($owner->email)->send(new ProposalCheckRejectedMail($proposal));
                        $owner->notify(new ProposalCheckRejectedNotification($proposal));
                        Log::info('Check rejected email sent for proposal: ' . $proposal->id . ' to ' . $owner->email);
                        $successMessage = 'Proposal rejected successfully!';
                    }
                
                } catch (\Exception $e) {
                    Log::error('Failed to send checked status update email for proposal: ' . $proposal->id . '. Error: ' . $e->getMessage());
                    $successMessage = 'Proposal checked status updated, but email notification failed.';
                }
            } else {
                Log::warning('Could not send checked status update email for proposal: ' . $proposal->id . '. Owner or owner email not found.');
                $successMessage = 'Proposal checked status updated, but user notification could not be sent (owner email missing).';
            }
            return redirect()->route('proposal.view', ['proposal' => $proposal->id])->with('success', 'Approval checked status updated successfully!');
        } else {
            abort(403, 'Unauthorized');
        }
    }

    public function updateApprovedStatus(Request $request, Proposal $proposal)//Contract Manager will handle this
    {
        if (Auth::user()->designation === 'Contract Manager') {
            $rules = [
                'approvedStatus' => 'required|in:Approved,Rejected',
            ];

            $newApprovedStatus = $request->input('approvedStatus');
            if ($newApprovedStatus === 'Rejected') {
                $rules['remarks'] = 'required|string|max:1000';
            }

            $request->validate($rules);

            $remarks = null;
            if ($newApprovedStatus === 'Rejected') {
                $remarks = $request->input('remarks');
            }
            $successMessage = '';

            $proposal->update([
                'approvedStatus' => $newApprovedStatus,
                'remarks' => $remarks
            ]);
        
            $owner = $proposal->owner;

            if ($owner && $owner->email) {
                try {
                    if ($newApprovedStatus === 'Approved') {
                        //Mail::to($owner->email)->send(new ProposalApproved($proposal));
                        $owner->notify(new ProposalApprovedNotification($proposal));
                        Log::info('Approval email sent for proposal: ' . $proposal->id . ' to ' . $owner->email);
                        $successMessage = 'Proposal approved successfully!';
                    } elseif ($newApprovedStatus === 'Rejected') {
                        //Mail::to($owner->email)->send(new ProposalRejected($proposal));
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
            return redirect()->route('proposal.view', ['proposal' => $proposal->id])->with('success', 'Approval status updated successfully!');
        } else {
            abort(403, 'Unauthorized');
        }
    }

}

