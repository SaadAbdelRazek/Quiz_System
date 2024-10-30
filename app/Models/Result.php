<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    use HasFactory;

    // Define the fillable properties
    protected $fillable = [
        'user_id',
        'quiz_id',
        'quizzer_id',
        'correct_answers',
        'total_questions',
        'points',
    ];

    /**
     * Get the user that owns the result.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the quiz associated with the result.
     */
    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

}
