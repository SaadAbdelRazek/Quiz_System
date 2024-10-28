@extends('website.app.layout')
@section('content')
    <!-- Home Section -->
    <section id="home" class="home-section">
        <div class="cover-overlay">
            <div class="home-content">
                <h2>Welcome to QuizQuest</h2>
                <p>Your adventure into the world of quizzes begins here.</p>
                <a href="#quizzes" class="btn">Browse Quizzes</a>
                <a href="{{ route('start_create_quiz', ['user_id' => auth()->user()->id]) }}" class="btn">Create Quizzes</a>

            </div>
        </div>
    </section>

    <!-- About Us Section -->
    <section id="about" class="about-section">
        <div class="container">
            <h2>About Us</h2>
            <p>QuizQuest is an exciting platform offering quizzes across various topics. Our mission is to provide an enjoyable and engaging experience for learners and quiz enthusiasts. Whether you're looking for fun trivia or educational challenges, we've got something for everyone.</p>
        </div>
    </section>

    <!-- Quizzes Section -->
    <section id="quizzes" class="quiz-section">
        <div class="container">
            <h2>Featured Quizzes</h2>
            <div class="quiz-grid">
                @foreach($quizzes as $quiz)
                <div class="quiz-card">
                    <h3>{{$quiz->title}} Quiz</h3>
                    <p>{{$quiz->subject}}</p>
                    <button>Take Quiz</button>
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
            <form id="contact-form" action="#" method="POST">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="message">Message</label>
                    <textarea id="message" name="message" rows="5" required></textarea>
                </div>
                <button type="submit" class="btn-contact">Send Message</button>
            </form>
        </div>

    </section>
@endsection
