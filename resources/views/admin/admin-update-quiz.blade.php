@extends('admin.app.layout')
@section('content')
    <style>
        /* General Container */
        .content {
            padding: 20px;
            background-color: #f9f9f9;
            font-family: Arial, sans-serif;
        }



        h2,
        h3 {
            color: #333;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .btn {
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn-primary {
            background-color: #28a745;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        /* Choices Layout */
        /* الحاوية العامة لكل سؤال */
        /* General container for each question */
        .question-container {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            background-color: #f5f5f5;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
        }

        .question-container:hover {
            transform: scale(1.01);
        }

        /* Question header */
        .question-header {
            margin-bottom: 15px;
        }

        .question-header label {
            font-weight: bold;
            font-size: 1.2em;
            color: #333;
        }

        .question-title {
            font-size: 1em;
            padding: 8px;
            width: 100%;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-top: 5px;
        }

        /* Choices section heading */
        .choices-heading {
            font-size: 1.1em;
            color: #444;
            margin-bottom: 10px;
        }

        /* Container for choices */
        .choices-container {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-bottom: 10px;
        }

        /* Individual choice item */
        .choice-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        /* Choice text input */
        .choice-text {
            flex: 1;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        /* Radio button group */
        .radio-group {
            display: flex;
            gap: 15px;
            padding-top: 10px;
        }
    </style>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Add smooth transition for each form-group
            const formGroups = document.querySelectorAll(".form-group");
            formGroups.forEach(group => {
                group.style.transition = "all 0.3s ease-in-out";
            });

            // Toggle visibility of choices for multiple choice questions
            document.querySelectorAll("input[type='radio']").forEach(radio => {
                radio.addEventListener("change", (event) => {
                    if (event.target.value === "multiple_choice") {
                        event.target.closest(".form-group").querySelector(".choices-container")
                            .style.display = "block";
                    } else {
                        event.target.closest(".form-group").querySelector(".choices-container")
                            .style.display = "none";
                    }
                });
            });
        });
    </script>

    <div class="content">
        <div class="questions-container">
            <h2>Edit Quiz</h2>
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
                <div class="form-group" style="display: flex; align-items:center; gap:20px">
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
    <script>
        // دالة للتحقق من الحالة وتعيين خاصية required لحقل كلمة المرور
        function checkVisibility() {
            const selectedOption = document.querySelector('input[name="visibility"]:checked');
            const passwordContainer = document.getElementById('password-container');
            const passwordInput = document.getElementById('password');
            const warn_pass = document.getElementById('warn-pass');



            if (selectedOption && selectedOption.value === 'private') {
                passwordContainer.style.display = 'block';
                passwordInput.required = true; // جعل الحقل مطلوبًا
                passwordInput.minLength = 8; // تحديد الحد الأدنى للطول ليكون 6

                if (passwordInput.value.length >= 8) {
                    warn_pass.style.display = 'none';
                }
                // setInterval(() => {
                //     if (!passwordInput.required || passwordInput.minLength !== 8) {
                //         alert("Tampering attempt detected! Please do not modify field settings.");
                //         passwordInput.required = true;
                //         passwordInput.minLength = 8;
                //     }
                // }, 1000); // كل ثانية
            } else {
                passwordContainer.style.display = 'none';
                passwordInput.required = false; // إزالة الخاصية إذا كان الحقل غير ظاهر
                passwordInput.minLength = 0; // إعادة الحد الأدنى للطول إلى 0 عند الإخفاء
            }


        }

        // تنفيذ الفحص عند تحميل الصفحة
        document.addEventListener('DOMContentLoaded', function() {
            checkVisibility(); // تحقق من حالة الخيار عند تحميل الصفحة

            const visibilityOptions = document.querySelectorAll('input[name="visibility"]');
            visibilityOptions.forEach(option => {
                option.addEventListener('change', checkVisibility);
            });
        });
    </script>
@endsection
