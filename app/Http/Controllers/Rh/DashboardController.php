<?php

namespace App\Http\Controllers\Rh;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        return view('rh.dashboard');
    }
}