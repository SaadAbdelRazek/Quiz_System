@extends('website.app.layout')
@section('custom-meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('content')
    <style>
        .timer {
            font-size: 1.5rem;
            margin-bottom: 20px;
            color: #ff4c4c; /* لون لافت للانتباه */
        }
    </style>
    <div class="quiz-container">
        <div class="quiz-header">
            <h1>{{ $quiz->title }} Quiz</h1>
            @if($quiz->quizzer->user_id != auth()->id())
                <div id="timer" class="timer">Time Remaining: <span id="time"></span></div>
            @endif
        </div>

        <form action="{{ route('quiz.submit', $quiz->id) }}" method="POST" id="quizForm">
            @csrf
            @foreach ($quiz->questions as $index => $question)
                <div class="question-section">
                    <h2>{{ $index + 1 }}. {{ $question->question_text }}</h2>

                    @if ($question->question_type == 'multiple_choice')
                        <ul class="answers">
                            @foreach ($question->answers as $answer)
                                <li>
                                    <input type="radio" name="answers[{{ $question->id }}]" id="answer{{ $answer->id }}" value="{{ $answer->id }}">
                                    <label for="answer{{ $answer->id }}">{{ $answer->answer_text }}</label>
                                </li>
                            @endforeach
                        </ul>
                    @elseif ($question->question_type == 'true_false')
                        <ul class="answers">
                            <li>
                                <input type="radio" name="answers[{{ $question->id }}]" value="1" id="true{{ $question->id }}">
                                <label for="true{{ $question->id }}">True</label>
                            </li>
                            <li>
                                <input type="radio" name="answers[{{ $question->id }}]" value="0" id="false{{ $question->id }}">
                                <label for="false{{ $question->id }}">False</label>
                            </li>
                        </ul>
                    @elseif ($question->question_type == 'photo')
                        <img src="{{ asset('storage/' . $question->photo) }}" alt="Question Image" class="quiz-photo">
                        <ul class="answers">
                            @foreach ($question->answers as $answer)
                                <li>
                                    <input type="radio" name="answers[{{ $question->id }}]" id="photoAnswer{{ $answer->id }}" value="{{ $answer->id }}">
                                    <label for="photoAnswer{{ $answer->id }}">{{ $answer->answer_text }}</label>
                                </li>
                            @endforeach
                        </ul>
                    @endif

                    @if ($errors->has("answers.{$question->id}"))
                        <span class="text-danger">{{ $errors->first("answers.{$question->id}") }}</span>
                    @endif
                </div>
            @endforeach

            @if($quiz->quizzer->user_id != auth()->id())
                <button type="submit" class="submit-btn">Submit Quiz</button>
            @endif
        </form>
    </div>

    @section('custom-js')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const duration = {{ $quiz->duration }} * 60; // المدة الزمنية بالكويز
                const quizId = {{ $quiz->id }}; // معرف الكويز
                const timerElement = document.getElementById('time');
                const formElement = document.getElementById('quizForm');

                // مفتاح التخزين الخاص بالوقت المتبقي لكل كويز
                const storageKey = `quizTimer_${quizId}_{{ auth()->id() }}`;

                // إعادة ضبط المؤقت عند الدخول إلى كويز جديد أو بدء كويز جديد
                let remainingTime = localStorage.getItem(storageKey) ? parseInt(localStorage.getItem(storageKey)) : duration;

                // تحديث العرض عند فتح الكويز أو إعادة فتحه
                function startTimer() {
                    const timer = setInterval(() => {
                        if (remainingTime <= 0) {
                            clearInterval(timer);
                            localStorage.removeItem(storageKey); // إزالة التخزين المحلي عند انتهاء الوقت
                            submitFormViaAjax();
                        } else {
                            remainingTime--;
                            localStorage.setItem(storageKey, remainingTime); // تحديث الوقت المتبقي
                            displayTime(remainingTime);
                        }
                    }, 1000);
                }

                // تنسيق العرض للوقت المتبقي
                function displayTime(seconds) {
                    const minutes = Math.floor(seconds / 60);
                    const sec = seconds % 60;
                    timerElement.textContent = `${minutes}:${sec < 10 ? '0' : ''}${sec}`;
                }

                // الإرسال التلقائي عند انتهاء الوقت باستخدام AJAX
                function submitFormViaAjax() {
                    const formData = new FormData(formElement);

                    fetch(formElement.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: formData
                    })
                    .then(response => {
                        if (response.ok) {
                            alert('Quiz submitted successfully');
                            localStorage.removeItem(storageKey); // إزالة التخزين بعد الإرسال
                            window.location.href = "/home/quizzes";
                        } else {
                            alert('Failed to submit the quiz. Please try again.');
                        }
                    })
                    .catch(error => {
                        console.error('Error submitting the quiz:', error);
                        alert('An error occurred while submitting the quiz.');
                    });
                }

<<<<<<< HEAD
                displayTime(remainingTime); // Initial display of the timer
                startTimer(); // Start the timer
                quizForm.addEventListener("submit", function(event) {
                    localStorage.removeItem(storageKey); // Remove quiz answers from localStorage after submission
                });
=======
                // تحديث العداد وبدء تشغيل المؤقت
                displayTime(remainingTime);
                startTimer();
>>>>>>> 00050b1119ce5f1292e74e03f101ae7eabfd0867
            });
        </script>


        <script>
            setInterval(() => {
                fetch('/keep-alive');
            }, 5 * 60 * 1000);
        </script>
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                setInterval(() => {
                    fetch('/refresh-session')
                        .then(response => response.json())
                        .then(data => {
                            if (data.token) {
                                document.querySelector('meta[name="csrf-token"]').setAttribute('content', data.token);
                                document.querySelectorAll('input[name="_token"]').forEach(input => {
                                    input.value = data.token;
                                });
                                console.log('CSRF token refreshed successfully.');
                            }
                        })
                        .catch(error => {
                            console.error('Error refreshing CSRF token:', error);
                        });
                }, 300000);
            });
        </script>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const quizForm = document.getElementById("quizForm");
                const quizId = {{ $quiz->id }}; // Get the quiz ID
                const storageKey = `quizAnswers_${quizId}`; // Unique key for localStorage

                // Load saved answers from localStorage
                const savedAnswers = JSON.parse(localStorage.getItem(storageKey)) || {};

                // Pre-select saved answers
                for (const questionId in savedAnswers) {
                    const answerId = savedAnswers[questionId];
                    const radioButton = document.querySelector(`input[name="answers[${questionId}]"][value="${answerId}"]`);
                    if (radioButton) {
                        radioButton.checked = true;
                    }
                }

                // Save answer when user selects a radio button
                quizForm.addEventListener("change", (event) => {
                    if (event.target.type === "radio") {
                        const questionId = event.target.name.match(/\d+/)[0]; // Extract question ID
                        const answerId = event.target.value;
                        savedAnswers[questionId] = answerId; // Save answer for this question
                        localStorage.setItem(storageKey, JSON.stringify(savedAnswers)); // Update localStorage
                    }
                });


                quizForm.addEventListener("submit", function(event) {
                    localStorage.removeItem(storageKey); // Remove quiz answers from localStorage after submission
                });
            });
        </script>

    @endsection
@endsection
