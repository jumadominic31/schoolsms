<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Attendance;
use App\Student;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
    	$curr_date = date('Y-m-d');

    	$students = Student::pluck('NAME', 'USERID')->all();
    	
    	$student_id = $request->input('student_id');
    	$checktype = $request->input('checktype');
    	$first_date = $request->input('first_date');
    	$last_date = $request->input('last_date');

    	if ($request->isMethod('POST'))
    	{
	    	$attendances = Attendance::join('USERINFO', 'CHECKINOUT.USERID', '=', 'USERINFO.USERID')
	    					->select('CHECKINOUT.USERID', 'USERINFO.NAME', 'CHECKINOUT.CHECKTIME', 'CHECKINOUT.CHECKTYPE');
	    	
	    	if ($student_id != NULL)
	    	{
	    		$attendances = $attendances->where('CHECKINOUT.USERID', '=', $student_id);
	    	}
	    	if ($checktype != NULL)
	    	{
	    		$attendances = $attendances->where('CHECKINOUT.CHECKTYPE', '=', $checktype);
	    	}
	    	if ($first_date != NULL)
	    	{
                if ($last_date != NULL)
                {
                    $attendances = $attendances->where(DB::raw('CONVERT(date, CHECKINOUT.CHECKTIME)'), '<=', $last_date)->where(DB::raw('CONVERT(date, CHECKINOUT.CHECKTIME)'),'>=',$first_date);
                } 
                else
                {
                    $attendances = $attendances->where(DB::raw('CONVERT(date, CHECKINOUT.CHECKTIME)'), '=', $first_date);
                }
            }

	    	$attendances = $attendances->get();				
	    }

	    else 
	    {
	    	$attendances = Attendance::join('USERINFO', 'CHECKINOUT.USERID', '=', 'USERINFO.USERID')
	    					->select('CHECKINOUT.USERID', 'USERINFO.NAME', 'CHECKINOUT.CHECKTIME', 'CHECKINOUT.CHECKTYPE')
	    					->where(DB::raw('CONVERT(date, CHECKINOUT.CHECKTIME)'), '=', $curr_date)
	    					->get();
	    }

        return view('attendance.index', ['attendances' => $attendances, 'students' => $students]);
    }

    public function messages()
    {
    	$sentmsgnum = Attendance::where('SentSMS', '=', '1')->count();
        return view('messages.index', ['sentmsgnum' => $sentmsgnum]);
    }
}
