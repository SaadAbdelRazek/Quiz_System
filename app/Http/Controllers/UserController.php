<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Result;
use App\Models\User;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(){
        $quizzes=Quiz::where('is_published',1)->limit(4)->get();
        return view('website.index',compact('quizzes'));
    }

    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect()->back()->with('error', 'User not found.');
        }

        $user->delete();
        UserActivity::create([
            'user_id' => auth()->id(),
            'activity' => 'Delete Account',
        ]);

        return redirect()->back()->with('success', 'User deleted successfully.');
    }

    public function toggleAdmin($id)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect()->back()->with('error', 'User not found.');
        }

        // Toggle admin status
        $user->role = $user->role === 'admin' ? 'user' : 'admin';
        UserActivity::create([
            'user_id' => auth()->id(),
            'activity' => 'Someone role changed',
        ]);
        $user->save();

        return redirect()->back()->with('success', 'User role updated successfully.');
    }

    public function viewProfile()
    {
        $user = Auth::user();
        $quizHistory=Result::with('quiz')->where('user_id',$user->id)->get();
        $uniqueQuizCount = Result::where('user_id', $user->id)
            ->distinct('quiz_id')
            ->count('quiz_id');

        $highestScore = Result::where('user_id', $user->id)
            ->selectRaw('MAX(correct_answers / total_questions * 100) as max_score')
            ->value('max_score');

        $averageScore = Result::where('user_id', $user->id)
            ->selectRaw('AVG(correct_answers / total_questions * 100) as average_score')
            ->value('average_score');

        return view('website.user-profile',compact('user','quizHistory','uniqueQuizCount','highestScore','averageScore'));
    }

    public function viewQuizSubmitDetails()
    {
        return view('website.quiz-submit-details');
    }
    public function viewQuizThank(){
        return view('website.quiz-submit-thank');
    }

    public function view_quiz_result_attempts($id)
{
    $user = auth()->user();
    $quiz = Quiz::with(['questions.answers'])->findOrFail($id);
    $result = Result::where('user_id', $user->id)->where('quiz_id', $id)->first();
    $totalPoints = $quiz->questions->sum('points');
    return view('website.quiz-all-result-attempts', compact('quiz', 'result','totalPoints'));
}



}
