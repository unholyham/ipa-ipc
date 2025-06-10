<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Mail\AccountApprovedMail;
use App\Mail\AccountRejectedMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller
{
    public function index() {
        $users = User::all();
        return view('admin.viewallusers', ['users' => $users]);
    }

    public function viewUser(User $user) {
        return view('admin.viewuser', ['user' => $user]);
    }

    public function updateVerificationStatus(Request $request, User $user) {
        // Ensure only admins can perform this action
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized.');
        }

        // Define base validation rules
        $rules = [
            'verificationStatus' => 'required|in:Approved,Rejected',
        ];

        // Conditionally add 'remarks' validation rule ONLY if status is 'Rejected'
        if ($request->input('verificationStatus') === 'Rejected') {
            $rules['remarks'] = 'required|string|max:1000'; // Added max length for remarks
        }

        // Validate the request based on the defined rules
        $request->validate($rules);

        // Retrieve the new verification status and remarks from the request
        $newVerificationStatus = $request->input('verificationStatus');
        // If 'remarks' is not provided (e.g., for 'Approved' status), default to an empty string
        $remarks = $request->input('remarks', '');

        $newAccountStatus = $user->accountStatus;

        if ($newVerificationStatus === 'Approved') {
            $newAccountStatus = 'Active';
        } else {
            $newAccountStatus = 'Inactive';
        }
        // Update the user's verification status and remarks in the database
        $user->update([
            'verificationStatus' => $newVerificationStatus, // Use the variable for clarity
            'remarks' => $remarks,
            'accountStatus' => $newAccountStatus,
        ]);

        // Send email based on the new verification status
        try {
            if ($newVerificationStatus === 'Approved') {
                // Send Approval Email to applicant
                Mail::to($user->email)->send(new AccountApprovedMail($user));
                Log::info('Account approved email sent to: ' . $user->email);
                //Send Verification Success Email to MainContractor
                //Mail::to(config('mail.from.address'))->send(new AccountApprovedMail($user));
    
            } elseif ($newVerificationStatus === 'Rejected') {
                // Send Rejection Email to applicant, including the remarks
                Mail::to($user->email)->send(new AccountRejectedMail($user, $remarks));
                Log::info('Account rejected email sent to: ' . $user->email . ' with remarks: ' . $remarks);
                //Send Verification Rejected Email to MainContractor
                //Code Here
            }   //Mail::to(config('mail.from.address'))->send(new AccountRejectedMail($user));
            
        } catch (\Exception $e) {
            // Log any errors that occur during email sending
            Log::error('Failed to send account verification email to ' . $user->email . ': ' . $e->getMessage());
        }

        // Redirect back with a success message
        return back()->with('success', 'Verification status updated successfully.');
    }

    public function updateAccountStatus(Request $request, User $user) {
       // Ensure only admins can perform this action
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized.');
        }
        
        if ($user->verificationStatus !== 'Approved') {
            return back()->with('error', 'Account status can only be toggled for approved accounts');
        } 

        $newStatus = ($user->accountStatus === 'Active') ? 'Inactive' : 'Active';
        $user->update(['accountStatus' => $newStatus]);

        return back()->with('success', 'User account status updated to ' . $newStatus . '.');
    }
}