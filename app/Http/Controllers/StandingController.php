<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Result;
use Illuminate\Http\Request;

class StandingController extends Controller
{
    public function viewQuizStanding($id)
    {
        $quiz = Quiz::find($id);
        if(!$quiz){
            return redirect()->back();
        }
        // Get the latest attempt for each user for each quiz
        $results = Result::select('user_id', 'quiz_id', 'correct_answers', 'attempts','points')->where('quiz_id', $id)
            ->whereIn('id', function ($query) {
                $query->selectRaw('MAX(id)')
                    ->from('results')
                    ->groupBy('user_id', 'quiz_id');
            })
            ->with('user') // Assuming Result model has a relationship with User model
            ->orderBy('points', 'desc') // Sorting by score for standings
            ->get();

        $rowsCount = count($results);

        return view('website.quiz-standing', compact(['results', 'quiz', 'rowsCount']));
    }
}
