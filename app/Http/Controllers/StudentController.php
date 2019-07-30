<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use App\Student;
use Excel;
use Auth;

class StudentController extends Controller
{
    public function index(Request $request)
    {
    	$admno = $request->input('admno');
    	$student_name = $request->input('student_name');
    	$parent_name = $request->input('parent_name');
    	$gender = $request->input('gender');
    	$class = $request->input('class');
    	$stream = $request->input('stream');

    	if ($request->isMethod('POST'))
    	{
	    	$students = Student::select('Admno', 'NAME', DB::raw("(CASE WHEN (GENDER = 0) THEN 'Male' ELSE 'Female' END) as GENDER"), 'OPHONE', 'ParentName', 'Class', 'Stream')->orderBy('Admno', 'asc');
	    	
	    	if ($admno != NULL)
	    	{
	    		$students = $students->where('Admno', 'like', '%'.$admno.'%');
	    	}
	    	if ($student_name != NULL)
	    	{
	    		$students = $students->where('NAME', 'like', '%'.$student_name.'%');
	    	}
	    	if ($parent_name != NULL)
	    	{
	    		$students = $students->where('ParentName', 'like', '%'.$parent_name.'%');
	    	}
	    	if ($gender != NULL)
	    	{
	    		$students = $students->where('GENDER', 'like', '%'.$gender.'%');
	    	}
	    	if ($class != NULL)
	    	{
	    		$students = $students->where('Class', 'like', '%'.$class.'%');
	    	}
	    	if ($stream != NULL)
	    	{
	    		$students = $students->where('Stream', 'like', '%'.$stream.'%');
	    	}
            
            $students = $students->get();

            if ($request->submitBtn == 'Export_XLS') {
	            Excel::create('students', function($excel) use($students) {
					$excel->sheet('Sheet 1', function($sheet) use($students) {
						$sheet->fromArray($students);
					});
				})->export('xls');
            }
   				
	    }

	    else 
	    {
	    	$students = Student::select('Admno', 'NAME', DB::raw("(CASE WHEN (GENDER = 0) THEN 'Male' ELSE 'Female' END) as GENDER"), 'OPHONE', 'ParentName', 'Class', 'Stream')->get();
	    	
	    }

        return view('students.index', ['students' => $students]);
    }

    public function edit($id)
    {
        $student = Student::where('Admno', '=', $id)->first();
        return view('students.edit',['student'=> $student]);
    }

    public function update(Request $request, $id)
    {
        $student = Student::where('admno', '=', $id)->first();
          $this->validate($request, [
        	'admno' => 'required',
        	'name' => 'required',
        	'gender' => 'required',
        	'parent_name' => 'required',
        	'phone' => array('required', 'regex:/^[0-9]{12}$/'),
        	'class_name' => 'required',
        	'stream' => 'required'
        ]);
        
        $user_id = Auth::user()->id;

        $student = Student::where('admno', '=', $id)->first();
        $student->Admno = $request->input('admno');
        $student->NAME = $request->input('name');
        $student->GENDER = $request->input('gender');
        $student->ParentName = $request->input('parent_name');
        $student->OPHONE = $request->input('phone');
        $student->Class = $request->input('class_name');
        $student->Stream = $request->input('stream');
        $student->save();

        return redirect('/students')->with('success', 'Student details updated');
       
    }

    public function studentsImport(Request $request)
    {
        $this->validate($request, [
            'students' => 'mimes:xls,xlsx,csv' 
        ]);

        // students already in the database
        $curr_students = Student::select('Admno')->pluck('Admno')->toArray();
        
        if($request->hasFile('students')){
            $path = $request->file('students')->getRealPath();
            $data = Excel::load($path)->get();
            if($data->count()){

                foreach ($data as $key => $value) {
                    // Skip name previously added using in_array
                    if (in_array($value->admno, $curr_students))
                        continue;
                    $value->gender = strtolower($value->gender) == 'male' ? '0' : '1' ;
                    $students_list[] = ['Admno' => $value->admno, 'BADGENUMBER' => $value->admno, 'NAME' => $value->name, 'GENDER' => $value->gender, 'ParentName' => $value->parentname, 'OPHONE' => $value->ophone, 'Class' => $value->class, 'Stream' => $value->stream];
                }
                if(!empty($students_list)){
                    try {
                        Student::insert($students_list);
                        \Session::flash('success', 'File imported successfully.');
                    }
                    catch (\Illuminate\Database\QueryException $ex){ 
                        // dd($ex->getMessage()); 
                        \Session::flash('error', $ex->getMessage());
                    // Note any method of class PDOException can be called on $ex.
                    }

                }
            }
        }
        else{
            \Session::flash('error','There is no file to import');
        }
        return Redirect::back();
    }

    public function create()
    {
        return view('students.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'admno' => 'required|unique:USERINFO',
            'name' => 'required',
            'gender' => 'required',
            'parent_name' => 'required',
            'phone' => array('required', 'regex:/^[0-9]{12}$/'),
            'class_name' => 'required',
            'stream' => 'required'
        ]);

        $student = new Student;
        $student->Admno = $request->input('admno');
        $student->BADGENUMBER = $request->input('admno');
        $student->NAME = $request->input('name');
        $student->GENDER = $request->input('gender');
        $student->ParentName = $request->input('parent_name');
        $student->OPHONE = $request->input('phone');
        $student->Class = $request->input('class_name');
        $student->Stream = $request->input('stream');
        $student->save();

        return redirect('/students')->with('success', 'Student added');
    }
}
