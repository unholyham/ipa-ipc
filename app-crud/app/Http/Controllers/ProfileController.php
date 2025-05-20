<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function showProfilePage()
    {
        $user = Auth::user();
        return view('user.userprofile', compact('user'));
    }
    
    public function edit()
    {
        $user = Auth::user();
        return view('user.editprofile', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->update($validatedData);

        return redirect()->route('profile.view')->with('success', 'Profile updated successfully!');
    }
}
