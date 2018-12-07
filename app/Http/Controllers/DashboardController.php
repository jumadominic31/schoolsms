<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Student;
use App\Group;
use App\Attendance;
use App\SmsApi;

use AfricasTalking\SDK\AfricasTalking;

class DashboardController extends Controller
{
    public function index()
    {
    	$curr_date = date('Y-m-d');
    	$apidetails = SmsApi::where('school_id', '=', '1')->first();
    	$atgusername   = $apidetails->atgusername;
        $atgapikey     = $apidetails->atgapikey;

    	$num_students = Student::count();
    	$num_groups = Group::count();
    	$checkedin = Attendance::where(DB::raw('CONVERT(date, CHECKINOUT.CHECKTIME)'), '=', $curr_date)->where('CHECKTYPE', '=', 'I')->count();
    	$late = $num_students - $checkedin;

    	// Initialize the SDK
		$AT          = new AfricasTalking($atgusername, $atgapikey);

		// Get the application service
		$application = $AT->application();

		try {
		    // Fetch the application data
		    $data = $application->fetchApplication();
		    // $bal =  print_r($data);
		    $bal = $data['data']->UserData->balance;
		} 
		catch(Exception $e) {
		    echo "Error: ".$e->getMessage();
		}

        return view('dashboard.index', ['num_students' => $num_students, 'num_groups' => $num_groups, 'checkedin' => $checkedin, 'late' => $late, 'bal' => $bal]);
    }
}
