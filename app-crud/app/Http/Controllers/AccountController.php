<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Role;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Notifications\PendingAccountRegistrationNotification;
class AccountController extends Controller
{
    public function showRegistrationForm()
    {
        $companies = Company::all();
        return view('user.registeruser', compact('companies'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employeeName'        => ['required', 'string', 'max:255'],
            'companyID'           => ['required', 'uuid', 'exists:companies,companyID'],
            'designation'         => ['required', 'string'],
            'contactNumber'       => ['required', 'string', 'max:20'],
            'email'               => ['required', 'string', 'email', 'max:255', 'unique:accounts,email'],
            'password'            => ['required', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['required'],
    ], [
            //Custom Error Message
            'employeeName.required' => 'Please enter your name',
            'companyID.required' => 'Please select a company.',
            'designation.required' => 'Please select a designation',
            'contactNumber.required' => 'Please enter your contact number',
            'email.required' => 'Please enter your email address',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $data = $validator->validated();

        $data['verificationStatus'] = 'pending';
        $data['verificationRejectRemarks'] = null;
        $data['accountStatus'] = 'inactive';
        $defaultRole = Role::where('roleName', 'user')->firstOrFail();
        $data['roleID'] = $defaultRole->roleID;
        
        $account = Account::create($data);

        $admins = Account::whereHas('role', function ($query) {
                $query->where('roleName', 'admin');
            })->get();

        foreach ($admins as $admin) {
            $admin->notify(new PendingAccountRegistrationNotification($account));
        }
        return redirect()->route('login')->with('success', 'Account registered successfully! Our team is verifying your application. An email will be sent once your account has been approved.');
    }
}
