<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DoctorLoginController extends Controller
{
    public function index()
    {
        return view('auth.doctorLogin');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if ($user->doctor) {
                return redirect()->intended('dashboard');
            } else {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Je hebt geen toegang als dokter.',
                ]);
            }
        }

        return back()->withErrors([
            'email' => 'De opgegeven inloggegevens zijn onjuist.',
        ]);
    }

}
