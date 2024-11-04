@extends('website.app.layout')
@section('custom-css')
    <link rel="stylesheet" href="{{asset('css/standing.css')}}">
@endsection
@section('content')

    <div class="standings-container">
        <h1>Quiz Standings</h1>
        <div class="quiz-info">
            <h2>{{$quiz->title}}</h2>
            <p>{{$quiz->subject}}</p>
        </div>
        <div class="standings-table">
            @if($rowsCount)
            <div class="table-header">
                <div class="header-rank">Rank</div>
                <div class="header-name">Name</div>
                <div class="header-score">Score</div>
            </div>
            @foreach($results as $result)
            <div class="table-row">
                <div class="rank">{{$loop->iteration}}</div>
                <div class="name">{{$result->user->name}}</div>
                <div class="score">{{$result->points}}</div>
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
