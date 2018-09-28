<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Student;
use App\Group;
use App\Attendance;

class DashboardController extends Controller
{
    public function index()
    {
    	$curr_date = date('Y-m-d');
    	$num_students = Student::count();
    	$num_groups = Group::count();
    	$checkedin = Attendance::where(DB::raw('CONVERT(date, CHECKINOUT.CHECKTIME)'), '=', $curr_date)->where('CHECKTYPE', '=', 'I')->count();
    	$late = $num_students - $checkedin;

        return view('dashboard.index', ['num_students' => $num_students, 'num_groups' => $num_groups, 'checkedin' => $checkedin, 'late' => $late]);
    }
}
