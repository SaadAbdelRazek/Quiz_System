@extends('admin.app.layout')
@section('content')
    <div class="container">
        <center>
            <h2>Your Quizzes Reports</h2>
        </center>
        <br><br>
        <div class="results-table mt-4">
            @if ($quizzes->isEmpty())
                <p>No students have taken this quiz yet.</p>
            @else
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Quiz Title</th>
                        <th>Download</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($quizzes as $quiz)
                        <tr>
                            <td>{{ $quiz->title }}</td>
                            <td><a href="{{route('quiz.report.download',$quiz->id)}}">Download</a></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
@endsection
