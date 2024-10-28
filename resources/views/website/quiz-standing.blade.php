@extends('website.app.layout')
@section('custom-css')
    <link rel="stylesheet" href="{{asset('css/standing.css')}}">
@endsection
@section('content')

    <div class="standings-container">
        <h1>Quiz Standings</h1>
        <div class="quiz-info">
            <h2>Quiz Title</h2>
            <p>Description of the quiz and current standings</p>
        </div>
        <div class="standings-table">
            <div class="table-header">
                <div class="header-rank">Rank</div>
                <div class="header-name">Name</div>
                <div class="header-score">Score</div>
            </div>
            <div class="table-row">
                <div class="rank">1</div>
                <div class="name">Alice</div>
                <div class="score">95</div>
            </div>
            <div class="table-row">
                <div class="rank">2</div>
                <div class="name">Bob</div>
                <div class="score">90</div>
            </div>
            <!-- Add more rows as needed -->
        </div>
    </div>


@endsection
