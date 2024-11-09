@extends('website.app.layout')
@section('custom-css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/quizzes.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
          integrity="sha384-k6RqeWeci5ZR/Lv4MR0sA0FfDOMg6B4XrA9RjZbgc1b4E5V1P3Xr8U32ZgwtFfS" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@endsection
@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">X</button>
        </div>
    @endif

    @if (session('error'))
    <div id="error-message" class="error-message alert alert-warning alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" onclick="closeAlert()" aria-label="Close">X</button>
    </div>
@endif

    <style>
        .result-container {
            width: 50%;
            margin: auto;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 40px;
            font-family: Arial, sans-serif;
        }

        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }
        h2{
            font-size: 23px;
        }

        .score {
            font-size: 1.5em;
            margin: 20px 0;
            text-align: center;
            color: #4caf50;
            /* لون أخضر */
        }

        .result-details {
            margin-top: 20px;
            text-align: center;
            font-size: 1.1em;
        }

        .action-buttons {
            display: flex;
            justify-content: center;
            margin-top: 30px;
        }

        .action-buttons button {
            background: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 15px;
            cursor: pointer;
            margin: 0 10px;
            transition: background 0.3s;
        }

        .action-buttons button:hover {
            background: #0056b3;
        }

        #detailed-answers {
            margin-top: 20px;
            padding: 15px;
            border: 1px solid #007bff;
            border-radius: 5px;
            background-color: #f1f8ff;
        }

        .question-detail {
            border-bottom: 1px solid #ccc;
            padding: 10px 0;
        }

        .question-detail:last-child {
            border-bottom: none;
            /* إزالة الحدود السفلية للسؤال الأخير */
        }

        .question-detail p {
            margin: 5px 0;
        }

        .question-detail strong {
            color: #333;
        }
    </style>

    <div class="result-container">
        <h1>Quiz Results for "{{ $quiz->title }}"</h1>
        @if ($quiz->show_answers_after_submission == 1)
            <div class="score">
                @php
                    $percentage = $result->points / $totalPoints; // حساب النسبة
                @endphp

                @if ($percentage <= 0.50)
                    <h2 style="color:red;">Your Score: <span style="border-radius: 50%; border:1px solid gray">{{ $result->points }} / {{ $totalPoints }}</span></h2>
                @elseif ($percentage >= 0.75 && $percentage < 0.90) <!-- تصحيح النسبة هنا -->
                <h2>Your Score: <span style="border-radius: 50%; border:1px solid gray">{{ $result->points }} / {{ $totalPoints }}</span></h2>
                @elseif ($percentage >= 0.90)
                    <h2 style="color: blue;">Your Score: <span style="border-radius:5px; padding:1px; border-bottom:2px solid blue; ">{{ $result->points }} / {{ $totalPoints }}</span></h2>
                @endif
            </div>

            <div class="result-details">
                <p><strong>Total Questions in Quiz:</strong> {{ $quiz->questions->count() }}</p>
                <p><strong>Correct Answers:</strong> {{ $result->correct_answers }}</p>
            </div>
        @else
            <p>Your response has been submitted, wait until the admin sends you the result.</p>
        @endif




        <div class="action-buttons">
            @if (!$result->attempts > $quiz->attempts)
                <button onclick="retakeQuiz()">Retake Quiz</button>
            @else
                <p>You have exceeded the quiz retake limit</p>
            @endif
            <button onclick="goToQuizzes()">Go to All Quizzes</button>
        </div>

        @if ($result->attempts > $quiz->attempts)
            <p style="margin-top: 10px; width:100%; text-align:center; color: rgb(209, 12, 12);">You have exceeded the quiz retake limit
            </p>
            <p style="width:100%; text-align:center; font-size:12px; color:rgb(17, 169, 215)">Thank you for your effort</p>
        @endif

        <!-- Button to toggle the detailed answers -->
        @if ($quiz->show_answers_after_submission == 1)
            <button onclick="toggleDetails()"
                    style="margin-top: 20px; background-color: #007bff; color: white; padding: 10px; border: none; border-radius: 5px; cursor: pointer; width:100%">
                Show/Hide Correct Answers
            </button>

            <!-- Hidden section for detailed answers -->
            <div id="detailed-answers" style="display:none;">
                <h3>quiz Answers</h3>
                @foreach ($quiz->questions as $question)
                    <div class="question-detail">
                        <p><strong>Question:</strong> {{ $question->question_text }}</p>

                        <p><strong>Options:</strong></p>
                        <ul style="margin-left:10px">
                            @foreach ($question->answers as $answer)
                                <li>
                                    <p>
                                        <strong>{{ $answer->answer_text }}</strong>
                                        @if ($answer->is_correct)
                                            <span style="color: green;">(Correct Answer)</span>
                                        @endif
                                    </p>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach

            </div>
        @endif

    </div>

    <script>
        function retakeQuiz() {
            window.location.href = '{{ route('view-quiz', $quiz->id) }}';
        }

        function goToQuizzes() {
            window.location.href = '{{ route('quizzes') }}';
        }

        function toggleDetails() {
            var details = document.getElementById('detailed-answers');
            details.style.display = (details.style.display === 'none') ? 'block' : 'none';
        }
    </script>
    @endsection

    </html>
