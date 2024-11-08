@extends('website.app.layout')
@section('custom-css')
    <link rel="stylesheet" href="{{ asset('css/standing.css') }}">
@endsection
@section('content')

    <div class="standings-container">
        <h1>Quiz Standings</h1>
        <div class="quiz-info">
            <h2>{{ $quiz->title }}</h2>
            <p>{{ $quiz->subject }}</p>
        </div>
        <div class="standings-table">
            @if ($rowsCount)
                <div class="table-header">
                    <div class="header-rank">Rank</div>
                    <div class="header-name">Name</div>
                    <div class="header-score">Score</div>
                </div>
                @foreach ($results as $result)
                    <div class="table-row">
                        <div class="rank">
                            @if ($loop->iteration == 1)
                                {{ $loop->iteration }}<img src="{{ asset('images/standing/trophy.png') }}" alt="Gold Trophy"
                                    style="width: 20px; height: 20px;">
                            @elseif ($loop->iteration == 2)
                                {{ $loop->iteration }}<img src="{{ asset('images/standing/silver-cup.png') }}"
                                    alt="Silver Trophy" style="width: 20px; height: 20px;">
                            @elseif ($loop->iteration == 3)
                                {{ $loop->iteration }}<img src="{{ asset('images/standing/bronze-cup.png') }}"
                                    alt="Bronze Trophy" style="width: 20px; height: 20px;">
                            @else
                                {{ $loop->iteration }} <!-- ترقيم عادي لبقية المراكز -->
                            @endif
                        </div>


                        <div class="name">
                            @if ($result->user->profile_photo_path)
                                <img src="{{ asset('storage/' . $result->user->profile_photo_path) }}" alt=""
                                    style="width: 50px; height:50px; border-radius:50%">
                            @else
                                <img src="{{ asset('images/def.jpg') }}" alt=""
                                    style="width: 50px; height:50px; border-radius:50%">
                            @endif
                            {{ $result->user->name }}
                        </div>
                        <div class="score">{{ $result->points }}/{{ $quizPoints }}</div>
                    </div>
                @endforeach
            @else
                <div class="table-header">
                    <div class="header-rank">There is no quiz submits yet</div>
                </div>
            @endif
        </div>
    </div>


@endsection
