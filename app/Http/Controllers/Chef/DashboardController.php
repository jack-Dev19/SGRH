<?php

namespace App\Http\Controllers\Chef;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        return view('chef.dashboard');
    }
}