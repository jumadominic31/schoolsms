<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Attendance;
use App\Student;
use App\Group;
use App\SmsApi;
use App\MsgTemplate;
use App\StatusReason;

class AttendanceController extends Controller
{
    //this page shows all attendance. can be filtered. initial page shows today's checkin/out
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

    //this page allows a portal user to send custom SMS e.g. when a student goes to hospital
    public function sendcustommsg()
    {
        $reasons = StatusReason::pluck('description', 'id')->all();
        $students = Student::pluck('NAME', 'USERID')->all();
        return view('attendance.sendcustommsg', ['reasons' => $reasons, 'students' => $students]);
    }

    //this function post the custom msg
    public function postcustommsg(Request $request)
    {
        $this->validate($request, [
            'status' => 'required',
            'reason' => 'required',
            'student_id' => 'required'          
        ]);

        $curr_datetime = date('Y-m-d h:m:s'); 

        $status = $request->input('status');
        $reason = $request->input('reason');
        $student_id = $request->input('student_id');

        $checkinout = new Attendance;
        $checkinout->USERID = $student_id;
        $checkinout->CHECKTIME = $curr_datetime;
        $checkinout->CHECKTYPE = $status == '0' ? 'O' : 'I';
        $checkinout->VERIFYCODE = 1;
        $checkinout->SENSORID = 104;
        $checkinout->Memoinfo = $reason;
        $checkinout->WorkCode = 0;
        $checkinout->sn = '';
        $checkinout->UserExtFmt = 0;
        $checkinout->SentSMS = 0;
        $checkinout->updated_at = $curr_datetime;
        $checkinout->save();

        return redirect('/attendance/sendcustommsg')->with('success', 'Message Sent' . $checkinout->id);
    }

    //this page shows the SMS usage and balance
    public function smsusage()
    {
    	$sentmsgnum = Attendance::where('SentSMS', '=', '1')->count();
        return view('reports.smsusage', ['sentmsgnum' => $sentmsgnum]);
    }

    //this page display a report for the attendance filtered by group (e.g. all form 4 students) and period
    public function groupattendance()
    {
        $sentmsgnum = Attendance::where('SentSMS', '=', '1')->count();
        // Attendance summary per day
        // Attendance::select(DB::raw('CONVERT(date, CHECKTIME) AS CHECKTIME'), DB::raw('count(*) as tot') )->where('SentSMS', '=',  '1' )->WHERE('CHECKTYPE', '=', 'I')->groupBy(DB::raw('CAST(CHECKTIME AS DATE)'))->get();
        return view('reports.groupattendance', ['sentmsgnum' => $sentmsgnum]);
    }
    
    //this page display a report for the attendance filtered by individual student and period
    public function studentattendance()
    {
        $sentmsgnum = Attendance::where('SentSMS', '=', '1')->count();
        return view('reports.studentattendance', ['sentmsgnum' => $sentmsgnum]);
    }

    //this function checks and sends an SMS if required for new checkin/out
    public function sendmsg()
    {
        //ATG username, apikey, sender_id
        $apidetails = SmsApi::where('school_id', '=', '1')->first();
        $msgtemplate = MsgTemplate::where('school_id', '=', '1')->first();

        $tosendsms = Attendance::join('USERINFO', 'CHECKINOUT.USERID', '=', 'USERINFO.USERID')
                            ->select('CHECKINOUT.ID', 'CHECKINOUT.USERID', 'CHECKINOUT.CHECKTIME', 'CHECKINOUT.CHECKTYPE', 'USERINFO.OPHONE', 'USERINFO.NAME', 'USERINFO.GENDER', 'USERINFO.Admno')
                            ->where('CHECKINOUT.SentSMS', '!=', '1')
                            ->get();
        $count = $tosendsms->count();        
    
        if ($count > 0)
        {
            $atgusername   = $apidetails->atgusername;
            $atgapikey     = $apidetails->atgapikey;
            // $atgsender_id  = $apidetails->atgsender_id;

            foreach ($tosendsms as $item)
            {
                $id = $item['ID'];
                $phone = $item['OPHONE'];
                $checktype = $item['CHECKTYPE'];
                $name = $item['NAME'];
                $checktime = $item['CHECKTIME'];
                $gender = $item['GENDER'] == 'Male' ? 'son' : 'daughter';
                $admno = $item['Admno'];
                $variables = array('name' => $name, 'clockdatetime' => $checktime, 'gender' => $gender, 'admno' => $admno);

                if ($checktype == "I")
                {
                    $type = "checked in at";
                    $message = $msgtemplate->clockinmsg;
                    foreach($variables as $key => $value){
                        $message = str_replace('{'.$key.'}', $value, $message);
                    }
                }
                else if ($checktype == "O")
                {
                    $type = "checked out from";
                    $message = $msgtemplate->clockoutmsg;
                    foreach ($variables as $key => $value){
                        $message = str_replace('{'.$key.'}', $value, $message);
                    }
                }
                
                $recipients = "+254". $phone;

                $from = $apidetails->atgsender_id;

                $gateway    = new AfricasTalkingGateway($atgusername, $atgapikey);
                
                try 
                { 
                  $results = $gateway->sendMessage($recipients, $message, $from);
                            
                  foreach($results as $result) {

                    echo " Number: " .$result->number;
                    echo " Status: " .$result->status;
                    echo " StatusCode: " .$result->statusCode;
                    echo " MessageId: " .$result->messageId;
                    echo " Cost: "   .$result->cost."\n";
                  } 
                  
                }
                catch ( AfricasTalkingGatewayException $e )
                {
                  echo "Encountered an error while sending: ".$e->getMessage();
                }
                
                $checkinout = Attendance::find($id);
                $checkinout->SentSMS = '1';
                $checkinout->save();

            }
            return redirect('/attendance/sendcustommsg')->with('success', $count.' SMS sent successfully');
        }

        else 
        {
            return redirect('/attendance/sendcustommsg')->with('error', 'No SMS to be sent');
        }
    }
}
