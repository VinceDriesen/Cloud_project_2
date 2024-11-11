<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class SignoutController extends Controller
{

    use AuthenticatesUsers;

    public function index()
    {
        return view('auth.signout');
    }

    public function signout()
    {
        $this->guard()->logout();

        return redirect('/');
    }
}
