<?php

namespace App\Http\Controllers\SchoolHead;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        return view('school_head.dashboard'); // adjust to your actual view
    }
}
