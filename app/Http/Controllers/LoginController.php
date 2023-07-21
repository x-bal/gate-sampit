<?php

namespace App\Http\Controllers;

use App\Models\Gate;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    function index(Request $request)
    {
        $gates = Gate::get();

        return view('auth.login', compact('gates'));
    }

    function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string'
        ]);

        $user = User::where('username', $request->username)->first();

        if ($user) {
            if (Auth::attempt(['username' => $user->username, 'password' => $request->password])) {
                Session::put([
                    'id' => $user->id,
                    'username' => $user->username,
                    'name' => $user->name,
                ]);


                return redirect('/cards');
            } else {
                return back()->withErrors(['email' => 'Invalid credentials']); // Redirect back to login with error message.
            }
        }
    }

    function logout()
    {
        Auth::logout();
        Session::flush();

        return redirect('/');
    }
}
