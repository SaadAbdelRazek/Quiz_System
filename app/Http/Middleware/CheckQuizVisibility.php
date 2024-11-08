<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Quiz;

class CheckQuizVisibility
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Get quiz ID from the route parameter
        $quizId = $request->route('quiz');

        // Find the quiz by ID
        $quiz = Quiz::find($quizId);

        // Check if the quiz exists and its visibility is private
        if ($quiz && $quiz->visibility === 'private' && $quiz->quizzer->user_id != auth()->id()) {
            // If the quiz is private and the user is not the owner, deny access
            return redirect()->route('quizzes')->with('error', 'This quiz is private and cannot be accessed.');
        }

        return $next($request);
    }
}
