@extends('layouts.app')

@section('content')

<h1>Attendances</h1>
<a href="{{ route('attendance.index') }}" class="btn btn-success">Reset</a>
<br>
<strong>Filter Options: </strong>
<input type="checkbox" autocomplete="off" onchange="checkfilter(this.checked);"/>
<div id="filteroptions" style="display: none ;">
    {!! Form::open(['action' => 'AttendanceController@index', 'method' => 'GET']) !!}
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
                        {{Form::label('class_name', 'Form*')}}
                        {{Form::select('class_name', ['' => '', '1' => '1', '2' => '2', '3' => '3', '4' => '4'], '', ['class' => 'form-control'])}}
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
                        {{Form::label('att_date', 'Date')}}
                        {{Form::text('att_date', '', ['class' => 'date form-control', 'placeholder' => 'yyyy-mm-dd'])}}
                    </div>
                </td>
            </tr>
        </tbody>
    </table>

    {{Form::submit('Submit', ['class'=>'btn btn-primary', 'name' => 'submitBtn'])}} 
    {{Form::submit('DownloadRpt', ['class'=>'btn btn-default', 'name' => 'submitBtn', 'formtarget' => '_blank'])}}
</div>

@include('attendance.attdata')

@endsection
