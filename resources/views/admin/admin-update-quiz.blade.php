@extends('admin.app.layout')
{{--@section('custom-css')--}}
{{--    <link rel="stylesheet" href="{{asset('css/users.css')}}">--}}
{{--@endsection--}}
@section('content')
    <div class="content">
    <div class="questions-container">
        <h2>Edit Quiz</h2>

        <form action="{{ route('quizzes.update', $quiz->id) }}" method="POST" nctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Quiz Title and Subject -->
            <div class="form-group">
                <label for="quiz-title">Quiz Title</label>
                <input type="text" name="title" id="quiz-title" class="form-control" value="{{ $quiz->title }}" required>
            </div>

            <div class="form-group">
                <label for="quiz-subject">Quiz Subject</label>
                <input type="text" name="subject" id="quiz-subject" class="form-control" value="{{ $quiz->subject }}" required>
            </div>

            <h3>Questions</h3>

            <!-- Loop through the questions of the quiz -->
            @foreach ($quiz->questions as $index => $question)
                <div class="form-group">
                    <label for="question-{{ $index }}">Question #{{ $index + 1 }}</label>
                    <input type="text" name="questions[{{ $index }}][title]" id="question-{{ $index }}" class="form-control" value="{{ $question->question_text }}" required>

                    <input type="hidden" name="questions[{{ $index }}][id]" value="{{ $question->id }}">

                    <!-- If the question is multiple-choice -->
                    @if($question->question_type === 'multiple_choice')
                        <h4>Choices</h4>
                        @foreach($question->answers as $i => $answer)
                            <div class="form-group">
                                <label for="answer-{{ $index }}-{{ $i }}">Choice #{{ $i + 1 }}</label>
                                <input type="text" name="questions[{{ $index }}][answers][{{ $i }}][text]" id="answer-{{ $index }}-{{ $i }}" class="form-control" value="{{ $answer->answer_text }}">
                                <label>
                                    <input type="radio" name="questions[{{ $index }}][correct_answer]" value="{{ $answer->id }}" {{ $answer->is_correct ? 'checked' : '' }}> Correct
                                </label>
                                <input type="hidden" name="questions[{{ $index }}][answers][{{ $i }}][id]" value="{{ $answer->id }}">
                            </div>
                        @endforeach
                    @elseif($question->question_type === 'true_false')
                        <h4>True or False</h4>
                        <label>
                            <input type="radio" name="questions[{{ $index }}][is_true]" value="1" {{ $question->is_true ? 'checked' : '' }}> True
                        </label>
                        <label>
                            <input type="radio" name="questions[{{ $index }}][is_true]" value="0" {{ !$question->is_true ? 'checked' : '' }}> False
                        </label>
                    @else
                        @foreach($question->answers as $i => $answer)
                            <div class="form-group">
                                <label for="answer-{{ $index }}-{{ $i }}">Choice #{{ $i + 1 }}</label>
                                <input type="text" name="questions[{{ $index }}][answers][{{ $i }}][text]" id="answer-{{ $index }}-{{ $i }}" class="form-control" value="{{ $answer->answer_text }}">
                                <label>
                                    <input type="radio" name="questions[{{ $index }}][correct_answer]" value="{{ $answer->id }}" {{ $answer->is_correct ? 'checked' : '' }}> Correct
                                </label>
                                <input type="hidden" name="questions[{{ $index }}][answers][{{ $i }}][id]" value="{{ $answer->id }}">
                            </div>
                        @endforeach
                    @endif
                </div>
            @endforeach

            <button type="submit" class="btn btn-primary">Update Quiz</button>
        </form>
    </div>
    </div>
@endsection
