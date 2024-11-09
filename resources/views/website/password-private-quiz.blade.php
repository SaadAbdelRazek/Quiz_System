
@if (session('error'))
    <div id="error-message" class="error-message alert alert-warning alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" onclick="closeAlert()" aria-label="Close">X</button>
    </div>
@endif

<div class="quiz-container">
    <form action="{{ route('private_quiz', $quiz->id) }}" method="POST" class="quiz-form">
        <h3>Quiz : {{$quiz->title}}</h3>
        @csrf
        <div class="input-group">

            <input type="password" name="password" placeholder="Enter Quiz Password" class="form-control" required>
            <button type="submit" class="submit-btn">Submit</button>
        </div>
    </form>
</div>

<style>
    body{
        background: linear-gradient(135deg, #6a82fb, #fc5c7d);
    }
    .quiz-container {
        max-width: 400px;
        margin: 50px auto;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        background-color: #ffffff;
    }

    .quiz-form {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .input-group {
        display: flex;
        flex-direction: column;
        gap: 10px;
        width: 100%;
    }

    .form-control {
        flex: 1;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px 0 0 5px;
        outline: none;
        font-size: 16px;
    }

    .submit-btn {
        background-color: #007bff;
        color: #fff;
        border: none;
        padding: 10px 20px;
        border-radius: 0 5px 5px 0;
        cursor: pointer;
        font-size: 16px;
        transition: background-color 0.3s;
    }

    .submit-btn:hover {
        background-color: #0056b3;
    }
</style>
