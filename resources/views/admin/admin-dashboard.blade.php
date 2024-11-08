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
                    <h3>Total examinees</h3>
                    <p>{{$userExaminees}}</p>
                </div>
                <div class="stat-card">
                    <h3>your Quizzes</h3>
                    <p>{{$userQuizzes}}</p>
                </div>
                <div class="stat-card">
                    <h3>Active Quizzes</h3>
                    <p>{{$userActiveQuizzes}}</p>
                </div>
            </section>
        </div>
    </div>
@endsection
