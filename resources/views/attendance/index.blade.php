@extends('layouts.app')

@section('content')

<h1>Attendances</h1>
<strong>Filter Options: </strong>
<input type="checkbox" autocomplete="off" onchange="checkfilter(this.checked);"/>
<div id="filteroptions" style="display: none ;">
    {!! Form::open(['action' => 'AttendanceController@index', 'method' => 'POST']) !!}
    <table class="table" width="100%" table-layout="fixed">
        <tbody>
            <tr>
                <td width="33.3%">
                    <div class="form-group">
                        {{Form::label('student_name', 'Student Name')}}
                        {{Form::text('student_name', '', ['class' => 'form-control'])}}
                    </div>
                </td>
                <td width="33.3%">
                    <div class="form-group">
                        {{Form::label('group_id', 'Group Name')}}
                        {{Form::select('group_id', ['' => ''] + $groups, '', ['class' => 'form-control'])}}
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
                        {{Form::label('first_date', 'Start Date')}}
                        {{Form::text('first_date', '', ['class' => ' first_date form-control', 'placeholder' => 'yyyy-mm-dd'])}}
                    </div>
                </td>
                <td >
                    <div class="form-group">
                        {{Form::label('last_date', 'Last Date')}}
                        {{Form::text('last_date', '', ['class' => 'last_date form-control', 'placeholder' => 'yyyy-mm-dd'])}}
                    </div>
                </td>
                <td></td>
            </tr>
        </tbody>
    </table>

    {{Form::submit('Submit', ['class'=>'btn btn-primary', 'name' => 'submitBtn'])}}  
</div>

<br><br>
<table class="table table-striped" >
    <tr>
        <th>User ID</th>
        <th>Check Time</th>
        <th>Check Type</th>
    @foreach($attendances as $attendance)
    <tr>
        <td>{{$attendance['NAME']}}</td>
        <td>{{$attendance['CHECKTIME']}}</td>
        <td>{{$attendance['CHECKTYPE']}}</td>
    </tr>
    @endforeach
</table>

@endsection
