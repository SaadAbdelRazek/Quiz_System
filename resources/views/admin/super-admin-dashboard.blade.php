@extends('admin.app.layout')
@section('custom-css')
    <link rel="stylesheet" href="{{asset('css/profile-photo.css')}}">
@endsection
@section('content')
    <div class="main-content">
        <header class="header">
            <h1>Dashboard</h1>
            <div class="user-info">
                <span>Welcome, {{$user_data->name}}</span>
                @if($user_data->profile_photo_path)
                <a href="{{route('profile.show')}}">
                    <img src="{{asset('storage/'. $user_data->profile_photo_path)}}" alt="{{ $user_data->name }}"  class="profile-picture">
                </a>
                @else
                <a href="{{route('profile.show')}}">
                    <img src="{{asset('images/def.jpg')}}" alt="{{ $user_data->name }}" class="profile-picture">
                </a>
                @endif
            </div>
        </header>
        <div class="content">
            <section class="stats">
                <div class="stat-card">
                    <h3>Total Users</h3>
                    <p>{{$users}}</p>
                </div>
                <div class="stat-card">
                    <h3>Total Quizzes</h3>
                    <p>{{$quizzes}}</p>
                </div>
                <div class="stat-card">
                    <h3>Total Examinees</h3>
                    <p>{{$examinees}}</p>
                </div>
                <div class="stat-card">
                    <h3>Active Quizzes</h3>
                    <p>{{$activeQuizzes}}</p>
                </div>
            </section>
            <section class="table">
                <div class="activities-header">
                    <h2>Recent Activities</h2>
                    <form action="{{ route('user-activities.deleteAll') }}" method="POST" id="deleteAllActivitiesForm">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Are you sure you want to delete all activities?')" class="btn btn-danger">Delete All Activities</button>
                    </form>
                </div>
                <table>
                    <thead>
                    <tr>
                        <th>User</th>
                        <th>Action</th>
                        <th>Date</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($activities as $activity)
                    <tr>
                        <td>{{$activity->user->name}}</td>
                        <td>{{$activity->activity}}</td>
                        <td>{{$activity->created_at->format('Y-m-d')}}</td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </section>
        </div>
    </div>
@endsection
