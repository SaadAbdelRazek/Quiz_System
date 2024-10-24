@extends('admin.app.layout')
@section('content')
    <div class="content">
    <div class="container">
        <h1>Create a Quiz</h1>
        <form id="quiz-form" action="{{route('create-quiz')}}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="quiz-title">Quiz Title:</label>
                <input type="text" id="quiz-title" name="title" required>
            </div>
            <div class="form-group">
                <label for="quiz-subject">Quiz Subject:</label>
                <input type="text" id="quiz-subject" name="subject" required>
            </div>
            <div class="form-group">
                <label for="choice-questions">Number of Choice Questions:</label>
                <input type="number" id="choice-questions" name="choice-questions" min="0" required>
            </div>
            <div class="form-group">
                <label for="true-false-questions">Number of True/False Questions:</label>
                <input type="number" id="true-false-questions" name="true-false-questions" min="0" required>
            </div>
            <div class="form-group">
                <label for="photo-questions">Number of Photo Questions:</label>
                <input type="number" id="photo-questions" name="photo-questions" min="0" required>
            </div>
            <button type="button" id="generate-questions">Generate Questions</button>
            <div id="generated-questions" class="questions-container"></div>
            <button type="submit">Create Quiz</button>
        </form>
    </div>
    </div>

@endsection
