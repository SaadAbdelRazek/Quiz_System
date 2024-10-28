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
        return view('website.quiz-standing');
    }
}
