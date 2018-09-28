<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Attendance;
use App\Student;
use App\Group;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
    	$curr_date = date('Y-m-d');

    	// $students = Student::pluck('NAME', 'USERID')->all();
        $groups = Group::orderBy('DEPTNAME')->pluck('DEPTNAME', 'DEPTID')->all();
    	
    	$student_name = $request->input('student_name');
        $group_id = $request->input('group_id');
    	$checktype = $request->input('checktype');
    	$first_date = $request->input('first_date');
    	$last_date = $request->input('last_date');

    	if ($request->isMethod('POST'))
    	{
	    	$attendances = Attendance::join('USERINFO', 'CHECKINOUT.USERID', '=', 'USERINFO.USERID')
	    					->select('CHECKINOUT.USERID', 'USERINFO.NAME', 'CHECKINOUT.CHECKTIME', 'CHECKINOUT.CHECKTYPE')
                            ->orderBy('CHECKINOUT.CHECKTIME', 'desc');
	    	
	    	if ($student_name != NULL)
	    	{
	    		$attendances = $attendances->where('USERINFO.NAME', 'like', '%'.$student_name.'%');
	    	}
            if ($group_id != NULL)
            {
                $attendances = $attendances->where('USERINFO.DEFAULTDEPTID', '=', $group_id);
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
            else
            {
                $attendances = $attendances->where(DB::raw('CONVERT(date, CHECKINOUT.CHECKTIME)'), '=', $curr_date);
            }

	    	$attendances = $attendances->get();				
	    }

	    else 
	    {
	    	$attendances = Attendance::join('USERINFO', 'CHECKINOUT.USERID', '=', 'USERINFO.USERID')
	    					->select('CHECKINOUT.USERID', 'USERINFO.NAME', 'CHECKINOUT.CHECKTIME', 'CHECKINOUT.CHECKTYPE')
                            ->orderBy('CHECKINOUT.CHECKTIME', 'desc')
	    					->where(DB::raw('CONVERT(date, CHECKINOUT.CHECKTIME)'), '=', $curr_date)
	    					->get();
	    }

        return view('attendance.index', ['attendances' => $attendances, 'groups' => $groups]);
    }

    public function sendcustommsg()
    {
        $sentmsgnum = Attendance::where('SentSMS', '=', '1')->count();
        return view('attendance.sendcustommsg', ['sentmsgnum' => $sentmsgnum]);
    }

    public function smsusage()
    {
    	$sentmsgnum = Attendance::where('SentSMS', '=', '1')->count();
        return view('reports.smsusage', ['sentmsgnum' => $sentmsgnum]);
    }

    public function groupattendance()
    {
        $sentmsgnum = Attendance::where('SentSMS', '=', '1')->count();
        // Attendance summary per day
        // Attendance::select(DB::raw('CONVERT(date, CHECKTIME) AS CHECKTIME'), DB::raw('count(*) as tot') )->where('SentSMS', '=',  '1' )->WHERE('CHECKTYPE', '=', 'I')->groupBy(DB::raw('CAST(CHECKTIME AS DATE)'))->get();
        return view('reports.groupattendance', ['sentmsgnum' => $sentmsgnum]);
    }
    
    public function studentattendance()
    {
        $sentmsgnum = Attendance::where('SentSMS', '=', '1')->count();
        return view('reports.studentattendance', ['sentmsgnum' => $sentmsgnum]);
    }
}
