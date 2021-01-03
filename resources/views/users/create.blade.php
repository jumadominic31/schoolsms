@extends('layouts.app')

@section('content')
    <h1>Create User</h1>
    <a href="{{ route('users.index') }}" class="pull-right btn btn-default">Go Back</a>
	<br>
    {!! Form::open(['action' => 'UsersController@store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
        <div class="form-group">
            {{Form::label('username', 'Username')}}
            {{Form::text('username', '', ['class' => 'form-control', 'placeholder' => 'Username'])}}
        </div>
        <div class="form-group">
            {{Form::label('name', 'Name')}}
            {{Form::text('name', '', ['class' => 'form-control', 'placeholder' => 'Name'])}}
        </div>
        <div class="form-group">
            {{Form::label('pass1', 'Password *')}}
            {{Form::password('pass1',  ['class' => 'form-control'])}}
        </div>
        <div class="form-group">
            {{Form::label('pass2', 'Password Again *')}}
            {{Form::password('pass2',  ['class' => 'form-control'])}}
        </div>
        <div class="form-group">
            {{Form::label('telephone', 'Telephone Number')}}
            {{Form::text('telephone', '', ['class' => 'form-control', 'placeholder' => 'Format example 254722000000'])}}
        </div>
        <div class="form-group">
            {{Form::label('email', 'Email')}}
            {{Form::text('email', '', ['class' => 'form-control', 'placeholder' => 'Email Address'])}}
        </div>
        <div class="form-group">
            {{Form::label('status', 'Status')}}
            {{Form::select('status', ['' => '', 1 => 'Active', 0 => 'Inactive'], 1, ['class' => 'form-control'])}}
        </div>
        {{Form::submit('Submit', ['class'=>'btn btn-primary'])}}
    {!! Form::close() !!}
    
@endsection