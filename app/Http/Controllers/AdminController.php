<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\School;
use App\SmsApi;
use App\MsgTemplate;
use Auth;

class AdminController extends Controller
{
    public function schooldetails()
    {
    	$schooldetails = School::where('id', '=', '1')->first();
        return view('school.index', ['schooldetails' => $schooldetails]);
    }

    public function editschool()
    {
        $school_id = Auth::user()->school_id;
        $school = School::where('id', '=', 1)->first();
        return view('school.edit', ['school'=> $school]);
    }

    public function updateschool(Request $request)
    {
        $user = Auth::user();
        $user_id = $user->id;

        $this->validate($request, [
            'address' => 'required',
            'town' => 'required',
            'boarding' => 'required',
            'gender' => 'required',
            'telephone' => ['required', 'regex:/^[0-9]{12}$/']
        ]);
        
        $email = $request->input('email');
        $school = School::find('1');
        $school->address = $request->input('address');
        $school->town = $request->input('town');
        $school->boarding = $request->input('boarding');
        $school->gender = $request->input('gender');
        $school->telephone = $request->input('telephone');
        if ($email != NULL)
        {
            $school->email = $email;
        }
        $school->updated_by = $user_id;
        $school->save();
        
        return redirect('/school')->with('success', 'School details updated');
    }

    public function users()
    {
    	
        return view('admin.users');
    }

    public function msgsetup()
    {
        $msgtemplate = MsgTemplate::where('school_id', '=', '1')->first();
        return view('admin.msgsetup', ['msgtemplate' => $msgtemplate]);
    }

    public function updatemsg(Request $request)
    {
        $this->validate($request, [
            'clockinmsg' => 'required',
            'clockoutmsg' => 'required',
            'negclockinmsg' => 'required'
        ]);

        $msgtemplate = MsgTemplate::find('1');
        $msgtemplate->school_id = '1';
        $msgtemplate->clockinmsg = $request->input('clockinmsg');
        $msgtemplate->clockoutmsg = $request->input('clockoutmsg');
        $msgtemplate->negclockinmsg = $request->input('negclockinmsg');
        $msgtemplate->save();

        return redirect('/admin/msgsetup')->with('success', 'Message Templates Updated Successfully');
    }

    public function smsengsetup()
    {
        // $user = Auth::user();
        // $user_id = $user->id;
        // $school_id = $user->school_id;
        // $apidetails = SmsApi::where('school_id', '=', $school_id)->all();
        $school_name = School::select('name')->where('id', '=', '1')->pluck('name')->first();
        $apidetails = SmsApi::where('school_id', '=', '1')->first();
        return view('admin.smsengsetup', ['apidetails' => $apidetails, 'school_name' => $school_name]);
    }

    public function updatesmseng(Request $request)
    {
        $this->validate($request, [
            'atgusername' => 'required',
            'atgapikey' => 'required',
            'atgsender_id' => 'required'
        ]);

        // $apidetails = SmsApi::find($id);
        $apidetails = SmsApi::find('1');
        // $school_id = $id;
        $apidetails->school_id = '1';
        $apidetails->atgusername = $request->input('atgusername');
        $apidetails->atgapikey = $request->input('atgapikey');
        $apidetails->atgsender_id = $request->input('atgsender_id');
        $apidetails->save();

        return redirect('/admin/smsengsetup')->with('success', 'Sms Engine Updated Successfully');
    }
}
