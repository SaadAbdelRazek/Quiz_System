@extends('admin.app.layout')
@section('custom-css')
    <link rel="stylesheet" href="{{asset('css/users.css')}}">
@endsection
@section('content')
    <div class="container">
        <h1>User Management</h1>
        <table class="users-table">
            <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Delete</th>
            </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{$user->name}}</td>
                <td><a href="mailto:{{$user->email}}">{{$user->email}}</a></td>
                <td>
                    @if($user->role==='SuperAdmin')
                        <p><strong>Super Admin</strong></p>
                    @elseif($user->role==='admin')
                        <a href="{{ route('users.toggleAdmin', $user->id) }}" onclick="return confirm('Are you sure you want to toggle the admin status?');">
                            {{ $user->role === 'admin' ? 'Remove Admin' : 'Make Admin' }}
                        </a>
                    @else
                        <a href="{{ route('users.toggleAdmin', $user->id) }}" onclick="return confirm('Are you sure you want to toggle the admin status?');">
                            {{ $user->role === 'admin' ? 'Remove Admin' : 'Make Admin' }}
                        </a>
                    @endif
                </td>
                <td>
                    @if($user->role==='SuperAdmin')
                        <p><strong>---</strong></p>
                    @elseif($user->role==='admin')
                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Are you sure you want to delete this user?');" class="admin-delete-btn">Delete</button>
                        </form>
                    @else
                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Are you sure you want to delete this user?');" class="admin-delete-btn">Delete</button>
                        </form>
                    @endif
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
