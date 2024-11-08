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
            <a href="{{route('quizzes')}}" id="loadMoreBtn" class="btn">Load More Quizzes</a>

        </div>
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
    </section>


    <!-- Contact Us Section -->
    <h1>Contact Us</h1>
    <section id="contact" class="contact-section">
        <div class="form-container">
            <img src="{{asset('images/contact.jpg')}}" class="contact-img" alt="">
            <form id="contact-form" action="{{route('contact.store')}}" method="POST">
                <p>We are happy to inform you of your problem and we will respond to you as soon as possible!</p>
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
                    {{-- <label for="message">Message</label> --}}
                    <textarea id="message" name="message" rows="5" required placeholder="message"></textarea>
                </div>
                <button type="submit" class="btn-contact">Send Message</button>
            </form>
        </div>
        <style>
            .contact-img{
                width: 60%;
                max-width: 600px;
                height: 400px;
                border-radius: 10px;
            }
            .contact-section{
                background-color: #f5f5f5;
            }
            .form-container{
                display: flex;
                justify-content: space-between;
                align-items:center;
                background-color: #f5f5f5;
                box-shadow: unset;
            }
            .form-container form{
                width: 30%;
                background-color: #f5f5f5;
                box-shadow: unset;

            }
            .form-container form p{
                font-size: 13px;
                text-align: left;
                margin-bottom: 30px;
            }
        </style>
    </section>
@endsection
