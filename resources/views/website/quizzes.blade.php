@extends('website.app.layout')
@section('custom-css')
    <link rel="stylesheet" href="{{ asset('css/quizzes.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
        integrity="sha384-k6RqeWeci5ZR/Lv4MR0sA0FfDOMg6B4XrA9RjZbgc1b4E5V1P3Xr8U32ZgwtFfS" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@endsection
@section('content')
    @if (session('success'))
        <div class="success-message alert alert-warning alert-dismissible fade show" style="" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" style="" data-bs-dismiss="alert" aria-label="Close">X</button>
        </div>
    @endif

    @if (session('error'))
    <div id="error-message" class="error-message alert alert-warning alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" onclick="closeAlert()" aria-label="Close">X</button>
    </div>
@endif
<script>
    function closeAlert() {
        var close = document.getElementById('error-message');
        if (close) {
            close.style.display = 'none';
        }
    }
</script>
    <br>
    <div class="search-container">
        <h1>Available Quizzes</h1>
        <input type="text" id="quizSearch" placeholder="Search for quizzes..." onkeyup="searchQuizzes()">
    </div>

    <section id="quizzes" class="quiz-section">
        <div class="container">
            <div class="quiz-grid" id="quizGrid">
                @foreach ($quizzes as $index => $quiz)
                    <div class="quiz-card" style="display: {{ $index < 6 ? 'block' : 'none' }}; margin-bottom: 20px;"
                        data-title="{{ strtolower($quiz->title) }}">
                        <div class="quiz-icon">
                            <i class="fas fa-question-circle"></i>
                        </div>
                        <h3>{{ $quiz->title }}</h3>
                        <p>{{ $quiz->subject }}</p>
                        {{-- <p class="quiz-description">
                            <span style="color: deepskyblue;">Quiz</span> <span style="color: gray">Quest</span>
                        </p> --}}
                        <p class="quiz-owner"><strong>Quizzer:</strong> {{ $quiz->quizzer->user->name }}</p>
                        <p class="quiz-time"><strong>Time Limit:</strong> {{ $quiz->duration }} minutes</p>
                        <p class="quiz-attempts"><strong>Attempts Allowed:</strong> {{ $quiz->attempts }}</p>

                        @php
                            // الحصول على نتيجة الكويز الحالي للمستخدم الحالي (مثلاً باستخدام user_id)
                            $quizResult = $quiz->results->where('user_id', auth()->id())->first();
                        @endphp

                        {{-- عرض الأزرار بناءً على حالة المحاولات لكل كويز --}}
                        @if ($quizResult)
                            <p class="quiz-attempts"><strong>your attempts:</strong>{{ $quizResult->attempts }}</p>
                            {{-- زر "Take Quiz" يظهر إذا كانت المحاولات أقل من الحد المسموح --}}
                            @if ($quizResult->attempts < $quiz->attempts)
                                <a href="{{ route('view-quiz', $quiz->id) }}" class="btn">Take Quiz</a>
                            @elseif($quizResult->attempts == $quiz->attempts)
                                <p style="color: rgb(211, 137, 10)">you has exceeded tha max attempts, thank you for your
                                    response</p>
                            @endif

                            {{-- زر "View Result" يظهر إذا كان هناك محاولات سابقة --}}
                            @if ($quizResult->attempts >= 1)
                                <a href="{{ route('view_quiz_result', $quiz->id) }}" class="btn result-btn">View Result</a>
                                @if ($quiz->show_answers_after_submission == 0)
                                    <i class="fas fa-solid fa-circle-exclamation"
                                        title="result is hidden by the quizzer"></i>
                                @endif
                            @endif
                        @else
                            {{-- زر "Take Quiz" يظهر إذا لم تكن هناك محاولات سابقة --}}
                            <a href="{{ route('view-quiz', $quiz->id) }}" class="btn">Take Quiz</a>
                        @endif

                        {{-- زر "Standing" --}}
                        @if ($quiz->show_answers_after_submission == 1)
                            <a href="{{ route('quiz-standing', $quiz->id) }}" class="btn standing-btn">Standing</a>
                        @endif
                    </div>
                @endforeach
            </div>
            <br>
            @if ($index >= 6)
                <a href="#" id="viewAllToggle" class="btn">View All</a>
            @endif
        </div>
    </section>

    <style>
        .quiz-icon {
            font-size: 40px;
            color: deepskyblue;
            margin-bottom: 10px;
        }

        .quiz-description {
            margin: 10px 0;
        }

        .quiz-owner,
        .quiz-time,
        .quiz-attempts {
            font-size: 0.9em;
            color: #555;
            margin: 5px 0;
        }

        .btn {
            display: inline-block;
            margin-top: 10px;
            padding: 10px 15px;
            border-radius: 5px;
            color: white;
            text-decoration: none;
            background-color: #007bff;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .result-btn {
            background-color: green;
        }

        .result-btn:hover {
            background-color: darkgreen;
        }

        .standing-btn {
            background-color: #636262;
        }

        .standing-btn:hover {
            background-color: #505050;
        }
    </style>


    <script src="{{ asset('js/search.js') }}"></script>
@endsection
