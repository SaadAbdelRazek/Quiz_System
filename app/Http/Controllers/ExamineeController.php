<?php

namespace App\Http\Controllers;

use App\Models\Result;
use App\Models\User;
use App\Models\Question;
use App\Models\Quizzer;
use App\Models\Quiz;

use Illuminate\Http\Request;

class ExamineeController extends Controller
{
    public function index($id = null){
        if($id){
            $state = 1;
            $examinees = Result::with('user')->where('quiz_id',$id)->get();
        }
        else{
            $quiz = Quiz::with('quizzer')->get();
            $state = 0;
            if(auth()->user()->role == 'SuperAdmin'){

                $examinees = Result::with(['user','quiz'])->get();
            }
            elseif(auth()->user()->role == 'admin'){
                $quizzer = Quizzer::where('user_id',auth()->user()->id)->first();

                $examinees = Result::with(['user','quiz','quizzer'])->where('quizzer_id', $quizzer->id)->get();
            }
        }
        return view('admin.admin-examinees',compact('examinees','state'));
    }
}
