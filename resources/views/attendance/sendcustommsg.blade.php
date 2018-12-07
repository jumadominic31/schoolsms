@extends('layouts.app')

@section('content')

<h1>Send Custom Message</h1>

<a class="btn btn-default" href="{{ route('attendance.sendmsg') }}">Sync SMS</a>
{!! Form::open(['action' => 'AttendanceController@postcustommsg', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
<table class="table" width="100%" table-layout="fixed">
    <tbody>
        <tr>
            <td width="50%">
                <div class="form-group">
                    {{Form::label('status', 'Status')}}
                    {{Form::select('status', ['' => '', '0' => 'Check Out', '1' => 'Check In'], '', ['class' => 'form-control'])}}
                </div>
            </td>
            <td width="50%">
                <div class="form-group">
                    {{Form::label('reason', 'Reason')}}
                    {{Form::select('reason', ['' => ''] + $reasons, '', ['class' => 'form-control'])}}
                </div>
            </td>
        </tr>
        <tr>
            <td >
                <div class="form-group">
                    {{Form::label('student_id', 'Name')}}
                    {{Form::select('student_id', ['' => ''] + $students, '', ['class' => 'form-control'])}}
                </div>
            </td>
            <td >
            </td>
        </tr>
        <tr>
            <td >
                <div class="form-group">
                    {{Form::label('admno', 'Admission No')}}
                    {{Form::text('admno', '', ['class' => 'form-control'])}}
                </div>
            </td>
            <td >
                <div class="form-group">
                    {{Form::label('student_name', 'Student Name')}}
                    {{Form::text('student_name', '', ['class' => 'form-control', 'disabled' => 'true'])}}
                </div>
            </td>
        </tr>
    </tbody>
</table>
{{Form::submit('Submit', ['class'=>'btn btn-primary', 'name' => 'submitBtn'])}}  

@endsection
