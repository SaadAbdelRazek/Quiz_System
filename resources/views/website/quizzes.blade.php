@extends('website.app.layout')
@section('custom-css')
    <link rel="stylesheet" href="{{asset('css/quizzes.css')}}">
@endsection
@section('content')

<br>
<center>
    <h1>Available Quizzes</h1>
</center>
<section id="quizzes" class="quiz-section">
    <div class="container">
        <div class="quiz-grid">
            @foreach($quizzes as $quiz)
            <div class="quiz-card">
                <h3>{{$quiz->title}}</h3>
                <p>{{$quiz->subject}}</p>
                <a href="{{route('view-quiz',$quiz->id)}}" class="btn">Take Quiz</a>
            </div>
            @endforeach
        </div>
    </div>
</section>



@endsection
