<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\User;
use Auth;
use Session;

class UsersController extends Controller
{
    
    public function index()
    {
        $users = User::where('id', '!=', '1')->paginate(10);
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

    public function resetpass(){
        return view('users.resetpass');
    }

    public function postResetpass(Request $request) {
        $this->validate($request, [
            'curr_password' => 'required',
            'new_password_1' => 'required|same:new_password_1',
            'new_password_2' => 'required|same:new_password_1'
        ]);

        $current_password = Auth::User()->password;

        if(Hash::check($request->input('curr_password'), $current_password)){
            $request->user()->fill([
                'password' => Hash::make($request->input('new_password_1'))
            ])->save();
            return redirect('/users/profile')->with('success', 'Password Changed');
        } 

        else {
            return redirect('/users/resetpass')->with('error', 'Current password incorrect');
        }

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
            'pass1' => 'required|same:pass1',
            'pass2' => 'required|same:pass1',
            'telephone' => array('required', 'regex:/^[0-9]{12}$/'),
            'status' => 'required' 
        ]);

        $password = $request->input('pass1');
        $email = $request->input('email');

        $user = new User;
        $user->username = $request->input('username');
        $user->name = $request->input('name');
        $user->telephone = $request->input('telephone');
        if ($email != NULL)
        {
            $user->email = $email;
        }
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
        $user = User::where('school_id','=',$school_id)->where('id', '!=', '1')->find($id);
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
            'pass1' => 'same:pass1',
            'pass2' => 'same:pass1',
            'telephone' => ['required', 'regex:/^[0-9]{12}$/'],
            'status' => 'required'
        ]);
        
        $password = $request->input('pass1');
        $email = $request->input('email');
        
        $user = User::find($id);
        $user->name = $request->input('name');
        if ($password != NULL)
        {
            $user->password = bcrypt($password);
        }
        $user->telephone = $request->input('telephone');
        if ($email != NULL)
        {
            $user->email = $email;
        }
        $user->status = $request->input('status');
        $user->school_id = $school_id;
        $user->updated_by = $user_id;
        $user->save();
        
        return redirect('/users')->with('success', 'User details updated');
    }
}
