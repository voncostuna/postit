<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

class ActivityLogController extends Controller
{
    public function index()
    {
        return view('user.activity-logs.index');
    }
}
