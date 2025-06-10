<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Mail\NewAccountRegistrationMail;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{

    public function showRegistrationForm()
    {
        return view('user.registeruser');
    }
    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed',
            'password_confirmation' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $data = $validator->validated();
        
        $data['id'] = Str::uuid();
        $data['password'] = Hash::make($request->password);
        $data['role'] = 'user';
        $data['verificationStatus'] = 'Pending';
        $data['remarks'] = null;
        $data['accountStatus'] = 'Inactive';

        $user = User::create($data);

        //Send New User Registration email to self
        Mail::to(config('mail.from.address'))->send(new NewAccountRegistrationMail($user));
        return redirect()->route('login')->with('success', 'Account registered successfully! Our team is verifying your application. An email will be sent once your account has been approved.');
    }
}
