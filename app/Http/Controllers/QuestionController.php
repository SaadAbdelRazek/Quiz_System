<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function create($quizId)
    {
        $quiz = Quiz::findOrFail($quizId);
        return view('questions.create', compact('quiz'));
    }

    public function store(Request $request, $quizId)
    {
        $validatedData = $request->validate([
            'question_text' => 'required|string|max:255',
            'question_type' => 'required|in:multiple_choice,true_false,photo',
        ]);

        $quiz = Quiz::findOrFail($quizId);
        $quiz->questions()->create($validatedData);

        return redirect()->route('quizzes.show', $quizId)->with('success', 'Question added successfully!');
    }

    public function destroy($quizId, $id)
    {
        $question = Question::findOrFail($id);
        $question->delete();

        return redirect()->route('quizzes.show', $quizId)->with('success', 'Question deleted successfully!');
    }
}
