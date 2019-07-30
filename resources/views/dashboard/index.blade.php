@extends('layouts.app')

@section('content')

<div class="container">
@include('inc.messages')

	<div class="row">
	    <section id="breadcrumb">
	        <div class="container">
	            <ol class="breadcrumb" style="text-align: center;">
	            <li class="active"><h2>Dashboard</h2></li>
	            </ol>
	        </div>
	    </section>
	</div>

    <!-- Common Tasks -->
    <div class="panel panel-default">
        <div class="panel-heading main-color-bg" style="text-align: center;">
            <h4 class="panel-title">Quick Links</h4>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-sm-4" style="text-align: center;">
                    <a class="btn btn-lg btn-primary" href="{{ route('attendance.sendcustommsg') }}" role="button">Send Custom Message</a></p>
                </div>
                <div class="col-sm-4" style="text-align: center;">
                    <a class="btn btn-lg btn-primary" href="{{ route('attendance.sendmsg') }}" role="button">Sync SMS</a></p>
                </div>
                <div class="col-sm-4" style="text-align: center;">
                    <a class="btn btn-lg btn-primary" href="{{ route('admin.loadcredit') }}" role="button">Load SMS Credit</a></p>
                </div>
            </div>
        </div>
    </div>

	<!-- Website Overview -->
    <div class="panel panel-default">
        <div class="panel-heading main-color-bg">
            <h3 class="panel-title" style="text-align: center;">Overview</h3>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-sm-6">
                    <a href="{{ route('students.index') }}" style='text-decoration: none; color: black'>
                        <div class="well dash-box">
                            <h2><span class="glyphicon glyphicon-inbox" aria-hidden="true"></span> {{$num_students}}</h2>
                            <h4>Total Students</h4>
                        </div>
                    </a>
                </div>
                <div class="col-sm-6">
                    <a href="" style='text-decoration: none; color: black'>
                        <div class="well dash-box">
                        <h2><span class="glyphicon glyphicon-user" aria-hidden="true"></span> {{$bal}}</h2>
                        <h4>SMS Balance</h4>
                    </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection