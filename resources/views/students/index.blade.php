@extends('layouts.app')

@section('content')

<h1>Students</h1>
<a href="{{ route('students.index') }}" class="btn btn-success">Reset</a>
<br>
<h4>Filter Options: </h4>

<input type="checkbox" autocomplete="off" onchange="checkfilter(this.checked);" />

<div id="filteroptions" style="display: none ;">
    {!! Form::open(['action' => 'StudentController@index', 'method' => 'POST']) !!}
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
                        {{Form::label('class', 'Form')}}
                        {{Form::select('class', ['' => '', '1' => '1', '2' => '2', '3' => '3', '4' => '4'], '', ['class' => 'form-control'])}}
                    </div>
                </td>
                <td>
                    <div class="form-group">
                        {{Form::label('stream', 'Stream')}}
                        {{Form::text('stream', '', ['class' => 'form-control'])}}
                    </div>
                </td>
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
    </tr>
    @endforeach
</table>
@else
    <p>No Students To Display</p>
@endif

@endsection
