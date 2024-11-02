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
                @if ($state = 1)
                <th>quiz</th>
                @endif
                <th>examinee</th>
                <th>email</th>
                <th>quiz result</th>

                <th>actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($examinees as $examinee)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$examinee->quiz->title}}</td>
                    <td>
                        {{$examinee->user->name}}
                    </td>

                    <td>
                        <a class="a-table" href="mailto:{{$examinee->user->email}}">{{$examinee->user->email}}</a>
                    </td>

                    <td>
                        {{$examinee->correct_answers}}
                    </td>

                    <td>
                        <i class="fas fa-trash"></i>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
