<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke()
    {
        if (auth()->user()) {
            return redirect(route('home'));
        }

        return view('login-or-register');
    }
}
