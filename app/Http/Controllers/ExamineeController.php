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
    public function index($id = null) {
        $quizPoints = 0;
        $examinees = collect(); // تهيئة قائمة فارغة بشكل افتراضي

        if ($id) {
            // جلب النقاط إذا تم تمرير `$id`
            $quizPoints = Question::where('quiz_id', $id)->sum('points');
            $state = 1;
            $examinees = Result::with('user')->where('quiz_id', $id)->get();
            $allExaminees = null ;
        } else {
            $state = 0;
            $quiz = Quiz::with(['quizzer','questions'])->get();
            // $quizPoints = sum($quiz->questions);

            if (auth()->user()->role == 'SuperAdmin') {
                // جلب جميع المستجيبين لكل الكويزات
                $allExaminees = Result::with(['user', 'quiz'])->get();
                $quizzer = Quizzer::where('user_id', auth()->user()->id)->first();

                if ($quizzer) {
                    $examinees = Result::with(['user', 'quiz', 'quizzer'])->where('quizzer_id', $quizzer->id)->get();
                }
            } elseif (auth()->user()->role == 'admin') {
                // جلب الكويزر الخاص بالـ`admin` الحالي فقط
                $quizzer = Quizzer::where('user_id', auth()->user()->id)->first();
                $allExaminees = null ;

                if ($quizzer) {
                    $examinees = Result::with(['user', 'quiz', 'quizzer'])->where('quizzer_id', $quizzer->id)->get();
                }
            }
        }

        return view('admin.admin-examinees', compact('examinees', 'allExaminees', 'state', 'quizPoints'));
    }

}
