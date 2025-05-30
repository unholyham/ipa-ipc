<?php

namespace App\Http\Controllers;

use App\Models\Proposal;
use App\Models\User;
use App\Mail\ProposalUnderReview;
use App\Mail\ProposalApproved;
use App\Mail\ProposalRejected;
use App\Notifications\ProposalUnderReviewNotification;
use App\Notifications\ProposalApprovedNotification;
use App\Notifications\ProposalRejectedNotification;
use App\Notifications\NewProposalSubmittedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ProposalController extends Controller
{
    public function index(){
        if (auth()->user()->role === 'admin') {
            // If admin, fetch all proposals
            $proposals = Proposal::all();
            return view('proposal.index', ['proposals' => $proposals]);
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
            'remarks' => 'nullable',
            'pathToTP' => 'nullable|file|mimes:pdf,doc,docx|max:61440',
            'pathToJMS' => 'nullable|file|mimes:pdf,doc,docx|max:61440',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated(); // Get the validated data

        $data['id'] = Str::uuid();
        $data['ownerId'] = auth()->id();
        $data['reviewStatus'] = $data['reviewStatus'] ?? 'Not Started';
        $data['approvedStatus'] = $data['approvedStatus'] ?? 'Not Started';
        $data['remarks'] = $data['remarks'] ?? null;
        $data['pathToTP'] = $data['pathToTP'] ?? null;
        $data['pathToJMS'] = $data['pathToJMS'] ?? null;

        if ($request->hasFile('pathToTP')) {
            $file = $request->file('pathToTP');
            $extension = $file->getClientOriginalExtension();
            $projectTitle = $data['projectTitle'];
            $dateSubmitted = now()->format('Ymd');
            $filename = $dateSubmitted . '_' . 'TP'. '_' . $projectTitle . '.' . $extension;
            $path = $file->storeAs('public/Technical_Proposal_Uploads', $filename);
            $data['pathToTP'] = $path;
        }

        if ($request->hasFile('pathToJMS')) {
            $file = $request->file('pathToJMS');
            $extension = $file->getClientOriginalExtension();
            $projectTitle = $data['projectTitle'];
            $dateSubmitted = now()->format('Ymd');
            $filename = $dateSubmitted . '_' . 'JMS'. '_' . $projectTitle . '.' . $extension;
            $path = $file->storeAs('public/Joint_Measurement_Sheet_Uploads', $filename);
            $data['pathToJMS'] = $path;
        }

        $newProposal = Proposal::create($data); // Use the $data array containing all validated fields

        $admins = User::where('role', 'admin')->get();

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
        return view('proposal.view', ['proposal' => $proposal]);
    }

    // public function update(Proposal $proposal, Request $request){
    //     $data = $request->validate([
    //         'projectTitle' => 'required',
    //         'projectNumber' => 'nullable',
    //         'region' => 'required',
    //         'preparedBy' => 'required',
    //         'mainContractor' => 'required',
    //         'reviewStatus' => 'required',
    //         'approvedStatus' => 'nullable',
    //         'pathToTP' => 'nullable'
    //     ]);

    //     $data['reviewStatus'] = $data['reviewStatus'] ?? 'Not Started';
    //     $data['approvedStatus'] = $data['approvedStatus'] ?? 'Not Started';
    //     $proposal->update($data);
    //     return redirect(route('proposal.index'))->with('success', 'Proposal Updated Successfully');
    // }

    // public function destroy(Proposal $proposal){
    //     if ($proposal->pathToTP) {
    //         Storage::delete($proposal->pathToTP);
    //     }
    //     $proposal->delete();
    //     return redirect(route('proposal.index'))->with('success', 'Proposal Deleted Successfully');
    // }

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
        if (auth()->user()->role !== 'admin') {
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
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized.');
        }

        $rules = [
            'approvedStatus' => 'required|in:Approved,Rejected',
        ];

        if ($request->input('approvedStatuss') === 'Rejected') {
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

