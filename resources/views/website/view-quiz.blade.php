@extends('website.app.layout')
@section('content')
    <div class="quiz-container">
        <div class="quiz-header">
            <h1>{{ $quiz->title }} Quiz</h1>
        </div>

        <form action="{{ route('quiz.submit', $quiz->id) }}" method="POST">
            @csrf
            @foreach ($quiz->questions as $index => $question)
                @if ($question->question_type == 'multiple_choice')
                    <div class="question-section">
                        <h2>{{ $index + 1 }}. {{ $question->question_text }}</h2>
                        <ul class="answers">
                            @foreach ($question->answers as $answer)
                                <li>
                                    <input type="radio" name="answers[{{ $question->id }}]" id="answer{{ $answer->id }}"
                                        value="{{ $answer->id }}">
                                    <label for="answer{{ $answer->id }}">{{ $answer->answer_text }}</label>
                                </li>
                            @endforeach

                            <!-- رسالة خطأ خاصة لكل سؤال -->
                            @if ($errors->has("answers.{$question->id}"))
                                <span class="text-danger">{{ $errors->first("answers.{$question->id}") }}</span>
                            @endif

                        </ul>
                    </div>
                @elseif($question->question_type == 'true_false')
                    <div class="question-section">
                        <h2>{{ $index + 1 }}. {{ $question->question_text }}</h2>
                        <ul class="answers">
                            <li>
                                <input type="radio" name="answers[{{ $question->id }}]" value="1"
                                    id="true{{ $question->id }}">
                                <label for="true{{ $question->id }}">True</label>
                            </li>
                            <li>
                                <input type="radio" name="answers[{{ $question->id }}]" value="0"
                                    id="false{{ $question->id }}">
                                <label for="false{{ $question->id }}">False</label>
                            </li>
                        </ul>
                    </div>
                @else
                    <div class="question-section">
                        <h2>{{ $index + 1 }}. {{ $question->question_text }}</h2>
                        <img src="{{ asset('storage/' . $question->photo) }}" alt="Question Image" class="quiz-photo">
                        <ul class="answers">
                            @foreach ($question->answers as $answer)
                                <li>
                                    <input type="radio" name="answers[{{ $question->id }}]"
                                        id="photoAnswer{{ $answer->id }}" value="{{ $answer->id }}">
                                    <label for="photoAnswer{{ $answer->id }}">{{ $answer->answer_text }}</label>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            @endforeach
            @if(!$quiz->quizzer_id==auth()->id())
                <button type="submit" class="submit-btn">Submit Quiz</button>
            @else
                <button class="submit-btn" disabled>Submit Quiz</button>
            @endif
        </form>
    </div>
@endsection
