<!DOCTYPE html>
<html>
<head>
    <title>Quiz Report</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .quiz-details { margin-bottom: 20px; }
        .table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .table th, .table td { border: 1px solid #000; padding: 8px; text-align: left; }
        .table th { background-color: #f2f2f2; }
    </style>
</head>
<body>
<h2>Quiz Report</h2>
<div class="quiz-details">
    <h3>{{ $quiz->title }}</h3>
    <p><strong>Subject:</strong> {{ $quiz->subject }}</p>
    <p><strong>Description:</strong> {{ $quiz->description }}</p>
</div>

<h4>Student Results</h4>
@if ($results->isEmpty())
    <p>No students have taken this quiz yet.</p>
@else
    <table class="table">
        <thead>
        <tr>
            <th>Student Name</th>
            <th>Email</th>
            <th>Points</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($results as $result)
            <tr>
                <td>{{ $result->student_name }}</td>
                    @if ($result->email)
                    <td>{{ $result->email }}</td>
                    @else
                    <td>{{ $result->user->email }}</td>
                    @endif
                <td>{{ $result->points }}/{{$quizPoints}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endif
</body>
</html>
