<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class EmailStyleController extends Controller
{
    public function show()
    {
        $views = glob(resource_path('views/emails/*/**'));

        $views = array_map(function ($view) {
            $parts = explode('/', $view);
            $filename = str_replace('.blade.php', '', end($parts));
            $folder = prev($parts);
            return "$folder.$filename";
        }, $views);

        $user = User::firstWhere('role', 'user');

        $firstname = $user->firstname;
        $lastname = $user->lastname;
        $email = $user->email;
        $token = "123456789";

        return view('emails.auth.register-confirmation', compact('firstname', 'lastname', 'email', 'token'));
    }
}
