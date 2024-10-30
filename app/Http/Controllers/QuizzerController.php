<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Models\User;
use \App\Models\Quiz;
use \App\Models\Quizzer;
use Illuminate\Support\Facades\Auth;

class QuizzerController extends Controller
{
    public function index(Request $request)
{
    $userId = auth()->user()->id;

    // تحقق إذا كان المستخدم موجودًا مسبقًا كـ quizzer
    if (!Quizzer::where('user_id', $userId)->exists()) {
        $quizzer = Quizzer::create([
            'user_id' => $userId
        ]);

        // تغيير دور المستخدم إلى 'admin'
        $user = User::findOrFail($userId);
        $user->role = 'admin';
        $user->save();
    }

    // إعادة التوجيه إلى لوحة التحكم
    return redirect()->route('admin-dashboard')->with('status', 'You are now an admin and can create quizzes!');
}

}
