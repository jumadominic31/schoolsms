@extends('layouts.app')

@section('content')

<h1>Portal Users Administration </h1>

<a href="{{ route('users.create') }}" class="btn btn-success">Add User</a>
<br>
    @if(count($users) > 0)
        <?php
            $colcount = count($users);
            $i = 1;
        ?>
        
        <table class="table table-striped" >
            <tr>
                <th>User Name</th>
                <th>Full Name</th>
                <th>Telephone</th>
                <th>Email</th>
                <th>Status</th>
                <th></th>
            </tr>
            @foreach($users as $user)
            <tr>
                <td>{{$user['username']}}</td>
                <td>{{$user['name']}}</td>
                <td>{{$user['telephone']}}</td>
                <td>{{$user['email']}}</td>
                <td><?php if ($user['status'] == 1 ) {echo "Active";} else {echo "Inactive";} ?></td>
                <td><a class="btn btn-default btn-xs" href="{{ route('users.edit', ['user' => $user->id ]) }}">Edit</a></td>
            </tr>
            @endforeach
        </table>
        {{$users->links()}}
    @else
      <p>No users To Display</p>
    @endif

@endsection
