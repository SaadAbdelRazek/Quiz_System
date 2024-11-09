<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Question;
use App\Models\Result;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function viewReportsPage(){
        $userId = Auth::id();
        $user = User::with('quizzes')->find($userId);
        $quizzes = $user->quizzes;
        return view('admin.reports',compact('user','quizzes'));
    }
    public function downloadQuizReport($quizId)
    {
        $quiz = Quiz::findOrFail($quizId);
        $quizPoints = Question::where('quiz_id', $quizId)->sum('points');

        // Fetch results related to the quiz
        $results = Result::where('quiz_id', $quizId)
            ->join('users', 'results.user_id', '=', 'users.id')
            ->select('users.name as student_name', 'users.email as email' , 'results.points')
            ->get();

        // Load the view and pass data
        $pdf = Pdf::loadView('admin.quiz_report', [
            'quiz' => $quiz,
            'results' => $results,
            'quizPoints' => $quizPoints
        ]);

        // Download the generated PDF file
        return $pdf->download('quiz_report_' . $quiz->title . '.pdf');
    }
}
