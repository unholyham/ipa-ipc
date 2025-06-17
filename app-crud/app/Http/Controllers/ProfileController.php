<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function showProfilePage()
    {
        $account = Auth::user();
        return view('user.userprofile', compact('account'));
    }
    
}
