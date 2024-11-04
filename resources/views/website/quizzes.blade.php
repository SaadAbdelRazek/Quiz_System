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
        <div class="error-message alert alert-warning alert-dismissible fade show" style="" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" style="" data-bs-dismiss="alert" aria-label="Close">X</button>
        </div>
    @endif
    <script>
        function close() {

            var close = document.getElementById('error-message');
            close.style.display = 'none';
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
                    <div class="quiz-card" style="display: {{ $index < 6 ? 'block' : 'none' }}"
                        data-title="{{ strtolower($quiz->title) }}">
                        <div class="quiz-icon">
                            <i class="fas fa-question-circle"></i>
                        </div>
                        <h3>{{ $quiz->title }}</h3>
                        <p>{{ $quiz->subject }}</p>
                        <p class="quiz-description"><span style="color:deepskyblue;">Quiz</span><span
                                style="color: gray">Quest</span></p>
                                @auth

                            @php
                                $viewed = false; // متغير للتحقق إذا كان الزر قد تم عرضه بالفعل
                            @endphp

                            @foreach ($quiz->results as $result)
                            @if ($result->quiz->attempts < $quiz->attempts || !$result->quiz_id)
                            <a href="{{ route('view-quiz', $quiz->id) }}" class="btn">Take Quiz</a>
                            @endif
                                @if ($result->attempts >= 1 && !$viewed)
                                <a href="{{ route('view_quiz_result', $quiz->id) }}" style="background-color: green" class="btn">view result</a>

                                    @php
                                        $viewed = true; // تعيين المتغير لمنع عرض الزر مجددًا
                                    @endphp
                                @endif
                            @endforeach

                        @endauth


                        <a href="{{ route('quiz-standing', $quiz->id) }}" class="btn"
                            style="background-color: #636262">Standing</a>


                    </div>
                @endforeach
            </div>
            <br>
            @if ($index >= 6)
                <a href="#" id="viewAllToggle" class="btn">View All</a>
            @endif
        </div>
    </section>

    <script src="{{asset('js/search.js')}}"></script>
@endsection
