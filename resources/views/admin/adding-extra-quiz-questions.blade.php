@extends('admin.app.layout')
@section('custom-css')
    <link rel="stylesheet" href="{{asset('css/profile-photo.css')}}">
@endsection
@section('content')
    <div class="content">
        <div class="container">
            <h1>Adding Extra Questions for [{{$quiz->title}}] Quiz</h1>
            <form id="quiz-form" action="{{ route('questions.add', ['id' => $quiz->id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="choice-questions">Number of Choice Questions:</label>
                    <input type="number" id="choice-questions" name="choice-questions" min="0" value="0" required>
                </div>
                <div class="form-group">
                    <label for="true-false-questions">Number of True/False Questions:</label>
                    <input type="number" id="true-false-questions" name="true-false-questions" min="0" value="0" required>
                </div>
                <div class="form-group">
                    <label for="photo-questions">Number of Photo Questions:</label>
                    <input type="number" id="photo-questions" name="photo-questions" min="0" value="0" required>
                </div>
                <button type="button" id="generate-questions">Generate Questions</button>
                <div id="generated-questions" class="questions-container"></div>
                <button type="submit">Add Questions</button>
            </form>
        </div>
    </div>
    <script src="{{asset('js/create-quiz.js')}}"></script>
@endsection
