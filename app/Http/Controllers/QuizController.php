<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\Result;
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
        'questions.*.answers' => 'required_if:questions.*.question_type,multiple_choice|required_if:questions.*.question_type,photo|array|min:2|max:4',
        'questions.*.answers.*.answer_text' => 'required|string|max:255',
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
            if ($question->question_type === 'multiple_choice' || $question->question_type ==='photo') {
                foreach ($questionData['answers'] as $answerData) {
                    $answer = Answer::findOrFail($answerData['id']);
                    $answer->answer_text = $answerData['text'];
                    $answer->is_correct = ($questionData['correct_answer'] == $answer->id) ? 1 : 0;
                    $answer->save();
                }


            }
        }

        return redirect()->route('admin-view-quizzes')->with('status', 'Quiz updated successfully!');
    }


    public function destroy($id)
    {
        $quiz = Quiz::findOrFail($id);
        $quiz->delete();
        return redirect()->route('admin-view-quizzes')->with('success', 'Quiz deleted successfully!');
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

    public function submitQuiz(Request $request, $quizId)
    {
        // Validate the incoming request
        $request->validate([
            'answers' => 'required|array',
        ]);

        $correctAnswers = 0;
        $totalQuestions = 0;

        // Loop through the quiz questions
        foreach ($request->answers as $questionId => $userAnswer) {
            $question = Question::findOrFail($questionId);
            $totalQuestions++;

            // Check if the question type is true/false or multiple choice
            if ($question->question_type === 'true_false') {
                if ($userAnswer == $question->is_true) {
                    $correctAnswers++;
                }
            } elseif ($question->question_type === 'multiple_choice' || $question->question_type === 'photo') {
                $correctAnswer = $question->answers()->where('is_correct', 1)->first();
                if ($correctAnswer && $userAnswer == $correctAnswer->id) {
                    $correctAnswers++;
                }
            }
            // Handle photo questions similarly if needed
        }

        // Store the user's results in the database
        Result::create([
            'user_id' => auth()->id(), // Assuming the user is authenticated
            'quiz_id' => $quizId,
            'correct_answers' => $correctAnswers,
            'total_questions' => $totalQuestions,
        ]);

        return view('website.quiz-submit-details',compact('quizId','correctAnswers','totalQuestions'));
    }

    public function searchQuizzes(Request $request)
    {
        $query = $request->input('query');
        $quizzes = Quiz::where('title', 'LIKE', "%{$query}%")
            ->orWhere('subject', 'LIKE', "%{$query}%")
            ->get();
        return response()->json($quizzes);
    }

}
