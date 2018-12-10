@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">Edit School Details <a 
                href="{{ route('school.index') }}"
                class="pull-right btn btn-default btn-xs">Go Back</a></div>

            <div class="panel-body">
              {!!Form::open(['action' => ['AdminController@updateschool', $school->id],'method' => 'POST'])!!}
                <div class="form-group">
                    {{Form::label('name', 'School Name')}}
                    {{Form::text('name', $school->name, ['class' => 'form-control', 'disabled' => 'true'])}}
                </div>
                <div class="form-group">
                    {{Form::label('telephone', 'Phone Number')}}
                    {{Form::text('telephone', $school->telephone, ['class' => 'form-control'])}}
                </div>
                <div class="form-group">
                    {{Form::label('address', 'Address')}}
                    {{Form::text('address', $school->address, ['class' => 'form-control'])}}
                </div>
                <div class="form-group">
                    {{Form::label('town', 'Town')}}
                    {{Form::text('town', $school->town, ['class' => 'form-control'])}}
                </div>
                <div class="form-group">
                    {{Form::label('email', 'Email')}}
                    {{Form::text('email', $school->email, ['class' => 'form-control'])}}
                </div>
                <div class="form-group">
                    {{Form::label('boarding', 'Boarding')}}
                    {{Form::select('boarding', ['' => '', 0 => 'Day', 1 => 'Boarding', 2 => 'Day and Boarding'], $school->boarding, ['class' => 'form-control'])}}
                </div>
                <div class="form-group">
                    {{Form::label('gender', 'Gender')}}
                    {{Form::select('gender', ['' => '', 0 => 'Male', 1 => 'Female', 2 => 'Mixed'], $school->gender, ['class' => 'form-control'])}}
                </div>
                {{Form::hidden('_method', 'PUT')}}
                {{Form::submit('Submit')}}
              {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection