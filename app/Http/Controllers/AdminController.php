<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Quizzer;
use App\Models\Result;
use App\Models\User;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index(){
        $user=Auth::user();
        $quizzer = Quizzer::where('user_id',$user->id)->first();
        if($user->role=='admin') {
            $userExaminees=Result::where('quizzer_id',$quizzer->id)->count();
            $userQuizzes=Quiz::where('quizzer_id',$quizzer->id)->count();
            $userActiveQuizzes=Quiz::where('quizzer_id',$quizzer->id)->where('is_published',1)->count();
            return view('admin.admin-dashboard',compact('userExaminees','userQuizzes','userActiveQuizzes'));
        }
        else{
            $examinees = Result::distinct()->pluck('user_id')->count();
            $users=User::all()->count();
            $quizzes=Quiz::all()->count();
            $activeQuizzes=Quiz::where('is_published',1)->count();
            $activities = UserActivity::with('user')->orderBy('created_at', 'desc')->get();
            return view('admin.super-admin-dashboard', compact('examinees','users','quizzes','activeQuizzes','activities'));
        }
    }
    public function viewUsers()
    {
        $users=User::all();
        return view('admin.users',compact('users'));
    }
}
