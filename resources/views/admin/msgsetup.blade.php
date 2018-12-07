@extends('layouts.app')

@section('content')

<h1>Message Setup</h1>
The following placeholders may be used within the SMS text
<br>{name}, {gender}, {admno}, {clockdatetime}, {currentdatetime}

{!! Form::open(['action' => ['AdminController@updatemsg'],'method' => 'POST']) !!}
<table class="table" width="100%" table-layout="fixed">
    <tbody>
    	<tr>
            <td>
                <div class="form-group">
                    {{Form::label('clockinmsg', 'Clock In Message')}}
                    {{Form::textarea('clockinmsg', $msgtemplate->clockinmsg, ['class' => 'form-control', 'cols' => '50', 'rows' => '3', 'onKeyPress' =>"check_length(this.form);", 'onKeyDown '=>"check_length(this.form);"])}}
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="form-group">
                    {{Form::label('clockoutmsg', 'Clock Out Message')}}
                    {{Form::textarea('clockoutmsg', $msgtemplate->clockoutmsg, ['class' => 'form-control', 'cols' => '50', 'rows' => '3'])}}
                </div>
            </td>
        </tr>
        <tr>
            <td >
                <div class="form-group">
                    {{Form::label('negclockinmsg', 'Negative Clock In Message')}}
                    {{Form::textarea('negclockinmsg', $msgtemplate->negclockinmsg, ['class' => 'form-control', 'cols' => '50', 'rows' => '3'])}}
                </div>
            </td>
        </tr>
    </tbody>
</table>
{{Form::hidden('_method', 'PUT')}}
{{Form::submit('Submit', ['class'=>'btn btn-primary'])}}
{!! Form::close() !!}

@endsection
