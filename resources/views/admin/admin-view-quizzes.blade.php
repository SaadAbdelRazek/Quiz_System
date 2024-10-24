@extends('admin.app.layout')
@section('custom-css')
    <link rel="stylesheet" href="{{asset('css/users.css')}}">
@endsection
@section('content')
    <div class="container">
        <h1>Quizzes Management</h1>
        <table class="users-table">
            <thead>
            <tr>
                <th>#</th>
                <th>Title</th>
                <th>Subject</th>
                <th>View</th>
                <th>Delete</th>
            </tr>
            </thead>
            <tbody>
            @foreach($quizzes as $quiz)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$quiz->title}}</td>
                    <td>
                        {{$quiz->subject}}
                    </td>
                    <td>
                       <a href="{{route('quizzes.edit',$quiz->id)}}">View & Update</a>
                    </td>
                    <td>
                        <a href="#">Delete</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
