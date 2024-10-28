<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AnswerController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\QuizzerController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\StandingController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [UserController::class, 'index'])->name('home');

Route::middleware([
    'auth:sanctum',
    'Admin',
    config('jetstream.auth_session'),
    'verified'
    ])->group(function () {
        // Route::get('/dashboard', function () {
            //     return view('dashboard');
            // })->name('dashboard');
            Route::get('/admin-dashboard', [AdminController::class, 'index'])->name('admin-dashboard');
            Route::get('/admin-create-quiz', [QuizController::class, 'viewQuizForm'])->name('view-create-quiz');
            Route::post('/create-quiz', [QuizController::class, 'createQuiz'])->name('create-quiz');
            Route::delete('/admin-delete-quiz/{id}', [QuizController::class, 'destroy'])->name('quiz.destroy');

            Route::get('/admin-view-users',[adminController::class, 'viewUsers'])->name('admin-view-users');
            Route::delete('/admin-view-users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
            Route::get('/admin-view-quizzes',[QuizController::class, 'adminViewQuizzes'])->name('admin-view-quizzes');
            Route::get('/admin-update-quiz/{id}/edit', [QuizController::class, 'edit'])->name('quizzes.edit');
            Route::put('/admin-update-quiz/{id}', [QuizController::class, 'update'])->name('quizzes.update');
            Route::put('/admin-update-quiz-activate/{id}', [QuizController::class, 'update_activate'])->name('update-quiz-activate');

});

Route::get('start_create_quiz/{user_id}', [QuizzerController::class, 'index'])->name('start_create_quiz');

//----------------------------------------------------------

Route::get('/users/toggle-admin/{id}', [UserController::class, 'toggleAdmin'])->name('users.toggleAdmin');

//---------------------------------------------------------
Route::get('/home/quizzes', [QuizController::class, 'viewAllQuizzes'])->name('quizzes');




//-----------------------------------------------------------------
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');


Route::middleware('auth')->group(function () {
    Route::get('/home/profile', [UserController::class, 'viewProfile'])->name('profile');
    Route::get('/home/quizzes/{id}', [QuizController::class, 'viewQuiz'])->name('view-quiz');
    Route::post('/quiz/{quiz}/submit', [QuizController::class, 'submitQuiz'])->name('quiz.submit');
    Route::get('/quiz/submit-details/{quizId}/{correctAnswers}/{totalQuestions}', [UserController::class, 'viewQuizSubmitDetails'])->name('quiz-submit-details');
    Route::get('/quiz/standing/{id}', [StandingController::class, 'viewQuizStanding'])->name('quiz-standing');
    Route::get('/quiz/submit-thank', [UserController::class, 'viewQuizThank'])->name('quiz-results');
});
Route::get('/search-quizzes', [QuizController::class, 'searchQuizzes']);
Route::get('/search', [SearchController::class, 'search'])->name('search');
