<?php

namespace App\Http\Controllers;

use App\Models\Result;
use App\Models\User;
use App\Models\Question;
use App\Models\Quizzer;

use Illuminate\Http\Request;

class ExamineeController extends Controller
{
    public function index($id = null){
        if($id){
            $state = 1;
            $examinees = Result::with('user')->where('quiz_id',$id)->get();
        }
        else{
            $state = 0;
            $examinees = Result::with(['user','quiz'])->where('quizzer');
        }
        return view('admin.admin-examinees',compact('examinees','state'));
    }
}
