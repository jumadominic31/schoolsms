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
            <td>
                <div class="form-group">
                    {{Form::label('indv_grp', 'Individual or Group Message')}}
                    {{Form::select('indv_grp', ['' => '', '0' => 'Individual', '1' => 'Group'], '', ['class' => 'form-control', 'id' => 'indv_grp'])}}
                </div>
            </td>
            <td >
                <div class="form-group" id="class_name_div" style="display:none">
                    {{Form::label('class_name', 'Class/Form')}}
                    {{Form::select('class_name', ['' => '', '1' => '1', '2' => '2', '3' => '3', '4' => '4'], '', ['id' => 'class_name', 'class' => 'form-control'])}}
                </div>
            </td>
        </tr>
        <tr>
            <td >
                <div class="form-group admno_div" style="display:none">
                    {{Form::label('admno', 'Admission No (5 digits)')}}
                    {{Form::text('admno', '', ['id' => 'admno', 'class' => 'form-control'])}}
                </div>
            </td>
            <td >
                <div class="form-group admno_div" style="display:none">
                    {{Form::label('student_name', 'Student Name')}}
                    {{Form::text('student_name', '', ['id' => 'student_name', 'class' => 'form-control', 'disabled' => 'true'])}}
                </div>
            </td>
        </tr>
        <tr>
            
            <td>
                <div class="form-group">
                    {{Form::label('msg', 'Message')}}
                    {{Form::textarea('msg', $msg, ['class' => 'form-control', 'cols' => '50', 'rows' => '3'])}}
                </div>
            </td>
        </tr>
    </tbody>
</table>
{{Form::submit('Submit', ['class'=>'btn btn-primary', 'name' => 'submitBtn'])}}  

<script type="text/javascript">
    $(document).on('keyup', '#admno', function(e) {
        e.preventDefault();
        var admno = this.value;
        if (admno.length >= 5) {
            $.get('/attendance/getstudentname/'+admno, function(data){
                if (Object.keys(data).length == 0){
                    $("#student_name").val('Invalid admission number');
                }
                else {
                    $("#student_name").val(data);
                }
                
            });
        }
    });

    $(document).ready(function(){
        $('#indv_grp').on('change', function() {
            if ( this.value == '1') {
                $("#class_name_div").show();
                $(".admno_div").hide();
            }
            else if ( this.value == '0'){
                $(".admno_div").show();
                $("#class_name_div").hide();
            }
            else if ( this.value == ''){
                $(".admno_div").hide();
                $("#class_name_div").hide();
            }
        });
    });
</script>

@endsection
