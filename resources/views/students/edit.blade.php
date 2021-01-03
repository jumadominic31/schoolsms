@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">Edit Student Details <a href="{{ route('students.index') }}" 
                class="pull-right btn btn-default btn-xs">Go Back</a></div>

            <div class="panel-body">
                {!! Form::open(['action' => ['StudentController@update', $student->Admno],'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
                    <div class="form-group">
                        {{Form::label('admno', 'Admission Number')}}
                        {{Form::text('admno', $student->Admno, ['class' => 'form-control'])}}
                    </div>
                    <div class="form-group">
                        {{Form::label('name', 'Student Name')}}
                        {{Form::text('name', $student->NAME, ['class' => 'form-control'])}}
                    </div>
                    <div class="form-group">
                        {{Form::label('gender', 'Gender')}}
                        {{Form::select('gender', ['' => '', 0 => 'Male', 1 => 'Female'], $student->GENDER, ['class' => 'form-control'])}}
                    </div>
                    <div class="form-group">
                        {{Form::label('parent_name', 'Parent Name')}}
                        {{Form::text('parent_name', $student->ParentName, ['class' => 'form-control'])}}
                    </div>
                    <div class="form-group">
                        {{Form::label('phone', 'Parent Phone')}}
                        {{Form::text('phone', $student->OPHONE, ['class' => 'form-control'])}}
                    </div>
                    <div class="form-group">
                        {{Form::label('class_name', 'Class/Form')}}
                        {{Form::select('class_name', ['' => '', '1' => '1', '2' => '2', '3' => '3', '4' => '4'], $student->Class, ['class' => 'form-control'])}}
                    </div>
                    <div class="form-group">
                        {{Form::label('stream', 'Stream')}}
                        {{Form::text('stream', $student->Stream, ['class' => 'form-control'])}}
                    </div>
                    <div class="form-group">
                        {{Form::label('boarder', 'Boarder *')}}
                        {{Form::select('boarder', ['1' => 'Boarder', '0' => 'Day Scholar'], $student->boarder, ['class' => 'form-control', 'placeholder' => ''])}}
                    </div>
                {{Form::hidden('_method', 'PUT')}}
                {{Form::submit('Submit', ['class'=>'btn btn-primary'])}}
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection