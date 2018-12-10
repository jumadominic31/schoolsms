@extends('layouts.app')

@section('content')

<h1>Attendances</h1>
<a href="{{ route('attendance.index') }}" class="btn btn-success">Reset</a>
<br>
<strong>Filter Options: </strong>
<input type="checkbox" autocomplete="off" onchange="checkfilter(this.checked);"/>
<div id="filteroptions" style="display: none ;">
    {!! Form::open(['action' => 'AttendanceController@index', 'method' => 'POST']) !!}
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
                        {{Form::label('checktype', 'Check IN/OUT')}}
                        {{Form::select('checktype', ['' => '', 'I' => 'IN', 'O' => 'OUT'] , '', ['class' => 'form-control'])}}
                    </div>
                </td>
            </tr>
            <tr>
                <td >
                    <div class="form-group">
                        {{Form::label('class', 'Form')}}
                        {{Form::select('class', ['' => '', '1' => '1', '2' => '2', '3' => '3', '4' => '4'], '', ['class' => 'form-control'])}}
                    </div>
                </td>
                <td >
                    <div class="form-group">
                        {{Form::label('stream', 'Stream')}}
                        {{Form::text('stream', '', ['class' => 'form-control'])}}
                    </div>
                </td>
                <td>
                    <div class="form-group">
                        {{Form::label('first_date', 'Start Date')}}
                        {{Form::text('first_date', '', ['class' => 'date form-control', 'placeholder' => 'yyyy-mm-dd'])}}
                    </div>
                </td>
            </tr>
            <tr>
                <td >
                    <div class="form-group">
                        {{Form::label('last_date', 'Last Date')}}
                        {{Form::text('last_date', '', ['class' => 'date form-control', 'placeholder' => 'yyyy-mm-dd'])}}
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

<br><br>
@if(count($attendances) > 0)
<?php
    $colcount = count($attendances);
    $i = 1;
?>
<table class="table table-striped" >
    <tr>
        <th>Check Type</th>
        <th>Check Time</th>
        <th>User</th>
        <th>Form</th> 
        <th>Stream</th>         
    @foreach($attendances as $attendance)
    <tr>
        <td>{{$attendance['CHECKTYPE']}}</td>
        <td>{{$attendance['CHECKTIME']}}</td>
        <td>{{$attendance['NAME']}}</td>
        <td>{{$attendance['Class']}}</td>
        <td>{{$attendance['Stream']}}</td>
    </tr>
    @endforeach
</table>
@else
    <p>No Check in/out To Display</p>
@endif
@endsection
