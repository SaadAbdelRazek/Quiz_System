<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You!</title>
    <link rel="stylesheet" href="{{asset('css/quiz-submit-thank.css')}}">
</head>
<body>
<div class="thank-you-container">
    <h1>Thank You!</h1>
    <p>We appreciate you taking the time to complete the quiz.</p>
    <p>Your responses have been recorded.</p>
    <div class="action-buttons">
        <button onclick="goToQuizzes()">Back to Quizzes</button>
    </div>
</div>

<script>
    function goToQuizzes() {
        window.location.href = '/home/quizzes'; // Replace with your actual quizzes URL
    }
</script>
</body>
</html>

