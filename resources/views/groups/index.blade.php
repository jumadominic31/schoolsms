@extends('layouts.app')

@section('content')

<h1>Groups</h1>

<table class="table table-striped" >
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Description</th>
    </tr>
    @foreach($groups as $group)
    <tr>
        <td>{{$group['DEPTID']}}</td>
        <td>{{$group['DEPTNAME']}}</td>
        <th>{{$group['Description']}}</th>
    </tr>
    @endforeach
</table>

@endsection
