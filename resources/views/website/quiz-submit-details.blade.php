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
    <h1>Quiz Results</h1>
    <div class="score-section">
        <p>Thank you for completing the quiz!</p>
        <div class="performance">
            <p><strong>Total Questions:</strong> <span id="total-questions">{{$totalQuestions}}</span></p>
            <p><strong>Correct Answers:</strong> <span id="correct-answers">{{$correctAnswers}}</span></p>
            <p><strong>Your Score:</strong> <span id="score">{{round((($correctAnswers/$totalQuestions)*100),2)}}%</span></p>
        </div>
        <p id="feedback-message">Great job! Keep up the good work!</p>
        <div class="action-buttons">
            @if(!$quizData->attempts == 1 && $userAttepts->attempts <= $quizData->attempts )
                <button onclick="retakeQuiz()">Retake Quiz</button>
            @endif
            <button onclick="goToQuizzes()">Go to All Quizzes</button>
        </div>
    </div>
</div>
<script>
    function retakeQuiz() {
        window.location.href = '{{ route('view-quiz',$quizId)}}'; // Replace with actual retake quiz URL
    }

    function goToQuizzes() {
        window.location.href = '/home/quizzes'; // Replace with actual quizzes list URL
    }
</script>
</body>
</html>
