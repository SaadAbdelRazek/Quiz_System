<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\QuizzerController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\StandingController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ExamineeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

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


// Show the verification notice view
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

// Verify the email
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill(); // Mark email as verified
    return redirect('/'); // Redirect to the dashboard after verification
})->middleware(['auth', 'signed'])->name('verification.verify');

// Resend the verification email
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.resend');

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

            Route::get('/admin-view-examinees/{id?}', [ExamineeController::class, 'index'])->name('admin-view-examinees');
            Route::get('admin-view-contacts', [ContactController::class, 'viewUserMessages'])->name('admin-view-contacts');

    Route::get('/quiz-report/download/{id}', [ReportController::class, 'downloadQuizReport'])->name('quiz.report.download');
    Route::get('/admin/reports', [ReportController::class, 'viewReportsPage'])->name('reports');
    Route::get('/admin/add-questions/{quizId}', [QuestionController::class, 'addQuestionsView'])->name('questions.add.view');
    Route::post('/admin/{id}/add-questions', [QuestionController::class, 'addQuestions'])->name('questions.add');
});

Route::get('start_create_quiz/{user_id}', [QuizzerController::class, 'index'])->name('start_create_quiz')->middleware(['auth', 'verified']);

//----------------------------------------------------------

Route::get('/users/toggle-admin/{id}', [UserController::class, 'toggleAdmin'])->name('users.toggleAdmin')->middleware(['auth', 'verified']);

//---------------------------------------------------------
Route::get('/home/quizzes', [QuizController::class, 'viewAllQuizzes'])->name('quizzes')->middleware(['auth', 'verified']);




//-----------------------------------------------------------------

Route::middleware(['auth','verified'])->group(function () {
    Route::get('/home/profile', [UserController::class, 'viewProfile'])->name('profile');
    Route::get('/home/quizzes/{id}', [QuizController::class, 'viewQuiz'])->name('view-quiz')->middleware('check.quiz.attempts');
    Route::get('/quiz/submit-details/{quizId}/{correctAnswers}/{totalQuestions}', [UserController::class, 'viewQuizSubmitDetails'])->name('quiz-submit-details');
    Route::get('/quiz/standing/{id}', [StandingController::class, 'viewQuizStanding'])->name('quiz-standing');
    Route::get('/quiz/submit-thank', [UserController::class, 'viewQuizThank'])->name('quiz-results');

    Route::get('/quiz/view-quiz-result/{id}', [UserController::class, 'view_quiz_result_attempts'])->name('view_quiz_result');

});


Route::get('quiz/check-password/{access}', [QuizController::class, 'submit_password_private_quiz'])->name('quiz_password')->middleware(['auth', 'verified']); // enter the quiz password
Route::post('quiz/private/{id}', [QuizController::class, 'view_private_quiz'])->name('private_quiz')->middleware(['auth', 'verified']);

Route::get('/search-quizzes', [QuizController::class, 'searchQuizzes'])->middleware(['auth', 'verified']);
Route::get('/search', [SearchController::class, 'search'])->name('search')->middleware(['auth', 'verified']);
Route::post('/contact', [ContactController::class, 'store'])->middleware('handleRouteErrors')->name('contact.store')->middleware(['auth', 'verified']);
Route::post('/quiz/{quiz}/submit', [QuizController::class, 'submitQuiz'])->middleware('handleRouteErrors')->middleware(['auth', 'verified'])->name('quiz.submit');


Route::get('/refresh-session', function () {
    return response()->json(['token' => csrf_token()]);
});

Route::get('/keep-alive', function () {
    return response()->json(['status' => 'Session kept alive']);
})->name('keep-alive');

