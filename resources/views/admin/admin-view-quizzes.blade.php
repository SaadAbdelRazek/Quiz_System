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
                <th>Active</th>
                <th>Title</th>
                <th>Subject</th>

                <th>actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($quizzes as $quiz)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    @if ($quiz->is_published==1)
                    <td style="width: 10px; text-align:center"><i class="fas fa-solid fa-circle-check" style="color:green"></i></td>
                    @elseif ($quiz->is_published==0)
                    <td style="width: 10px; text-align:center"><i class="fa-solid fa-circle-xmark" style="color: red"></i></td>
                    @endif
                    <td>{{$quiz->title}}</td>
                    <td>
                        {{$quiz->subject}}
                    </td>


                    <td style="display: flex; justify-content:center">
                        <a style="padding:10px; border:none; background-color: unset;width:fit-content;color:rgb(0, 106, 255)" href="{{route('quizzes.edit',$quiz->id)}}" title="edit"><i class="fas fa-edit"></i></a>
                        <form action="{{route('quiz.destroy', $quiz->id)}}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button style="background-color: unset;width:fit-content;color:rgb(218, 34, 34)" title="delete" type="submit" onclick="return confirm('Are you sure you want to delete this quiz?');"><i class="fas fa-trash"></i></button>
                        </form>

                        <form action="{{route('update-quiz-activate',$quiz->id)}}" method="POST">
                            @csrf
                            @method('PUT')
                            @if ($quiz->is_published==1)
                            <button style="background-color: unset;width:fit-content;color:black" title="disable"><i class="fas fa-regular fa-eye-slash"></i></button>
                            @elseif ($quiz->is_published==0)
                            <button style="background-color: unset;width:fit-content;color:black" title="enable"><i class="fas fa-regular fa-eye"></i></button>
                            @endif
                        </form>

                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
