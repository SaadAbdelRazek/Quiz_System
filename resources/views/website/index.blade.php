@extends('website.app.layout')
@section('content')
@if (session('error'))
    <div id="error-message" class="error-message alert alert-warning alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" onclick="closeAlert()" aria-label="Close">X</button>
    </div>
@endif



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
                    <div class="quiz-card" style="display: {{ $index < 6 ? 'block' : 'none' }}; margin-bottom: 20px;" data-title="{{ strtolower($quiz->title) }}">
                        <div class="quiz-icon">
                            <i class="fas fa-question-circle"></i>
                        </div>
                        <h3>{{ $quiz->title }}</h3>
                        <p>{{ $quiz->subject }}</p>
                        <p class="quiz-owner"><strong>Quizzer:</strong> {{ $quiz->quizzer->user->name }}</p>
                        <p class="quiz-time"><strong>Time Limit:</strong> {{ $quiz->duration }} minutes</p>
                        <p class="quiz-attempts"><strong>Attempts Allowed:</strong> {{ $quiz->attempts }}</p>

                        @php
                            $quizResult = $quiz->results->where('user_id', auth()->id())->first();
                        @endphp

                        @if ($quizResult)
                            <p class="quiz-attempts"><strong>Your Attempts:</strong> {{ $quizResult->attempts }}</p>
                            @if ($quizResult->attempts < $quiz->attempts)
                                <a href="{{ route('view-quiz', $quiz->id) }}" class="btn">Take Quiz</a>
                            @elseif ($quizResult->attempts == $quiz->attempts)
                                <p style="color: rgb(211, 137, 10)">You have exceeded the max attempts. Thank you for your response.</p>
                            @endif

                            @if ($quizResult->attempts >= 1)
                                <a href="{{ route('view_quiz_result', $quiz->id) }}" class="btn result-btn">View Result</a>
                                @if ($quiz->show_answers_after_submission == 0)
                                    <i class="fas fa-solid fa-circle-exclamation" title="Result is hidden by the quizzer"></i>
                                @endif
                            @endif
                        @else
                            <a href="{{ route('view-quiz', $quiz->id) }}" class="btn">Take Quiz</a>
                        @endif

                        @if ($quiz->show_answers_after_submission == 1)
                            <a href="{{ route('quiz-standing', $quiz->id) }}" class="btn standing-btn">Standing</a>
                        @endif
                    </div>
                @endforeach
            </div>
            <a href="{{ route('quizzes') }}" id="loadMoreBtn" class="btn">Load More Quizzes</a>
        </div>

        <style>
            /* Container for the whole quizzes section */
            .quiz-section {
                padding: 20px;
                background-color: #f9f9f9;
            }

            /* Grid for the quiz cards */
            .quiz-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
                gap: 20px;
                justify-items: center;
            }

            /* Individual quiz card */
            .quiz-card {
                background-color: #fff;
                padding: 20px;
                border-radius: 10px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                width: 100%;
                max-width: 300px; /* Make sure quiz card is responsive */
                text-align: center;
            }

            .quiz-icon {
                font-size: 40px;
                color: deepskyblue;
                margin-bottom: 10px;
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

            /* Load more button */
            #loadMoreBtn {
                display: inline-block;
                margin-top: 20px;
                padding: 10px 20px;
                background-color: #007bff;
                color: white;
                text-decoration: none;
                border-radius: 5px;
            }

            #loadMoreBtn:hover {
                background-color: #0056b3;
            }

            /* Responsive Styles for mobile and tablets */
            @media (max-width: 768px) {
                .quiz-grid {
                    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
                }

                .quiz-card {
                    padding: 15px;
                    max-width: 100%; /* Ensure quiz cards take up full width on smaller screens */
                }
            }

            @media (max-width: 480px) {
                .quiz-card {
                    padding: 10px;
                    max-width: 100%; /* Full width for mobile devices */
                }

                .btn {
                    width: 100%; /* Make buttons full width on smaller screens */
                    padding: 12px;
                }
            }
        </style>
    </section>


    <!-- Contact Us Section -->
    <h1>Contact Us</h1>
    <section id="contact" class="contact-section">
        <div class="form-container">
            <img src="{{ asset('images/contact.jpg') }}" class="contact-img" alt="Contact Image">
            <form id="contact-form" action="{{ route('contact.store') }}" method="POST">
                <p>We are happy to inform you of your problem and we will respond to you as soon as possible!</p>
                @csrf
                @if (!Auth::check())
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
                    <textarea id="message" name="message" rows="5" required placeholder="Message"></textarea>
                </div>
                <button type="submit" class="btn-contact">Send Message</button>
            </form>
        </div>
    </section>

    <style>
        /* Contact Section Styles */
        .contact-section {
            background-color: #f5f5f5;
            padding: 40px 10px;
        }

        .form-container {
            display: flex;
            flex-wrap: wrap; /* Allow for wrapping on small screens */
            justify-content: space-between;
            align-items: flex-start;
            gap: 20px; /* Add space between image and form */
        }

        /* Image Styles */
        .contact-img {
            width: 100%;
            max-width: 500px; /* Max width for image */
            height: auto;
            border-radius: 10px;
            object-fit: cover; /* Ensure the image fits well */
        }

        /* Form Styles */
        form {
            width: 100%;
            max-width: 500px; /* Max width for the form */
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        form p {
            font-size: 14px;
            text-align: left;
            margin-bottom: 20px;
        }

        /* Form Group Styles */
        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            margin-bottom: 5px;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        /* Button Styles */
        .btn-contact {
            background-color: #4991cf;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        .btn-contact:hover {
            background-color: #45a049;
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .form-container {
                flex-direction: column; /* Stack the image and form on smaller screens */
                align-items: center;
            }

            .contact-img {
                max-width: 100%; /* Ensure the image takes full width */
            }

            form {
                max-width: 90%; /* Adjust form width for smaller screens */
            }
        }

        @media (max-width: 480px) {
            form {
                width: 100%; /* Ensure form takes full width on very small screens */
            }
        }
    </style>

@endsection
