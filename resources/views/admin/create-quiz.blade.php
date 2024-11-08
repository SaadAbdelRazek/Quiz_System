@extends('admin.app.layout')
@section('custom-css')
    <link rel="stylesheet" href="{{asset('css/create-quiz.css')}}">
@endsection
@section('content')
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>


@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

<div class="content">
    <div class="container">
        <h1>Create a Quiz</h1>
        <form id="quiz-form" action="{{ route('create-quiz') }}" method="POST" enctype="multipart/form-data">
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
                <label for="quiz-description">Description:</label>
                <div id="editor-container"></div>
                <input type="hidden" id="quiz-description" name="description" required>
            </div>
            <div class="form-group" style="display: flex; align-items:center; gap:20px">
                <label for="quiz-duration">Duration (in minutes):</label>
                <span style="font-size: 11px; color:rgb(226, 52, 52)">(note: 0 means no time)</span>
                <input type="number" id="quiz-duration" name="duration" min="0" value="0" required style="width: 150px;">
                <label for="attempts">Number of Attempts:</label>
                <input type="number" id="attempts" name="attempts" min="1" value="1" required style="width: 150px;">
            </div>
            <div class="form-group">
                <label>Show Answers After Submission:</label>
                <div>
                    <label>
                        <input type="radio" name="show_answers_after_submission" value="1" required checked> Yes
                    </label>
                    <label>
                        <input type="radio" name="show_answers_after_submission" value="0" required> No
                    </label>
                </div>
            </div>
            <div class="form-group">
                <label for="visibility">Visibility:</label>
                <div>
                    <label>
                        <input type="radio" name="visibility" value="public" required checked> Public
                    </label>
                    <label>
                        <input type="radio" name="visibility" value="private" required> Private
                    </label>
                </div>
            </div>
            <div class="form-group" id="password-container" style="display: none;">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" minlength="6">
            </div>
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
            <button type="submit">Create Quiz</button>
        </form>
    </div>
</div>
    <script src="{{asset('js/create-quiz.js')}}"></script>
@endsection
