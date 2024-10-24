<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuizController extends Controller
{
    public function viewQuizForm(){
        return view('admin.create-quiz');
    }
    public function index()
    {
        $quizzes = Quiz::all();
        return view('quizzes.index', compact('quizzes'));
    }

    public function create()
    {
        return view('quizzes.create');
    }

    public function createQuiz(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'questions' => 'required|array',
            'questions.*.question_text' => 'required|string|max:255',
            'questions.*.question_type' => 'required|in:multiple_choice,true_false,photo',
            'questions.*.correct_answer' => 'required',
            'questions.*.answers' => 'sometimes|array|min:2|max:4',
            'questions.*.answers.*.answer_text' => 'required_if:questions.*.question_type,multiple_choice,photo|string|max:255',
            'questions.*.photo' => 'sometimes|nullable|image|mimes:jpeg,png,jpg,gif,svg|max:4096', // Validation for photo
        ]);

        DB::beginTransaction();

        try {
            // Create the Quiz
            $quiz = Quiz::create([
                'title' => $validatedData['title'],
                'subject' => $validatedData['subject'],
            ]);

            foreach ($validatedData['questions'] as $questionData) {
                // Initialize the photo path as null
                $photoPath = null;

                if ($questionData['question_type'] === 'true_false') {
                    // Set is_true based on the user's input (either true or false)
                    $isTrue = ($questionData['correct_answer'] === 'true') ? true : false;
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

            return redirect()->back()->with('success', 'Quiz created successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Error creating quiz.');
        }
    }


    public function show($id)
    {
        $quiz = Quiz::with('questions.answers')->findOrFail($id);
        return view('quizzes.show', compact('quiz'));
    }

    public function edit($id)
    {
        $quiz = Quiz::with(['questions.answers'])->findOrFail($id);
        if(!$quiz){
            return redirect()->back()->with('error', 'Quiz not found!');
        }
        return view('admin.admin-update-quiz', compact('quiz'));
    }

    public function update(Request $request, $id)
    {
        // Find the quiz
        $quiz = Quiz::findOrFail($id);

        // Update quiz title and subject
        $quiz->title = $request->input('title');
        $quiz->subject = $request->input('subject');
        $quiz->save();

        // Loop through the submitted questions
        foreach ($request->input('questions') as $questionData) {
            $question = Question::findOrFail($questionData['id']);

            // Update question details
            $question->question_text = $questionData['title'];

            // For true/false questions, update the is_true column
            if ($question->question_type === 'true_false') {
                $question->is_true = $questionData['is_true'];
            }

            $question->save();

            // For multiple-choice questions, update answers
            if ($question->question_type === 'multiple_choice') {
                foreach ($questionData['answers'] as $answerData) {
                    $answer = Answer::findOrFail($answerData['id']);
                    $answer->answer_text = $answerData['text'];
                    $answer->is_correct = ($questionData['correct_answer'] == $answer->id) ? 1 : 0;
                    $answer->save();
                }

                if ($question->question_type === 'photo') {
                    foreach ($questionData['answers'] as $answerData) {
                        $answer = Answer::findOrFail($answerData['id']);
                        $answer->answer_text = $answerData['text'];
                        $answer->is_correct = ($questionData['correct_answer'] == $answer->id) ? 1 : 0;
                        $answer->save();
                    }
                }
            }
        }

        return redirect()->route('admin-view-quizzes')->with('status', 'Quiz updated successfully!');
    }

    public function destroy($id)
    {
        $quiz = Quiz::findOrFail($id);
        $quiz->delete();
        return redirect()->route('quizzes.index')->with('success', 'Quiz deleted successfully!');
    }

    public function viewAllQuizzes()
    {
        $quizzes = Quiz::all();
        return view('website.quizzes', compact('quizzes'));
    }

    public function viewQuiz($id)
    {
        $quiz = Quiz::with(['questions.answers'])->findOrFail($id);
        if(!$quiz){
            return redirect()->back()->with('error', 'Quiz not found!');
        }

        return view('website.view-quiz', compact('quiz'));
    }

    public function adminViewQuizzes()
    {
        // Fetch all quizzes from the database
        $quizzes = Quiz::all();

        // Return the view and pass the quizzes to the Blade template
        return view('admin.admin-view-quizzes', compact('quizzes'));
    }
}
