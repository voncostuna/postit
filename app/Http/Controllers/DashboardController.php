<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function user() {
        return view('user.dashboard');
    }

    public function admin() {
        return view('admin.dashboard');
    }
}
