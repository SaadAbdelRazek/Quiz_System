<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(){
        $quizzes=Quiz::limit(4)->get();
        return view('website.index',compact('quizzes'));
    }

    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect()->back()->with('error', 'User not found.');
        }

        $user->delete();

        return redirect()->back()->with('success', 'User deleted successfully.');
    }

    public function toggleAdmin($id)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect()->back()->with('error', 'User not found.');
        }

        // Toggle admin status
        $user->role = $user->role === 'admin' ? 'user' : 'admin';
        $user->save();

        return redirect()->back()->with('success', 'User role updated successfully.');
    }
}
