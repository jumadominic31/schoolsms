@extends('layouts.app')

@section('content')

<h1>Students</h1>

<table class="table table-striped" >
    <tr>
        <th>User ID</th>
        <th>Name</th>
        <th>Phone</th>
    @foreach($students as $student)
    <tr>
        <td>{{$student['USERID']}}</td>
        <td>{{$student['NAME']}}</td>
        <td>{{$student['OPHONE']}}</td>
    </tr>
    @endforeach
</table>

@endsection
