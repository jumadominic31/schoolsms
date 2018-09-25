<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Checkinout;

class TestController extends Controller
{
    public function index()
    {
    	$checkinouts = Checkinout::all();
        return view('test', ['checkinouts' => $checkinouts]);
    }
}
