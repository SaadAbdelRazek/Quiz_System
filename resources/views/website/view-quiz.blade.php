@extends('website.app.layout')
@section('content')
    <div class="quiz-container">
        <div class="quiz-header">
            <h1>{{ $quiz->title }} Quiz</h1>
{{--            <div class="progress-bar">--}}
{{--                <div class="progress"></div>--}}
{{--            </div>--}}
        </div>
        <!-- Multiple-choice question -->
        @foreach($quiz->questions as $index => $question)
            @if($question->question_type=="multiple_choice")
        <div class="question-section">
            <h2>{{$index + 1}}. {{$question->question_text}}</h2>
            <ul class="answers">
                @foreach($question->answers as $answer)
                <li><input type="radio" name="q{{ $question->id }}" id="answer{{ $answer->id }}"> <label for="answer{{ $answer->id }}">{{$answer->answer_text}}</label></li>
                @endforeach
            </ul>
        </div>
            @elseif($question->question_type=="true_false")
        <!-- True/False question -->
        <div class="question-section">
            <h2>{{$index + 1}}. {{$question->question_text}}</h2>
            <ul class="answers">
                <li><input type="radio" name="q{{ $question->id }}" value="1" id="answer1"> <label for="answer1">True</label></li>
                <li><input type="radio" name="q{{ $question->id }}" value="0" id="answer2"> <label for="answer2">False</label></li>
            </ul>
        </div>
            @else
        <!-- Photo question -->
        <div class="question-section">
            <h2>{{$index + 1}}. {{$question->question_text}}</h2>
            <img src="{{asset('storage/'.$question->photo)}}" alt="Animal" class="quiz-photo">
            <ul class="answers">
                @foreach($question->answers as $answer)
                <li><input type="radio" name="q{{ $question->id }}" id="answer{{ $answer->id }}"> <label for="answer{{ $answer->id }}">{{$answer->answer_text}}</label></li>
                @endforeach
            </ul>
        </div>
            @endif
        @endforeach

        <button class="submit-btn">Submit Quiz</button>

    </div>
@endsection
