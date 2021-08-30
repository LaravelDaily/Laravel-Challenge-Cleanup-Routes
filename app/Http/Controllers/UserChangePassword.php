<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserChangePassword extends Controller
{
    public function update(Request $request, User $user)
    {
        //

        return redirect()->route('user.settings')->with('success', 'Password updated.');
    }
}
