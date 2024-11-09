@extends('website.app.layout')
@section('custom-meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('content')
<div class="container">


    <div class="quiz-container">
        <div class="quiz-header">
            <h1>{{ $quiz->title }} Quiz</h1>
            @if($quiz->quizzer->user_id != auth()->id())
                <div id="timer" class="timer"><span id="time"></span></div>
            @endif
        </div>

        <form id="quizForm" action="{{ route('quiz.submit', $quiz->id) }}" method="POST">
            @csrf

            {{-- قسم البريد الإلكتروني --}}
            <div id="email-section" class="section">
                <label for="email">Email:</label>
                @if (auth()->user())
                    <input type="email" name="email" id="email" placeholder="Enter your email" class="form-control"
                        value="{{ auth()->user()->email }}" readonly>
                @else
                    <input type="email" name="email" id="email" placeholder="Enter your email" class="form-control" required>
                @endif
                <div class="button-group">
                    <button type="button" class="next-btn" onclick="nextSection()">Next</button>
                </div>
            </div>

            {{-- قسم الأسئلة --}}
            @foreach ($quiz->questions as $index => $question)
                <div class="section question-section" id="question-section-{{ $index }}" style="display: none;">
                    <h2>{{ $index + 1 }}. {{ $question->question_text }}</h2>
                    <ul class="answers">
                        @foreach ($question->answers as $answer)
                            <li>
                                <input type="radio" name="answers[{{ $question->id }}]" id="answer{{ $answer->id }}"
                                    value="{{ $answer->id }}" required>
                                <label for="answer{{ $answer->id }}">{{ $answer->answer_text }}</label>
                            </li>
                        @endforeach
                        @if ($errors->has("answers.{$question->id}"))
                            <span class="text-danger">{{ $errors->first("answers.{$question->id}") }}</span>
                        @endif
                    </ul>

                    {{-- أزرار التنقل بين الأسئلة --}}
                    <div class="button-group">
                        <button type="button" class="prev-btn" onclick="prevSection({{ $index }})">Back</button>
                        @if ($index < count($quiz->questions) - 1)
                            <button type="button" class="next-btn" onclick="nextSection()">Next</button>
                        @else
                            <button type="submit" class="submit-btn">Submit Quiz</button>
                        @endif
                    </div>
                </div>
            @endforeach
        </form>
    </div>
</div>

    <style>
        .container{
            min-height: 100vh;
        }
        .quiz-container {
            max-width: 600px;
            margin: auto;
            margin-top: 50px;
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .quiz-header h1 {
            text-align: center;
            color: #4a4a4a;
            font-size: 24px;
            margin-bottom: 20px;
        }
        .form-control {
            width: 100%;
            padding: 10px;
            margin-top: 8px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .button-group {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
        }
        .next-btn, .prev-btn, .submit-btn {
            padding: 8px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        .next-btn {
            background-color: #4CAF50;
            color: white;
        }
        .prev-btn {
            background-color: #ddd;
            color: rgb(0, 0, 0);
            width: 50px;
            padding: 1px;
            font-size: 12px;
        }
        .submit-btn {
            background-color: #008CBA;
            color: white;
        }
        .question-section {
            margin-bottom: 20px;
        }
        .answers {
            list-style: none;
            padding: 0;
        }
        .answers li {
            margin-bottom: 10px;
        }
    </style>

    <script>
        let currentSection = 0;
        const sections = document.querySelectorAll('.section');

        // عرض القسم التالي
        function nextSection() {
            if (currentSection < sections.length - 1) {
                sections[currentSection].style.display = 'none';
                currentSection++;
                sections[currentSection].style.display = 'block';
            }
        }

        // عرض القسم السابق
        function prevSection(index) {
            sections[currentSection].style.display = 'none';
            currentSection = index;
            sections[currentSection].style.display = 'block';
        }

        // بداية عرض قسم البريد الإلكتروني فقط
        sections.forEach((section, index) => {
            if (index !== 0) {
                section.style.display = 'none';
            }
        });
    </script>

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
        alert('Time is up! Your quiz has been submitted.');
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

        // Clear the timer from localStorage upon manual submission
        formElement.addEventListener("submit", function(event) {
        localStorage.removeItem(storageKey);
    });
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
