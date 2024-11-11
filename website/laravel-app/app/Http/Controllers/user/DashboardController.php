<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
//        $user = Auth::user();
//        $groups = Groups::whereHas('groupmembers', function($query) use ($user) {
//            $query->where('user_id', $user->id);
//        })->get();

        return view('/user/dashboard');
    }
}
