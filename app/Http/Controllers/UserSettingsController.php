<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserSettingsController extends Controller
{
    public function index()
    {
        return view('front.user.settings');
    }
    
    public function update(Request $request, User $user)
    {
        //

        return redirect()->route('user.settings')->with('success', 'Settings updated.');
    }
}
