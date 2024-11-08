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
                <div id="timer" class="timer"> <span id="time"></span></div>
            @endif
        </div>

        <form action="{{ route('quiz.submit', $quiz->id) }}" method="POST" id="quizForm">
            @csrf
            @foreach ($quiz->questions as $index => $question)
                <div class="question-section">
                    <h2>{{ $index + 1 }}. {{ $question->question_text }}</h2>

                    <!-- معالجة أنواع الأسئلة -->
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

                    <!-- عرض رسالة خطأ لكل سؤال إذا لم يتم اختيار إجابة -->
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
                const duration = {{ $quiz->duration }} * 60; // Total duration in seconds
                const quizId = {{ $quiz->id }}; // Quiz ID
                const timerElement = document.getElementById('time');
                const formElement = document.getElementById('quizForm');

                // Storage key for remaining time
                const storageKey = `quizTimer_${quizId}`;

                // Check remaining time from localStorage or use the default duration
                let remainingTime = localStorage.getItem(storageKey) ? parseInt(localStorage.getItem(storageKey)) : duration;

                function startTimer() {
                    const timer = setInterval(() => {
                        if (remainingTime <= 0) {
                            clearInterval(timer);
                            localStorage.removeItem(storageKey);
                            submitFormViaAjax(); // Auto-submit the quiz
                        } else {
                            remainingTime--;
                            localStorage.setItem(storageKey, remainingTime);
                            displayTime(remainingTime);
                        }
                    }, 1000);
                }

                function displayTime(seconds) {
                    const minutes = Math.floor(seconds / 60);
                    const sec = seconds % 60;
                    timerElement.textContent = `Time Remaining: ${minutes}:${sec < 10 ? '0' : ''}${sec}`;
                }

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
                                localStorage.removeItem(storageKey);
                                window.location.href = "/home/quizzes"; // Redirect after successful submission
                            } else {
                                alert('Failed to submit the quiz. Please try again.');
                            }
                        })
                        .catch(error => {
                            console.error('Error submitting the quiz:', error);
                            alert('An error occurred while submitting the quiz.');
                        });
                }

                displayTime(remainingTime); // Initial display of the timer
                startTimer(); // Start the timer
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
    @endsection
@endsection
