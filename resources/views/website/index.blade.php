@extends('website.app.layout')
@section('content')
    <!-- Home Section -->
    <section id="home" class="home-section">
        <div class="cover-overlay">
            <div class="home-content">
                <h2>Welcome to QuizQuest</h2>
                <p>Your adventure into the world of quizzes begins here.</p>
                <a href="#quizzes" class="btn">Browse Quizzes</a>
                @if (auth()->user())

                    <a href="{{ route('start_create_quiz', ['user_id' => auth()->user()->id]) }}" class="btn">Create Quizzes</a>
                @endif

            </div>
        </div>
    </section>

    <style>
        .btn-quiz{
            padding: 5px;
        }
    </style>

    <!-- About Us Section -->
    <section id="about" class="about-section">
        <div class="container">
            <h2>About Us</h2>
            <p>
                QuizQuest is an exciting platform offering quizzes across various topics. Our mission is to provide an enjoyable and engaging experience for learners and quiz enthusiasts. Whether you're looking for fun trivia or educational challenges, we've got something for everyone.
            </p>
            <div class="features">
                <div class="feature-item">
                    <h3>Wide Variety of Quizzes</h3>
                    <p>Explore quizzes on topics from science to pop culture, and everything in between!</p>
                </div>
                <div class="feature-item">
                    <h3>Engaging Learning</h3>
                    <p>Learn while having fun! Our quizzes are designed to be interactive and educational.</p>
                </div>
                <div class="feature-item">
                    <h3>Community Driven</h3>
                    <p>Join a community of quiz lovers, share your own quizzes, and challenge your friends.</p>
                </div>
            </div>
        </div>
    </section>


    <!-- Quizzes Section -->
    <section id="quizzes" class="quiz-section">
        <div class="container">
            <h2>Featured Quizzes</h2>
            <div class="quiz-grid">
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
                                <a href="{{ route('view-quiz', $quiz->id) }}" class="btn btn-quiz">Take Quiz</a>
                            @endif

                            {{-- زر "View Result" يظهر إذا كان هناك محاولات سابقة --}}
                            @if ($quizResult->attempts >= 1)
                                <a href="{{ route('view_quiz_result', $quiz->id) }}" style="background-color: green"
                                   class="btn btn-quiz">View Result</a>
                            @endif
                        @else
                            {{-- زر "Take Quiz" يظهر إذا لم تكن هناك محاولات سابقة --}}
                            <a href="{{ route('view-quiz', $quiz->id) }}" class="btn btn-quiz">Take Quiz</a>
                        @endif

                        {{-- زر "Standing" --}}
                        <a href="{{ route('quiz-standing', $quiz->id) }}" class="btn btn-quiz"
                           style="background-color: #636262">Standing</a>
                    </div>
                @endforeach
            </div>
            <a href="{{route('quizzes')}}" id="loadMoreBtn" class="btn">Load More Quizzes</a>
        </div>
    </section>

    <!-- Contact Us Section -->
    <section id="contact" class="contact-section">
        <div class="form-container">
            <h1>Contact Us</h1>
            <form id="contact-form" action="{{route('contact.store')}}" method="POST">
                @csrf
                @if(!Auth::check())
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                @endif
                <div class="form-group">
                    <label for="message">Message</label>
                    <textarea id="message" name="message" rows="5" required></textarea>
                </div>
                <button type="submit" class="btn-contact">Send Message</button>
            </form>
        </div>

    </section>
@endsection
