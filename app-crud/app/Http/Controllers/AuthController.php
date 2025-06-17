<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        //Check if a user has logged in
        if (Auth::check()) {
            return redirect()->route('proposal.index');//Redirect to home page
        }
        return view('user.loginuser');//Otherwise just display login page
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->has('remember'))) {

            $user = Auth::user();
            if ($user->verificationStatus === 'verified') {
                if ($user->accountStatus === 'active') {
                    $request->session()->regenerate();
                    return redirect()->route('proposal.index');
                } else {
                    Auth::logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();
                    return back()->withErrors(['email' => 'Your account is currently inactive. Please contact support.'])->withInput();
                }
            } else {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return back()->withErrors(['email' => 'Your account is not verified. Please check your email or contact support.'])->withInput();
            }
        }
        return back()->withErrors(['email' => 'Invalid login credentials'])->withInput();
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
