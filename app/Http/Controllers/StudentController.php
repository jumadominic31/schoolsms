<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Student;
use Excel;

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
	    	$students = Student::select('Admno', 'NAME', 'GENDER', 'ParentName', 'Class', 'Stream')->orderBy('Admno', 'asc');
	    	
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
	    	$students = Student::select('Admno', 'NAME', 'GENDER', 'ParentName', 'Class', 'Stream')->get();
	    	
	    }

        return view('students.index', ['students' => $students]);
    }
}
