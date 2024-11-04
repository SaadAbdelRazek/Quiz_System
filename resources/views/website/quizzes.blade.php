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
                        <p class="quiz-description">
                            <span style="color:deepskyblue;">Quiz</span><span style="color: gray">Quest</span>
                        </p>

                        @php
                            // الحصول على نتيجة الكويز الحالي للمستخدم الحالي (مثلاً باستخدام user_id)
                            $quizResult = $quiz->results->where('user_id', auth()->id())->first();
                        @endphp

                        {{-- عرض الأزرار بناءً على حالة المحاولات لكل كويز --}}
                        @if ($quizResult)
                            {{-- زر "Take Quiz" يظهر إذا كانت المحاولات أقل من الحد المسموح --}}
                            @if ($quizResult->attempts < $quiz->attempts)
                                <a href="{{ route('view-quiz', $quiz->id) }}" class="btn">Take Quiz</a>
                            @endif

                            {{-- زر "View Result" يظهر إذا كان هناك محاولات سابقة --}}
                            @if ($quizResult->attempts >= 1)
                                <a href="{{ route('view_quiz_result', $quiz->id) }}" style="background-color: green"
                                    class="btn">View Result</a>
                            @endif
                        @else
                            {{-- زر "Take Quiz" يظهر إذا لم تكن هناك محاولات سابقة --}}
                            <a href="{{ route('view-quiz', $quiz->id) }}" class="btn">Take Quiz</a>
                        @endif

                        {{-- زر "Standing" --}}
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

    <script>
        function searchQuizzes() {
            const query = document.getElementById('quizSearch').value.toLowerCase();
            const cards = document.querySelectorAll('.quiz-card');

            cards.forEach(card => {
                const title = card.getAttribute('data-title');
                if (title.includes(query)) {
                    card.style.display = 'block'; // Show matching cards
                } else {
                    card.style.display = 'none'; // Hide non-matching cards
                }
            });

            // Show "View All" button if there are matching cards
            const visibleCards = Array.from(cards).filter(card => card.style.display === 'block');
            document.getElementById('viewAllToggle').style.display = visibleCards.length > 6 ? 'block' : 'none';
        }

        // Optional: Toggle to show all quizzes when "View All" is clicked
        document.getElementById('viewAllToggle')?.addEventListener('click', function() {
            const cards = document.querySelectorAll('.quiz-card');
            cards.forEach(card => {
                card.style.display = 'block'; // Show all cards
            });
            this.style.display = 'none'; // Hide the "View All" button
        });
    </script>
@endsection
