@extends('layouts.app')

@section('content')

<h1>School Details</h1>

<table class="table table-striped" >
    <tr>
    	<td>School Name</td>
    	<td>{{$schooldetails->name}}</td>
    </tr>
    <tr>
    	<td>Address</td>
    	<td>{{$schooldetails->address}}</td>
    </tr>
    <tr>
    	<td>City/Town</td>
    	<td>{{$schooldetails->town}}</td>
    </tr>
    <tr>
    	<td>Telephone</td>
    	<td>+{{$schooldetails->telephone}}</td>
    </tr>
    <tr>
    	<td>Email</td>
    	<td>{{$schooldetails->email}}</td>
    </tr>
    <tr>
    	<td>Boarding</td>
    	@if ($schooldetails->boarding == 0)
    		<td>Day</td>
    	@elseif ($schooldetails->boarding == 1)
    		<td>Boarding</td>
    	@else ($schooldetails->boarding == 2)
    		<td>Day and Boarding</td>
    	@endif
    </tr>
    <tr>
    	<td>Gender</td>
    	@if ($schooldetails->gender == 0)
    		<td>Male</td>
    	@elseif ($schooldetails->gender == 1)
    		<td>Female</td>
    	@else ($schooldetails->gender == 2)
    		<td>Mixed</td>
    	@endif
    </tr>

</table>
<a class="btn btn-default" href="{{ route('school.edit') }}">Edit Details</a>


@endsection
