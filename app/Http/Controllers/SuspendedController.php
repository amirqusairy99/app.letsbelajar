<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SuspendedController extends Controller
{
    public function show(Request $request)
    {
        return view('account.suspended');
    }
}
