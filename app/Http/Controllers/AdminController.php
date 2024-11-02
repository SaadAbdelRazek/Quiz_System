<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Result;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index(){
        $user=Auth::user();
        if($user->role=='admin') {
            $userExaminees=Result::where('quizzer_id',$user->id)->count();
            $userQuizzes=Quiz::where('quizzer_id',$user->id)->count();
            $userActiveQuizzes=Quiz::where('quizzer_id',$user->id)->where('is_published',1)->count();
            return view('admin.admin-dashboard',compact('userExaminees','userQuizzes','userActiveQuizzes'));
        }
        else{
            $examinees = Result::distinct()->pluck('user_id')->count();
            $users=User::all()->count();
            $quizzes=Quiz::all()->count();
            $activeQuizzes=Quiz::where('is_published',1)->count();
            return view('admin.super-admin-dashboard', compact('examinees','users','quizzes','activeQuizzes'));
        }
    }
    public function viewUsers()
    {
        $users=User::all();
        return view('admin.users',compact('users'));
    }
}
