@extends('layouts.app')

@section('content')

<h1>Students</h1>
<div class="row">
    <div class="col-md-2">
        <a href="{{ route('students.index') }}" class="btn btn-success" style="margin-right: 15px;">Reset</a>
    </div>
    <div class="col-md-2">
        <a href="{{ route('student.create') }}" class="btn btn-success" style="margin-right: 15px;">Add 1 Student</a>
    </div>
</div>
<br>
{!! Form::open(['action' => 'StudentController@studentsImport', 'method'=>'POST', 'files'=>'true']) !!}
<div class="row">
   <div class="col-xs-10 col-sm-10 col-md-10">
        <div class="form-group">
            {!! Form::label('students_file','Select File to Import:',['class'=>'col-md-3']) !!}
            <div class="col-md-9">
            {!! Form::file('students', ['class' => 'form-control', 'accept' => '.xls,.xlsx,.csv']) !!}
            </div>
        </div>
    </div>
    <div class="col-xs-2 col-sm-2 col-md-2 text-center">
    {!! Form::submit('Add Students from file',['class'=>'btn btn-success']) !!}
    </div>
</div>
{!! Form::close() !!}

<br>
<h4>Filter Options: </h4>

<input type="checkbox" autocomplete="off" onchange="checkfilter(this.checked);" />

<div id="filteroptions" style="display: none ;">
    {!! Form::open(['action' => 'StudentController@index', 'method' => 'GET']) !!}
    <table class="table" width="100%" table-layout="fixed">
        <tbody>
            <tr>
                <td width="33.3%">
                    <div class="form-group">
                        {{Form::label('admno', 'Admission No')}}
                        {{Form::text('admno', '', ['class' => 'form-control'])}}
                    </div>
                </td>
                <td width="33.3%">
                    <div class="form-group">
                        {{Form::label('student_name', 'Student Name')}}
                        {{Form::text('student_name', '', ['class' => 'form-control'])}}
                    </div>
                </td>
                <td width="33.3%">
                    <div class="form-group">
                        {{Form::label('parent_name', 'Parent Name')}}
                        {{Form::text('parent_name', '', ['class' => 'form-control'])}}
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="form-group">
                        {{Form::label('gender', 'Gender')}}
                        {{Form::select('gender', ['' => '', '0' => 'Male', '1' => 'Female'], '', ['class' => 'form-control'])}}
                    </div>
                </td>
                <td>
                    <div class="form-group">
                        {{Form::label('class_name', 'Form')}}
                        {{Form::select('class_name', ['' => '', '1' => '1', '2' => '2', '3' => '3', '4' => '4'], '', ['class' => 'form-control'])}}
                    </div>
                </td>
                <td>
                    <div class="form-group">
                        {{Form::label('stream', 'Stream')}}
                        {{Form::text('stream', '', ['class' => 'form-control'])}}
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="form-group">
                        {{Form::label('boarder', 'Boarder')}}
                        {{Form::select('boarder', [ '' => '','1' => 'Y', '0' => 'N'], '', ['class' => 'form-control'])}}
                    </div>
                </td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>

    {{Form::submit('Submit', ['class'=>'btn btn-primary', 'name' => 'submitBtn'])}}  
    {{Form::submit('Export_XLS', ['class'=>'btn btn-primary', 'name' => 'submitBtn', 'formtarget' => '_blank'])}}
</div>
@if(count($students) > 0)
<?php
    $colcount = count($students);
    $i = 1;
?>
<table class="table table-striped" >
    <tr>
        <th>Adm No</th>
        <th>Student Name</th>
        <th>Gender</th>
        <th>Parent Name</th>
        <th>Phone</th>
        <th>Form</th>
        <th>Stream</th>
        <th>Boarder</th>
        <th></th>
    </tr>
    @foreach($students as $student)
    <tr>
        <td>{{$student['Admno']}}</td>
        <td>{{$student['NAME']}}</td>
        <td>{{$student['GENDER']}}</td>
        <td>{{$student['ParentName']}}</td>
        <td>{{$student['OPHONE']}}</td>
        <td>{{$student['Class']}}</td>
        <td>{{$student['Stream']}}</td>
        <td>{{$student['boarder']}}</td>
        <td><span class="center-block"><a class="pull-right btn btn-default" href="{{ route('student.edit', ['student' => $student->Admno ]) }}">Edit</a></span></td>
    </tr>
    @endforeach
</table>
{{ $students->appends(request()->input())->links() }}
@else
    <p>No Students To Display</p>
@endif

@endsection
