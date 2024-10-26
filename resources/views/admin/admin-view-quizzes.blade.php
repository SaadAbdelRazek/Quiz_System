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
                        <form action="{{route('quiz.destroy', $quiz->id)}}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Are you sure you want to delete this quiz?');">Delete</button>
                        </form>
                        
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
