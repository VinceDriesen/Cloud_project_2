<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ThemeController extends Controller
{
    public function changeTheme(Request $request) {
        $theme = session('theme') === 'light' ? 'dark' : 'light';
        session(['theme' => $theme]);

        return redirect()->back();
    }
}
