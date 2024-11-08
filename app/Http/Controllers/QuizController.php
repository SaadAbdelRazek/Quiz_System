<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\Quizzer;
use App\Models\Result;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\STR;

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
        'description' => 'nullable|string|max:500',
        'duration' => 'nullable|integer|min:0',
        'attempts' => 'nullable|integer|min:1',
        'show_answers_after_submission' => 'boolean',
        'visibility' => 'required|in:public,private',
        'password' => 'nullable|string|min:8',
        'questions' => 'required|array',
        'questions.*.question_text' => 'required|string|max:255',
        'questions.*.question_type' => 'required|in:multiple_choice,true_false,photo',
        'questions.*.correct_answer' => 'required',
        'questions.*.answers' => 'required_if:questions.*.question_type,multiple_choice|required_if:questions.*.question_type,photo|array|min:2|max:4',
        'questions.*.answers.*.answer_text' => 'required|string|max:255',
        'questions.*.photo' => 'sometimes|nullable|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
    ]);

    // إنشاء رمز الوصول في حالة الكويز الخاص
    $accessToken = $request->visibility === 'private' ? Str::random(12) : null;

    // البحث عن Quizzer المرتبط بالمستخدم الحالي
    $quizzer = Quizzer::where('user_id', auth()->user()->id)->first();



    // تحقق من وجود سجل Quizzer للمستخدم الحالي
    if (!$quizzer) {
        return back()->withErrors(['error' => 'Quizzer not found for the user. Please ensure you have the correct permissions to create quizzes.']);
    }

    // بدء المعاملة
    DB::beginTransaction();

    try {
        // إنشاء سجل الكويز
        $quiz = Quiz::create([
            'quizzer_id' => $quizzer->id,
            'title' => $validatedData['title'],
            'subject' => $validatedData['subject'],
            'description' => $validatedData['description'],
            'duration' => $validatedData['duration'],
            'attempts' => $validatedData['attempts'],
            'show_answers_after_submission' => $validatedData['show_answers_after_submission'],
            'visibility' => $validatedData['visibility'],
            'password' => $validatedData['password'],
            'access_token' => $accessToken,
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
        UserActivity::create([
            'user_id' => auth()->id(),
            'activity' => 'Create Quiz',
        ]);

        return redirect()->back()->with('success', 'Quiz created successfully!');
    } catch (\Exception $e) {
        DB::rollback();
        return redirect()->back()->with('error', 'Error creating quiz.');
    }
}



    public function show($id)
    {

        $quiz = Quiz::with('questions.answers')->findOrFail($id);
        if ($quiz->visibility === 'private') {
            $request->validate(['password' => 'required']);

            if (!Hash::check($request->password, $quiz->password)) {
                return back()->withErrors(['password' => 'Incorrect password']);
            }
        }
        return view('quizzes.show', compact('quiz'));
    }

    public function edit($id)
    {
        $quiz = Quiz::with(['questions.answers'])->findOrFail($id);
        $quiz_points = $quiz->questions->sum('points');
        if(!$quiz){
            return redirect()->back()->with('error', 'Quiz not found!');
        }
        return view('admin.admin-update-quiz', compact('quiz','quiz_points'));
    }

    public function update(Request $request, $id)
{
    // Find the quiz
    $accessToken = $request->visibility === 'private' ? Str::random(12) : null;
    $quiz = Quiz::findOrFail($id);

    // تحديث بيانات الكويز
    $quiz->title = $request->input('title');
    $quiz->subject = $request->input('subject');
    $quiz->description = $request->input('description');
    $quiz->duration = $request->input('duration');
    $quiz->attempts = $request->input('attempts');
    $quiz->show_answers_after_submission = $request->input('show_answers_after_submission');
    $quiz->visibility = $request->input('visibility');

    // تعيين كلمة المرور للكويز الخاص فقط
    if ($request->input('visibility') == 'public') {
        $quiz->password = null;
    } else {
        $validatedData = $request->validate([
            'password'=> 'required|min:8',
        ]);
        $quiz->password = $validatedData['password'];
    }

    $quiz->access_token = $accessToken;
    $quiz->save();

    // التكرار على الأسئلة وتحديثها
    foreach ($request->input('questions') as $index => $questionData) {
        // البحث عن السؤال وتحديثه
        $question = Question::findOrFail($questionData['id']);

        // حساب نقاط الاختبار
        $quiz_points = array_sum(array_column($request->input('questions'), 'points'));

        // تحديث نص السؤال والنقاط
        $question->question_text = $questionData['title'];
        $question->points = $questionData['points'];

        // إذا كان نوع السؤال هو true/false
        if ($question->question_type === 'true_false') {
            $question->is_true = $questionData['is_true'];
        }

        // حفظ السؤال
        $question->save();

        // تحديث الإجابات إذا كان السؤال من نوع multiple_choice أو photo
        if ($question->question_type === 'multiple_choice' || $question->question_type === 'photo') {
            // تحقق من وجود الإجابات
            if (isset($questionData['answers']) && is_array($questionData['answers'])) {
                // تحديث الإجابات
                foreach ($questionData['answers'] as $answerData) {
                    // تحقق من وجود الإجابة
                    if (isset($answerData['id'])) {
                        $answer = Answer::findOrFail($answerData['id']);
                        $answer->answer_text = $answerData['text'];
                        $answer->is_correct = ($questionData['correct_answer'] == $answer->id) ? 1 : 0;
                        $answer->save();
                    } else {
                        // إذا كانت الإجابة جديدة (لا تحتوي على ID)، يمكنك إنشاء سجل جديد هنا
                        $answer = new Answer();
                        $answer->question_id = $question->id; // ربط الإجابة بالسؤال
                        $answer->answer_text = $answerData['text'];
                        $answer->is_correct = ($questionData['correct_answer'] == $answer->id) ? 1 : 0;
                        $answer->save();
                    }
                }

                // إذا كنت بحاجة لإزالة الإجابات التي لم تعد موجودة
                $existingAnswerIds = array_column($question->answers->toArray(), 'id');
                $newAnswerIds = array_column($questionData['answers'], 'id');

                // حذف الإجابات التي لم تعد موجودة
                foreach ($existingAnswerIds as $existingId) {
                    if (!in_array($existingId, $newAnswerIds)) {
                        $answer = Answer::find($existingId);
                        if ($answer) {
                            $answer->delete(); // حذف الإجابة
                        }
                    }
                }
            }
        }

        // تحديث مسار الصورة إذا كان نوع السؤال هو photo
        if ($question->question_type === 'photo' && $request->hasFile("questions.$index.image")) {
            // حذف الصورة القديمة إذا كانت موجودة
            if ($question->photo && Storage::disk('public')->exists($question->photo)) {
                Storage::disk('public')->delete($question->photo);
            }

            // حفظ الصورة الجديدة وتحديث مسارها
            $imagePath = $request->file("questions.$index.image")->store('images', 'public');
            $question->photo = $imagePath;
        }

        $question->save();
    }
    UserActivity::create([
        'user_id' => auth()->id(),
        'activity' => 'Update Quiz',
    ]);

    return redirect()->route('admin-view-quizzes')->with('status', 'Quiz updated successfully!');
}


    public function update_activate($id){
        $quiz_state = Quiz::findOrFail($id);
        if($quiz_state->is_published==0){
            $quiz_state->is_published = 1;

        }
        elseif($quiz_state->is_published==1){
            $quiz_state->is_published = 0;

        }
        else{
            return redirect()->back()->with('success','updated successfully');
        }
        $quiz_state->save();
        return redirect()->back()->with('error','some thing be wrong, please try again');
    }


    public function destroy($id)
    {
        $quiz = Quiz::findOrFail($id);
        $quiz->delete();
        return redirect()->route('admin-view-quizzes')->with('success', 'Quiz deleted successfully!');
    }

    public function viewAllQuizzes()
{
    // التحقق مما إذا كان المستخدم مسجل دخول
    $user = auth()->user();
    $result = null;

    // إذا كان هناك مستخدم مسجل دخول، جلب بيانات المحاولات له
    if ($user) {
        $result = Result::where('user_id', $user->id)->first();
    }

    // استرجاع الكويزات المنشورة
    $quizzes = Quiz::where('is_published', 1)->with('results')->get();

    return view('website.quizzes', compact('quizzes', 'result'));
}



    public function viewQuiz($id)
    {
        $result = Result::where('user_id', auth()->user()->id)->where('quiz_id', $id)->first();
        $quiz = Quiz::with(['questions.answers'])->with('quizzer')->findOrFail($id);

        if($quiz->visibility == 'public'){
        if($result){
            if($result->attempts < $quiz->attempts){

                if(!$quiz){
                    return redirect()->back()->with('error', 'Quiz not found!');
                }

                return view('website.view-quiz', compact('quiz','result'));
            }

            else{
                return redirect()->back()->with('error','sorry, You have exceeded the allowed number of attempts for this quiz',compact('result','quiz'));
            }
        }
        else{
            return view('website.view-quiz', compact('quiz'));
        }
    }

    return redirect()->route('quiz_password',$quiz->access_token);


    }

    public function adminViewQuizzes()
    {
        // Fetch all quizzes from the database
        if (auth()->user()->role === 'SuperAdmin') {
            // Load all quizzes
            $quizzes = Quiz::all();

        }
        elseif (auth()->user()->role === 'admin') {
            // Load only quizzes created by the admin through quizzers table
            $quizzes = Quiz::whereHas('quizzer', function ($query) {
                $query->where('user_id', auth()->user()->id);
            })->get();
        }


        // Return the view and pass the quizzes to the Blade template
        return view('admin.admin-view-quizzes', compact('quizzes'));
    }

    public function submitQuiz(Request $request, $quizId)
{
    // التحقق من جميع الأسئلة المطلوبة
    $request->validate([
        'answers' => 'required|array',
        'answers.*' => 'required', // تحقق من أن كل سؤال تم الإجابة عليه
    ], [
        'answers.required' => 'Please answer all questions.', // الرسالة العامة
        'answers.*.required' => 'Please answer each question.', // الرسالة لكل سؤال
    ]);

    $correctAnswers = 0;
    $totalQuestions = 0;
    $points = 0;

    // تكرار على الأسئلة
    foreach ($request->answers as $questionId => $userAnswer) {
        $question = Question::with('quiz')->findOrFail($questionId);

        $totalQuestions++;

        // تحقق من نوع السؤال
        if ($question->question_type === 'true_false') {
            if ($userAnswer == $question->is_true) {
                $correctAnswers++;
                $points += $question->points; // أضف النقاط هنا فقط إذا كانت الإجابة صحيحة
            }
        } elseif ($question->question_type === 'multiple_choice' || $question->question_type === 'photo') {
            $correctAnswer = $question->answers()->where('is_correct', 1)->first();
            if ($correctAnswer && $userAnswer == $correctAnswer->id) {
                $correctAnswers++;
                $points += $question->points; // أضف النقاط هنا فقط إذا كانت الإجابة صحيحة
            }
        }
    }


    // استرجاع المحاولة للكويز
    $quiz_attempt = Quiz::find($quizId);
    $result_attempt = Result::where('user_id', auth()->user()->id)
                            ->where('quiz_id', $quizId)
                            ->first();

    if ($result_attempt) {
        if ($result_attempt->attempts < $quiz_attempt->attempts) {
            // زيادة عدد المحاولات إذا كانت أقل من الحد الأقصى

            $result_attempt->attempts++;
            $result_attempt->correct_answers = $correctAnswers;
            $result_attempt->total_questions = $totalQuestions;
            $result_attempt->points = $points;
            $result_attempt->save();
        } else {
            // العودة مع رسالة خطأ إذا تجاوز عدد المحاولات
            return redirect()->back()->with('error', 'Sorry, you have exceeded the allowed number of attempts for this quiz');
        }
    } else {
        // إذا كانت هذه أول محاولة، يتم إنشاء سجل جديد
        Result::create([
            'user_id' => auth()->id(),
            'quiz_id' => $quizId,
            'quizzer_id' => $question->quiz->quizzer_id,
            'correct_answers' => $correctAnswers,
            'total_questions' => $totalQuestions,
            'points' => $points,
            'attempts' => 1,
            'email' => $request->email
        ]);
    }
    $quizData=Quiz::where('id',$quizId)->first();
    $userAttempts=Result::where('user_id',auth()->id())->where('quiz_id',$quizId)->first();
    if($quiz_attempt->show_answers_after_submission == 1){
        return view('website.quiz-submit-details', compact('quizId', 'correctAnswers', 'totalQuestions', 'points', 'quiz_attempt', 'result_attempt', 'quizData', 'userAttempts'));
    }
    else{
        return view('website.quiz-submit-thank', compact('quizId', 'correctAnswers', 'totalQuestions', 'points', 'quiz_attempt', 'result_attempt'));
    }
}


    public function searchQuizzes(Request $request)
    {
        $query = $request->input('query');
        $quizzes = Quiz::where('title', 'LIKE', "%{$query}%")
            ->orWhere('subject', 'LIKE', "%{$query}%")
            ->get();
        return response()->json($quizzes);
    }


    public function submit_password_private_quiz($access){
        $quiz = Quiz::with(['questions.answers'])->where('access_token',$access)->first();
        if($quiz){

            return view('website.password-private-quiz',compact('quiz'));
        }
        else{
            return redirect()->back()->with('error','quiz not found');
        }
    }

    public function view_private_quiz(Request $request ,$id){

        $quiz = Quiz::with(['questions.answers'])->findOrFail($id);
        $request->validate([
            'password' => 'required'
        ]);

        if($request->password == $quiz->password){



        $result = Result::where('user_id', auth()->user()->id)->where('quiz_id', $quiz->id)->first();



        if($result){
            if($result->attempts < $quiz->attempts){

                if(!$quiz){
                    return redirect()->back()->with('error', 'Quiz not found!');
                }

                return view('website.view-private-quiz', compact('quiz','result'));
            }

            else{
                return redirect()->back()->with('error','sorry, You have exceeded the allowed number of attempts for this quiz',compact('result','quiz'));
            }
        }
        else{
            return view('website.view-private-quiz', compact('quiz'));
        }
    }

    else{
        return redirect()->back()->with('error','password incorrect');
    }
}


}
