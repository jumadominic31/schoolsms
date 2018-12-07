<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Auth;
use Session;

class UsersController extends Controller
{
    
    public function index()
    {
        $users = User::paginate(10);
        return View('users.index')->with('users', $users);
    }

    public function getSignin()
    {
        return view('users.signin');
    }

    public function postSignin(Request $request)
    {
        $this->validate($request, [
            'username' => 'required',
            'password' => 'required'
        ]);

        $username = $request->input('username');

        //Check whether the user active
        $user_id = User::where('username', '=', $username)->pluck('id')->first();
        $user_status = User::where('id', '=', $user_id)->pluck('status')->first();

        //If user is inactive go back
        if ($user_status == '0')
        {
            return redirect()->back()->with('error', 'User Inactive');
        }

        $credentials = array('username' => $request->input('username'), 'password' => $request->input('password'));

        if (Auth::attempt($credentials)) {
            if (Session::has('oldUrl')) {
                $oldUrl = Session::get('oldUrl');
                Session::forget('oldUrl');
                return redirect()->to($oldUrl);
            }

            //Userdetails
            $userdetails = User::join('schools', 'users.school_id', '=', 'schools.id')->select('users.id', 'users.username','users.school_id', 'schools.name' )->where('users.username', '=', $username)->first();
            $schoolname = $userdetails->name;

            session(['schoolname' => $schoolname]);

            return redirect()->route('dashboard.index');
        }
        return redirect()->back()->with('error', 'Incorrect username/password');
    }

    public function getLogout() 
    {
        Auth::logout();
        return redirect()->route('users.signin');
    }

    public function getProfile() {
        $user = Auth::user();
        return view('users.profile', ['user' => $user]);
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $school_id = Auth::user()->school_id;
        $user_id = Auth::user()->id;

        $this->validate($request, [
            'username' => 'required|unique:users',
            'name' => 'required',
            'telephone' => array('required', 'regex:/^[0-9]{12}$/'),
            'status' => 'required' 
        ]);

        $password = 'sams123';

        $user = new User;
        $user->username = $request->input('username');
        $user->name = $request->input('name');
        $user->telephone = $request->input('telephone');
        $user->email = $request->input('email');
        $user->password = bcrypt($password);
        $user->school_id = $school_id;
        $user->status = $request->input('status');
        $user->updated_by = $user_id;
        $user->save();

        return redirect('/users')->with('success', 'User Created');
    }

    public function edit($id)
    {
        $school_id = Auth::user()->school_id;
        $user = User::where('school_id','=',$school_id)->find($id);
        if ($user == null){
            return redirect('/users')->with('error', 'User not found');
        }

        return view('users.edit', ['user'=> $user]);
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $user_id = $user->id;
        $school_id = Auth::user()->school_id;

        $this->validate($request, [
            'name' => 'required',
            'telephone' => ['required', 'regex:/^[0-9]{12}$/'],
            'status' => 'required'
        ]);
        
        
        $user = User::find($id);
        $user->name = $request->input('name');
        $user->telephone = $request->input('telephone');
        $user->email = $request->input('email');
        $user->status = $request->input('status');
        $user->school_id = $school_id;
        $user->updated_by = $user_id;
        $user->save();
        
        return redirect('/users')->with('success', 'User details updated');
    }
}
