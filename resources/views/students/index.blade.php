@extends('layouts.app')

@section('content')

<h1>Students</h1>

<strong>Filter Options: </strong>
<input type="checkbox" autocomplete="off" onchange="checkfilter(this.checked);"/>
<div id="filteroptions" style="display: none ;">

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
                        {{Form::text('student_name', '', ['class' => 'form-control'])}}
                    </div>
                </td>
                <td width="33.3%">
                    
                </td>
            </tr>
        </tbody>
    </table>


</div>

<table class="table table-striped" >
    <tr>
        <th>Adm No</th>
        <th>Student Name</th>
        <th>Parent Name</th>
        <th>Phone</th>
        <th>Class</th>
        <th>Stream</th>
    </tr>
    @foreach($students as $student)
    <tr>
        <td>{{$student['Admno']}}</td>
        <td>{{$student['NAME']}}</td>
        <td>{{$student['ParentName']}}</td>
        <td>{{$student['OPHONE']}}</td>
        <td>{{$student['Class']}}</td>
        <td>{{$student['Stream']}}</td>
    </tr>
    @endforeach
</table>

@endsection
