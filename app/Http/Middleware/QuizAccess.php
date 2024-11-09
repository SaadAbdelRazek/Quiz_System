<?php

namespace App\Http\Middleware;
use App\Models\Quiz;
use App\Models\Result;
use Illuminate\Support\Facades\Auth;

use Closure;
use Illuminate\Http\Request;

class QuizAccess
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
        $quizId = $request->route('id');
        $routeName = $request->route()->getName();
        $quiz = Quiz::findOrFail($quizId);
        $quizResult = Result::where('quiz_id',$quizId)->where('user_id',auth()->user()->id)->first();
        if($routeName == 'quiz-standing'){

            if($quiz->show_answers_after_submission == 0){
                return redirect()->back();
            }
            return $next($request);
        }

        if($routeName == 'view_quiz_result'){

            if($quizResult && $quizResult->attempts >= 1 && $quiz->show_answers_after_submission == 1){
                return $next($request);
            }
            return redirect()->back();
        }

        // if($routeName == )
    }
}
