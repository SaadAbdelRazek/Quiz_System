<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Results</title>
    <link rel="stylesheet" href="{{asset('css/quiz-submit-details.css')}}">
</head>
<body>
<div class="result-container">
    <h1>Quiz Result</h1>
    <div class="score-section">
        {{-- <p>Thank you for completing the quiz!</p> --}}
        <div class="performance">
            <p><strong>Total Questions:</strong> <span id="total-questions">{{$result->total_questions}}</span></p>
            <p><strong>Correct Answers:</strong> <span id="correct-answers">{{$result->correct_answers}}</span></p>
            <p><strong>Your Score:</strong> <span id="score">{{round(($result->correct_answers/$result->points)*100,2)}}%</span></p>
        </div>
        <p id="feedback-message">Great job! Keep up the good work!</p>
        <div class="action-buttons">
            <button onclick="retakeQuiz()">Retake Quiz</button>
            <button onclick="goToQuizzes()">Go to All Quizzes</button>
        </div>
    </div>
</div>
<script>
    function retakeQuiz() {
        window.location.href = '{{ route('view-quiz',$quiz->id)}}'; // Replace with actual retake quiz URL
    }

    function goToQuizzes() {
        window.location.href = '/home/quizzes'; // Replace with actual quizzes list URL
    }
</script>
</body>
</html>
