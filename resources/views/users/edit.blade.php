@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">Edit User Details <a 
                href="{{ route('users.index') }}"
                class="pull-right btn btn-default btn-xs">Go Back</a></div>

            <div class="panel-body">
              {!!Form::open(['action' => ['UsersController@update', $user->id],'method' => 'POST'])!!}
                <div class="form-group">
                    {{Form::label('name', 'Name')}}
                    {{Form::text('name', $user->name, ['class' => 'form-control'])}}
                </div>
                <div class="form-group">
                    {{Form::label('telephone', 'Phone Number')}}
                    {{Form::text('telephone', $user->telephone, ['class' => 'form-control'])}}
                </div>
                <div class="form-group">
                    {{Form::label('email', 'Email')}}
                    {{Form::text('email', $user->email, ['class' => 'form-control'])}}
                </div>
                <div class="form-group">
                    {{Form::label('status', 'Status')}}
                    {{Form::select('status', ['' => '', 1 => 'Active', 0 => 'Inactive'], $user->status, ['class' => 'form-control'])}}
                </div>
                {{Form::hidden('_method', 'PUT')}}
                {{Form::submit('Submit')}}
              {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection