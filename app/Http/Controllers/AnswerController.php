<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Question;
use Illuminate\Http\Request;

class AnswerController extends Controller
{
    public function create($questionId)
    {
        $question = Question::findOrFail($questionId);
        return view('answers.create', compact('question'));
    }

    public function store(Request $request, $questionId)
    {
        $validatedData = $request->validate([
            'answer_text' => 'required|string|max:255',
            'is_correct' => 'required|boolean',
        ]);

        $question = Question::findOrFail($questionId);
        $question->answers()->create($validatedData);

        return redirect()->route('quizzes.show', $question->quiz_id)->with('success', 'Answer added successfully!');
    }

    public function destroy($questionId, $id)
    {
        $answer = Answer::findOrFail($id);
        $answer->delete();

        return redirect()->route('quizzes.show', $answer->question->quiz_id)->with('success', 'Answer deleted successfully!');
    }
}
