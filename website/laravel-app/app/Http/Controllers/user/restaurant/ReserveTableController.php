<?php

namespace App\Http\Controllers\user\restaurant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReserveTableController extends Controller
{
    public function index()
    {
        return view('user.restaurant.reserveTable');
    }
}
