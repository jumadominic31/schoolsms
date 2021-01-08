<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Attendance;
use App\Student;
use App\Group;
use App\School;
use App\SmsApi;
use App\MsgTemplate;
use App\StatusReason;
use App\Customsms;
use Auth;
use Excel;
use PDF;
use App\Classes\AfricasTalkingGateway;
use App\Classes\AfricasTalkingGatewayException;

class AttendanceController extends Controller
{
    //this page shows all attendance. can be filtered. initial page shows today's checkin/out
    public function index(Request $request)
    {
    	$curr_date = date('Y-m-d');
        $groups = Group::orderBy('DEPTNAME')->pluck('DEPTNAME', 'DEPTID')->all();
        $sch_details = School::first();

    	$student_name = $request->input('student_name');
        $admno = $request->input('admno');
        $class = $request->input('class_name');
        $stream = $request->input('stream');
    	$checktype = $request->input('checktype');
    	$att_date = $request->input('att_date');

        $choices = ['class' => $class, 'stream' => $stream, 'checktype' => $checktype, 'att_date' => $att_date];

        $attendances = Attendance::rightjoin('USERINFO as U1', 'CHECKINOUT.USERID', '=', 'U1.USERID')->select('U1.USERID', 'U1.NAME', 'CHECKINOUT.CHECKTIME', 'CHECKINOUT.CHECKTYPE', 'U1.Admno', 'U1.Class', 'U1.Stream');

        // $attendances = Student::leftjoin('CHECKINOUT as C1', 'C1.USERID', '=', 'USERINFO.USERID')->select('USERINFO.USERID', 'USERINFO.NAME', 'C1.CHECKTIME', 'C1.CHECKTYPE', 'USERINFO.Admno', 'USERINFO.Class', 'USERINFO.Stream')->orderBy('C1.CHECKTIME', 'desc');

        if ($request->submitBtn ) {
            $this->validate($request, [
                'class_name' => 'required',
                'att_date' => 'required'
            ]);
        } 	
	    if ($att_date != NULL)
        {
            // $attendances = $attendances->where(function ($query) use ($att_date) { $query->where(DB::raw('CONVERT(date, C1.CHECKTIME)'), '=', $att_date)->orWhereNull('C1.USERID'); });
            // $attendances = $attendances->leftJoin('CHECKINOUT as C2', function($join) use ($att_date) { $join->on('C2.USERID', '=', 'U1.USERID')->where(DB::raw('CONVERT(date, C1.CHECKTIME)'), '=', $att_date); });
            $attendances = $attendances->where(DB::raw('CONVERT(date, CHECKINOUT.CHECKTIME)'), '=', $att_date);
        }
        else
        {
            // $attendances = $attendances->where(function ($query) use ($curr_date) { $query->where(DB::raw('CONVERT(date, C1.CHECKTIME)'), '=', $curr_date)->orWhereNull('C1.USERID'); });
            // $attendances = $attendances->leftJoin('CHECKINOUT as C2', function($join) use ($curr_date) { $join->on('C2.USERID', '=', 'U1.USERID')->where(DB::raw('CONVERT(date, C1.CHECKTIME)'), '=', $curr_date); });
            $attendances = $attendances->where(DB::raw('CONVERT(date, CHECKINOUT.CHECKTIME)'), '=', $att_date);
        }				
        if ($student_name != NULL)
        {
            $attendances = $attendances->where('U1.NAME', 'like', '%'.$student_name.'%');
        }
        if ($admno != NULL)
        {
            $attendances = $attendances->where('U1.Admno', '=', $admno);
        }
        if ($class != NULL)
        {
            $attendances = $attendances->where('U1.Class', '=', $class);
        }
        if ($stream != NULL)
        {
            $attendances = $attendances->where('U1.Stream', '=', $stream);
        }
        if ($checktype != NULL)
        {
            $attendances = $attendances->where('CHECKINOUT.CHECKTYPE', '=', $checktype);
        }
        // $attendances = $attendances->rightJoin('USERINFO as U2', function($join) { $join->on('U2.USERID', '=', 'CHECKINOUT.USERID'); });
        

        $attendances = $attendances->get()  ;	
        
        if ($request->submitBtn == 'DownloadRpt') {
            $pdf = PDF::loadView('pdf.attendance', ['attendances' => $attendances, 'groups' => $groups, 'sch_details' => $sch_details, 'curr_date' => $curr_date, 'choices' => $choices]);
            $pdf->setPaper('A4', 'portrait');
            return $pdf->stream('attendance_report.pdf');
        } 	

        return view('attendance.index', ['attendances' => $attendances, 'groups' => $groups]);
    }

    //get student name from admission no
    public function getstudentname($admno)
    {
        $studentname = Student::select('NAME')->where('Admno', '=', $admno)->pluck('NAME')->first();
        return response()->json($studentname);
    }

    //this page allows a portal user to send custom SMS e.g. when a student goes to hospital
    public function sendcustommsg()
    {
        $reasons = StatusReason::pluck('description', 'id')->all();
        $students = Student::pluck('NAME', 'USERID')->all();
        $msg = "Dear Parent, Your {gender} {name} Adm # {admno} will check {in-out} school today. Reason: {reason} \nThank you";

        return view('attendance.sendcustommsg', ['reasons' => $reasons, 'students' => $students, 'msg' => $msg]);
    }

    //this function post the custom msg
    public function postcustommsg(Request $request)
    {
        $user = Auth::user();
        $user_id = $user->id;
        $apidetails = SmsApi::where('school_id', '=', '1')->first();
        $atgusername   = $apidetails->atgusername;
        $atgapikey     = $apidetails->atgapikey;

        $this->validate($request, [
            'status' => 'required',
            'reason' => 'required',
            'indv_grp' => 'required'          
        ]);

        $curr_datetime = date('Y-m-d h:m:s'); 

        $status = $request->input('status');
        $reason = $request->input('reason');
        $student_name = $request->input('student_name');
        $admno = $request->input('admno');
        $message = $request->input('msg');
        $class_name = $request->input('class_name');
        $indv_grp = $request->input('indv_grp');

        if ($indv_grp == 0) //individual //get student name, gender, admno, guardian phone
        {
            $student = Student::select('USERINFO.USERID', 'USERINFO.OPHONE', 'USERINFO.NAME', 'USERINFO.GENDER', 'USERINFO.Admno')
                        ->where('USERINFO.Admno', '=', $admno)
                        ->get()->toArray();
        }
        else if ($indv_grp == 1) //group
        {
            $student = Student::select('USERINFO.USERID', 'USERINFO.OPHONE', 'USERINFO.NAME', 'USERINFO.GENDER', 'USERINFO.Admno')
                        ->where('USERINFO.Class', '=', $class_name)
                        ->get()->toArray();
        }

        if ( empty($student) )
        {
            return redirect()->back()->with('error', 'Input a valid admission number/class or no students in class chosen');
        }

        $count = count($student); 

        if ($count > 0)
        {
            foreach ($student as $item)
            {
                //get reason
                $reason_det = StatusReason::where('id', '=', $reason)->pluck('description', 'id')->first();

                $phone = $item['OPHONE'];

                // if ($phone == '')
                // {
                //     return redirect('/attendance/sendcustommsg')->with('error', 'No phone listed');
                // }
                
                $checktype = $status == '0' ? 'out of' : 'in to';
                $name = $item['NAME'];
                $checktime = $curr_datetime;
                $gender = $item['GENDER'] == '0' ? 'son' : 'daughter';
                $admno = $item['Admno'];

                $variables = array('name' => $name, 'clockdatetime' => $checktime, 'gender' => $gender, 'admno' => $admno, 'reason' => $reason_det, 'in-out' => $checktype);

                foreach ($variables as $key => $value){
                    $message = str_replace('{'.$key.'}', $value, $message);
                }
                
                $recipients = "+". $phone;

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

                    $customsms = new Customsms;
                    $customsms->student_id = $item['USERID'];
                    $customsms->message = $message;
                    $customsms->checktype = $status;
                    $customsms->updated_by = $user_id;
                    $customsms->save();
                  
                }
                catch ( AfricasTalkingGatewayException $e )
                {
                  echo "Encountered an error while sending: ".$e->getMessage();
                }
                //reinitialize message
                $message = $request->input('msg');
            }
        }

        return redirect('/attendance/sendcustommsg')->with('success', 'Message Sent');
    }

    //this function checks and sends an SMS if required for new checkin/out
    public function sendmsg()
    {
        //update latecomers table 
        


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
                $gender = $item['GENDER'] == '0' ? 'son' : 'daughter';
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
                
                $recipients = "+". $phone;

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

    public function schedsendmsg()
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
                
                $recipients = "+". $phone;

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
            return response()->json($count.' SMS sent successfully');
        }

        else 
        {
            return response()->json('No SMS to be sent');
        }
    }

    //this function will check for latecomers at a certain time and then populate the latecomers table
    public function latecomers()
    {
        $latecomers = Student::select('USERINFO.USERID', 'USERINFO.OPHONE', 'USERINFO.NAME', 'USERINFO.GENDER', 'USERINFO.Admno')
                        ->where('USERINFO.checkedin', '=', '0')
                        ->get()->toArray();

    }
}
