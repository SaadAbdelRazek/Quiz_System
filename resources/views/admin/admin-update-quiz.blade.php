@extends('admin.app.layout')
@section('custom-css')
    <link rel="stylesheet" href="{{asset('css/admin-update-quiz.css')}}">
@endsection
@section('content')

    <div class="content">
        <div class="questions-container">
            <div class="head-container" style="display: flex; justify-content: space-between; align-items: center;">
                <h2>Edit Quiz</h2>
                <a href="{{route('questions.add.view',$quiz->id)}}" class="add-question-btn" style="padding: 8px 12px; width: 176px; background-color: #4CAF50; color: white; border: none; border-radius: 4px; cursor: pointer; text-decoration: none;">Add New Questions</a>
            </div>
            <form action="{{ route('quizzes.update', $quiz->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Quiz Title and Subject -->
                <div class="form-group">
                    <label for="quiz-title">Quiz Title</label>
                    <input type="text" name="title" id="quiz-title" class="form-control" value="{{ $quiz->title }}"
                        required>
                </div>

                <div class="form-group">
                    <label for="quiz-subject">Quiz Subject</label>
                    <input type="text" name="subject" id="quiz-subject" class="form-control" value="{{ $quiz->subject }}"
                        required>
                </div>

                <div class="form-group">
                    <label for="quiz-description">Description:</label>
                    <textarea id="quiz-description" name="description" class="form-control" required>{{ $quiz->title }}</textarea>
                </div>
                <div class="form-group" style=" align-items:center; gap:20px">
                    <label for="quiz-duration">Duration (in minutes):</label>
                    <input type="number" id="quiz-duration" name="duration" class="form-control" min="0"
                        value="{{ $quiz->duration }}" required style="width: 150px;">

                    <label for="attempts">Number of Attempts:</label>
                    <input type="number" id="attempts" class="form-control" name="attempts" min="1"
                        value="{{ $quiz->attempts }}" required style="width: 150px;">
                </div>
                <div class="form-group">
                    <label>Show Answers After Submission:</label>
                    <div>
                        <label>
                            <input type="radio" name="show_answers_after_submission" value="1"
                                {{ $quiz->show_answers_after_submission == 1 ? 'checked' : '' }} required> Yes
                        </label>
                        <label>
                            <input type="radio" name="show_answers_after_submission" value="0"
                                {{ $quiz->show_answers_after_submission == 0 ? 'checked' : '' }} required> No
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="visibility">Visibility:</label>
                    <div>
                        <label>
                            <input type="radio" name="visibility" value="public"
                                {{ $quiz->visibility == 'public' ? 'checked' : '' }} required> Public
                        </label>
                        <label>
                            <input type="radio" name="visibility" value="private"
                                {{ $quiz->visibility == 'private' ? 'checked' : '' }} required> Private
                        </label>
                    </div>
                </div>
                <div class="form-group" id="password-container" style="display: none;">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" value="{{ $quiz->password }}">
                    <p id="warn-pass" style="font-size: 10px; color:red">min:8</p>
                </div>

                {{-- --------------------- --}}
                <div style="display: flex; justify-content:space-between;">

                    <h3>Questions</h3>
                    <p style="border-radius:50%; border-bottom:1px solid gray;padding:5px;margin-bottom:10px">
                        {{ $quiz_points }}/{{ $quiz_points }} points</p>
                </div>
                @foreach ($quiz->questions as $index => $question)
                    <div class="question-container">
                        <div class="question-header">
                            <div style="display: flex; justify-content: space-between">
                                <label for="question-{{ $index }}">Question {{ $index + 1 }}:</label>
                                <span>
                                    <input type="number" name="questions[{{ $index }}][points]"
                                        value="{{ $question->points }}" style="width: 30px; padding: 2px;"> points
                                </span>
                            </div>
                            <input type="text" name="questions[{{ $index }}][title]"
                                id="question-{{ $index }}" class="form-control question-title"
                                value="{{ $question->question_text }}" required>
                        </div>

                        <input type="hidden" name="questions[{{ $index }}][id]" value="{{ $question->id }}">

                        @if ($question->question_type === 'multiple_choice')
                            <h4 class="choices-heading">Choices</h4>
                            <div class="choices-container">
                                @foreach ($question->answers as $i => $answer)
                                    <div class="choice-item">
                                        <input type="text"
                                            name="questions[{{ $index }}][answers][{{ $i }}][text]"
                                            id="answer-{{ $index }}-{{ $i }}"
                                            class="form-control choice-text" value="{{ $answer->answer_text }}">
                                        <label class="radio-group">
                                            <input type="radio" name="questions[{{ $index }}][correct_answer]"
                                                value="{{ $answer->id }}" {{ $answer->is_correct ? 'checked' : '' }}>
                                            Correct
                                        </label>
                                        <input type="hidden"
                                            name="questions[{{ $index }}][answers][{{ $i }}][id]"
                                            value="{{ $answer->id }}">
                                    </div>
                                @endforeach
                            </div>
                        @elseif($question->question_type === 'true_false')
                            <h4 class="true-false-heading">True/False</h4>
                            <div class="radio-group">
                                <label>
                                    <input type="radio" name="questions[{{ $index }}][is_true]" value="1"
                                        {{ $question->is_true ? 'checked' : '' }}> True
                                </label>
                                <label>
                                    <input type="radio" name="questions[{{ $index }}][is_true]" value="0"
                                        {{ !$question->is_true ? 'checked' : '' }}> False
                                </label>
                            </div>
                        @elseif ($question->question_type === 'photo')
                            {{-- <h4 class="photo-question-heading">Question:</h4> --}}
                            <div class="photo-question">
                                {{-- <p>{{ $question->question_text }}</p> <!-- عرض نص السؤال --> --}}
                                <img src="{{ asset('storage/' . $question->photo) }}" alt="Question Image"
                                    style="width: 80%; display:block; margin-left:10%; margin-bottom: 10px;">
                                <!-- عرض الصورة -->

                                <h4 class="choices-heading">Choices:</h4>
                                <div class="choices-container">
                                    @foreach ($question->answers as $i => $answer)
                                        <!-- افتراض أن لديك مجموعة من الاختيارات -->
                                        <div class="choice-item">
                                            <input type="text"
                                                name="questions[{{ $index }}][answers][{{ $i }}][text]"
                                                id="answer-{{ $index }}-{{ $i }}"
                                                class="form-control choice-text" value="{{ $answer->answer_text }}">
                                            <label class="radio-group">
                                                <input type="radio" name="questions[{{ $index }}][correct_answer]"
                                                    value="{{ $answer->id }}" {{ $answer->is_correct ? 'checked' : '' }}>
                                                Correct
                                            </label>
                                            <input type="hidden"
                                                name="questions[{{ $index }}][answers][{{ $i }}][id]"
                                                value="{{ $answer->id }}">
                                        </div>



                                    @endforeach
                                </div>

                                <label>Update Image for Question:</label>
                                <input type="file" name="questions[{{ $index }}][image]" class="form-control">
                                <!-- حقل رفع صورة -->
                            </div>
                        @endif
                    </div>
                @endforeach




                <button type="submit" class="btn btn-primary">Update Quiz</button>
            </form>
        </div>
    </div>
    <script src="{{asset('js/admin-update-quiz.js')}}">
    </script>
@endsection
