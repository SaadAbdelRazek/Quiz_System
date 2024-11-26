@extends('website.app.layout')
@section('content')
    <section class="profile-section">
        <div class="container">
            <div class="profile-header" style="justify-content: space-between">
                <div style="display: flex; align-items:center">

                    @if ($user_data->profile_photo_path)
                        <img src="{{ asset('storage/' . $user_data->profile_photo_path) }}" alt="{{ $user_data->name }}"
                            class="profile-picture">
                    @else
                        <img src="{{ asset('images/def.jpg') }}" alt="{{ $user_data->name }}" class="profile-picture">
                    @endif
                    <div class="user-info">
                        <h1>{{ $user->name }}</h1>
                        <p>Email: {{ $user->email }}</p>
                    </div>
                </div>
                <div>

                    <button onclick="window.location.href='{{ url('/user/profile') }}'" class="edit-profile-btn">Edit
                        Profile</button>
                        @if ($user->role == 'admin' || $user->role == 'SuperAdmin')

                        <button onclick="window.location.href='{{ url('/admin-dashboard') }}'" class="edit-profile-btn">Dashboard</button>
                        @endif

                </div>
            </div>

            <div class="profile-stats">
                <div class="stat-box">
                    <h3>Completed Quizzes</h3>
                    <p>{{ $uniqueQuizCount }}</p>
                </div>
                <div class="stat-box">
                    <h3>Highest Score</h3>
                    <p>{{ round($highestScore, 2) }}%</p>
                </div>
                <div class="stat-box">
                    <h3>Average Score</h3>
                    <p>{{ round($averageScore, 2) }}%</p>
                </div>
            </div>

            <div class="quiz-activity">
                <h2 style="text-align: center">Recent Quiz Activity</h2>
                <ul class="quiz-list" id="quizList" style="text-align: left; margin-left:30px;">
                    @php $checkHistory=0; @endphp
                    @foreach ($quizHistory as $index => $quiz)
                        <li class="quiz-item" style="display: {{ $index < 3 ? 'block' : 'none' }};">
                            <div style="display: flex; align-items:center; justify-content:space-between">
                                <div>
                                    <h3>{{ $quiz->quiz->title }}</h3>
                                    <h4>created by: {{ $quiz->quizzer->user->name }}</h4>
                                    {{-- <p>Score: {{ round((($quiz->correct_answers / $quiz->total_questions)*100), 2) }}%</p> --}}
                                    <span>submitted on: {{ $quiz->created_at->format('Y-m-d h:m:s') }}</span>
                                </div>
                                <div>
                                    @if ($quiz->quiz->show_answers_after_submission == 1)
                                        <a href="{{ route('view_quiz_result', $quiz->quiz->id) }}" class="btn"
                                            style="padding:10px; ">quiz result</a>
                                    @elseif ($quiz->quiz->show_answers_after_submission == 0)
                                        <div style="width: 250px;">
                                            <p style="padding:10px;  color:rgb(168, 132, 60); ">Quiz results are hidden,
                                                contact the quizzer via email <a
                                                    href="mailto:{{ $quiz->quizzer->user->email }}">{{ $quiz->quizzer->user->email }}</a>
                                            </p>
                                        </div>
                                    @endif
                                </div>

                            </div>
                        </li>
                        @php $checkHistory++; @endphp
                    @endforeach
                </ul>
                @if ($checkHistory)
                    <a href="#" id="viewAllToggle" class="word-link">View All</a>
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
