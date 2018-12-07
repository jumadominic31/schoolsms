@extends('layouts.app')

@section('content')
<div class="container">
      <!-- PANELS -->
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title"><strong>User Profile</strong></h3>
        </div>
        <div class="panel-body">
        	<div class="row">
        		<div class="col-md-12">
			        <table class="table table-striped">
			          <tr><td>Username</td><td>{{$user['username']}}</td></tr>
			          <tr><td>Full Name</td><td>{{$user['name']}}</td></tr>
			          <tr><td>Phone Number</td><td>{{$user['telephone']}}</td></tr>
			          <tr><td>Email Address</td><td>{{$user['email']}}</td></tr>
			          <tr><td>Status</td><td> 
			          	@if ($user['status'] == 1)
			      			Active
			      		@else
			      			Inactive
			      		@endif
			      		</td></tr>  
					
			        </table>
			    </div>
	    	</div>
    	</div>
    	
    	<div class="panel-footer">
        	<a href="#" class="btn btn-success">Edit User Details</a>
        	<a href="#" class="btn btn-success">Reset Password</a>
        </div>
      
        
	</div>
</div>

@endsection