@extends('website.app.layout')
@section('content')


<section class="profile-section">
    <div class="container">
        <div class="profile-header">
            <img src="{{asset('storage/'. $user_data->profile_photo_path)}}" alt="{{ $user_data->name }}" alt="Profile Picture" class="profile-picture">
            <div class="user-info">
                <h1>{{$user->name}}</h1>
                <p>Email: {{$user->email}}</p>
                <button onclick="window.location.href='{{ url('/user/profile') }}'" class="edit-profile-btn">Edit Profile</button>
            </div>
        </div>

        <div class="profile-stats">
            <div class="stat-box">
                <h3>Completed Quizzes</h3>
                <p>{{$uniqueQuizCount}}</p>
            </div>
            <div class="stat-box">
                <h3>Highest Score</h3>
                <p>{{round($highestScore,2)}}%</p>
            </div>
            <div class="stat-box">
                <h3>Average Score</h3>
                <p>{{round($averageScore,2)}}%</p>
            </div>
        </div>

        <div class="quiz-activity">
            <h2>Recent Quiz Activity</h2>
            <ul class="quiz-list" id="quizList">
                @foreach($quizHistory as $index => $quiz)
                    <li class="quiz-item" style="display: {{ $index < 3 ? 'block' : 'none' }};">
                        <h3>{{$quiz->quiz->title}}</h3>
                        <p>Score: {{ round((($quiz->correct_answers / $quiz->total_questions)*100), 2) }}%</p>
                        <span>Completed on: {{$quiz->created_at->format('Y-m-d')}}</span>
                    </li>
                @endforeach
            </ul>
            @if(!$quizHistory)
            <a href="#" id="viewAllToggle" class="word-link" >View All</a>
            @else
                <p>Quiz History Is Empty</p>
            @endif
        </div>
    </div>
</section>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const viewAllToggle = document.getElementById("viewAllToggle");
        const quizItems = document.querySelectorAll(".quiz-item");
        let showingAll = false;

        viewAllToggle.addEventListener("click", function(e) {
            e.preventDefault();
            showingAll = !showingAll;

            quizItems.forEach((item, index) => {
                item.style.display = showingAll || index < 3 ? "block" : "none";
            });

            viewAllToggle.textContent = showingAll ? "View Less" : "View All";
        });
    });
</script>


@endsection
