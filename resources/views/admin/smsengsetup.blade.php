@extends('layouts.app')

@section('content')

<h1>SMS Engine Setup</h1>
{!! Form::open(['action' => ['AdminController@updatesmseng'],'method' => 'POST']) !!}
<table class="table" width="100%" table-layout="fixed">
    <tbody>
    	<tr>
            <td>
                <div class="form-group">
                    {{Form::label('school_name', 'School Name')}}
                    {{Form::text('school_name', $school_name, ['class' => 'form-control', 'disabled' => 'true'])}}
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="form-group">
                    {{Form::label('atgusername', 'Username')}}
                    {{Form::text('atgusername', $apidetails->atgusername, ['class' => 'form-control'])}}
                </div>
            </td>
        </tr>
        <tr>
            <td >
                <div class="form-group">
                    {{Form::label('atgapikey', 'API Key')}}
                    {{Form::text('atgapikey', $apidetails->atgapikey, ['class' => 'form-control'])}}
                </div>
            </td>
        </tr>
        <tr>
            <td >
                <div class="form-group">
                    {{Form::label('atgsender_id', 'Sender ID')}}
                    {{Form::text('atgsender_id', $apidetails->atgsender_id, ['class' => 'form-control'])}}
                </div>
            </td>
        </tr>
    </tbody>
</table>
{{Form::hidden('_method', 'PUT')}}
{{Form::submit('Submit', ['class'=>'btn btn-primary'])}}
{!! Form::close() !!}

@endsection
