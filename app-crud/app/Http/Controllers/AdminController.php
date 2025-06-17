<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Mail\AccountApprovedMail;
use App\Mail\AccountRejectedMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller
{
    public function index() {
        $accounts = Account::with(['role', 'company'])->get();
        return view('admin.viewallusers', ['accounts' => $accounts]);
    }

    public function viewUser(Account $account) {
        $account->load(['role', 'company']);
        return view('admin.viewuser', ['account' => $account]);
    }

    public function updateVerificationStatus(Request $request, Account $account) {
        // Ensure only admins can perform this action
        if (!Auth::check() || !Auth::user()->role || Auth::user()->role->roleName !== 'admin') {
            abort(403, 'Unauthorized. Only Admins can perform this action.');
        }

        // Define base validation rules
        $rules = [
            'verificationStatus' => 'required|in:verified,rejected',
        ];

        // Conditionally add 'remarks' validation rule ONLY if status is 'Rejected'
        if ($request->input('verificationStatus') === 'rejected') {
            $rules['verificationRejectRemarks'] = 'required|string|max:1000'; // Added max length for remarks
        }

        // Validate the request based on the defined rules
        $request->validate($rules);

        // Retrieve the new verification status and remarks from the request
        $newVerificationStatus = $request->input('verificationStatus');
        // If 'remarks' is not provided (e.g., for 'Approved' status), default to an empty string
        $verificationRejectRemarks = $request->input('verificationRejectRemarks', null);

        $newAccountStatus = $account->accountStatus;

        if ($newVerificationStatus === 'verified') {
            $newAccountStatus = 'active';
        } else {
            $newAccountStatus = 'inactive';
        }
        // Update the user's verification status and remarks in the database
        $account->update([
            'verificationStatus' => $newVerificationStatus, // Use the variable for clarity
            'verificationRejectRemarks' => $verificationRejectRemarks,
            'accountStatus' => $newAccountStatus,
        ]);

        // Send email based on the new verification status
        try {
            if ($newVerificationStatus === 'verified') {
                // Send Approval Email to applicant
                Mail::to($account->email)->send(new AccountApprovedMail($account));
                Log::info('Account verified email sent to: ' . $account->email);
    
            } elseif ($newVerificationStatus === 'rejected') {
                // Send Rejection Email to applicant, including the remarks
                Mail::to($account->email)->send(new AccountRejectedMail($account, $verificationRejectRemarks));
                Log::info('Account rejected email sent to: ' . $account->email . ' with remarks: ' . $verificationRejectRemarks);

            }   
            
        } catch (\Exception $e) {
            // Log any errors that occur during email sending
            Log::error('Failed to send account verification email to ' . $account->email . ': ' . $e->getMessage());
        }

        // Redirect back with a success message
        return back()->with('success', 'Verification status updated successfully.');
    }

    public function updateAccountStatus(Request $request, Account $account) {
       // Ensure only admins can perform this action
        if (!Auth::check() || !Auth::user()->role || Auth::user()->role->roleName !== 'admin') {
            abort(403, 'Unauthorized. Only Administrators can perform this action.');
        }
        
        if ($account->verificationStatus !== 'verified') {
            return back()->with('error', 'Account status can only be toggled for approved accounts');
        } 

        $newStatus = ($account->accountStatus === 'active') ? 'inactive' : 'active';
        $account->update(['accountStatus' => $newStatus]);

        return back()->with('success', 'User account status updated to ' . $newStatus . '.');
    }
}