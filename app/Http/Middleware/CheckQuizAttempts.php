<?php

namespace App\Http\Middleware;

use App\Models\Quiz;
use App\Models\Result;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckQuizAttempts
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Get the quiz ID from the route
        $quizId = $request->route('quiz');

        // Find the quiz by ID
        $quiz = Quiz::find($quizId);
        if (!$quiz) {
            return redirect()->back();
        }

        // Get the authenticated user
        $user = Auth::user();

        // Check if the user has results for this quiz
        $results = $user->results()->where('quiz_id', $quizId)->get();

        // Calculate total attempts
        $totalAttempts = Result::where('quiz_id', $quizId)->where('user_id',$user->id)->count();

        // If attempts exceed or equal the quiz attempts limit, deny access
        if ($totalAttempts >= $quiz->attempts) {
            return redirect()->back()
                ->with('error', 'You have exhausted your attempts for this quiz.');
        }
        return $next($request);
    }
}
