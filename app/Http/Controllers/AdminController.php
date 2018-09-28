<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function schooldetails()
    {
    	
        return view('admin.schooldetails');
    }

    public function users()
    {
    	
        return view('admin.users');
    }
}
