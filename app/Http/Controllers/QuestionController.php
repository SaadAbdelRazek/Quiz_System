<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Quiz;
use App\Models\Quizzer;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuestionController extends Controller
{
    public function addQuestionsView($id){
        $quiz=Quiz::find($id);
        if(!$quiz){
            abort(404);
        }
        return view('admin.adding-extra-quiz-questions',compact('quiz'));
    }
    public function addQuestions(Request $request,$id){
        $quiz=Quiz::findorfail($id);
        $validatedData = $request->validate([
            'questions' => 'required|array',
            'questions.*.question_text' => 'required|string|max:255',
            'questions.*.question_type' => 'required|in:multiple_choice,true_false,photo',
            'questions.*.correct_answer' => 'required',
            'questions.*.answers' => 'required_if:questions.*.question_type,multiple_choice|required_if:questions.*.question_type,photo|array|min:2|max:4',
            'questions.*.answers.*.answer_text' => 'required|string|max:255',
            'questions.*.photo' => 'sometimes|nullable|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
        ]);

        $quizzer = Quizzer::where('user_id', auth()->user()->id)->first();


        // تحقق من وجود سجل Quizzer للمستخدم الحالي
        if (!$quizzer) {
            return back()->withErrors(['error' => 'Quizzer not found for the user. Please ensure you have the correct permissions to create quizzes.']);
        }

        DB::beginTransaction();
        foreach ($validatedData['questions'] as $questionData) {
            // Initialize default values for optional fields
            $photoPath = null;
            $isTrue = null; // Initialize to default

            // If the question type is 'true_false', handle is_true value
            if ($questionData['question_type'] === 'true_false') {
                $isTrue = ($questionData['correct_answer'] === 'true');
            }

            // If the question type is 'photo', handle the image upload
            if ($questionData['question_type'] === 'photo' && isset($questionData['photo'])) {
                $photoPath = $questionData['photo']->store('photos', 'public'); // Store the photo in the 'photos' directory in public storage
            }

            // Create the question
            $question = $quiz->questions()->create([
                'question_text' => $questionData['question_text'],
                'question_type' => $questionData['question_type'],
                'photo' => $photoPath, // Save the photo path if it exists
                'is_true' => $isTrue,
            ]);

            // Create the answers if the question is not True/False
            if ($questionData['question_type'] !== 'true_false') {
                foreach ($questionData['answers'] as $index => $answerData) {
                    $question->answers()->create([
                        'answer_text' => $answerData['answer_text'],
                        'is_correct' => $index == $questionData['correct_answer'],
                    ]);
                }
            }
        }

        DB::commit();
        UserActivity::create([
            'user_id' => auth()->id(),
            'activity' => 'Adding Questions to his quiz',
        ]);

        return redirect()->back()->with('success', 'Questions added successfully!');


    }

    public function destroy($id)
    {
        try {
            // Retrieve the question along with its answers
            $question = Question::with('answers')->findOrFail($id);

            // Delete the answers associated with the question
            $question->answers()->delete();

            // Delete the question itself
            $question->delete();

            return response()->json(['success' => true, 'message' => 'Question and its answers deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete the question']);
        }
    }

}
