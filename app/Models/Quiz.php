<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'quizzer_id',
        'title',
        'subject',
        'description',
        'duration',
        'attempts',
        'show_answers_after_submission',
        'visibility',
        'password',
        'access_token',
        'is_published'
    ];

    public function questions()
    {
        return $this->hasMany(Question::class);
    }
    public function quizzer()
    {
        return $this->belongsTo(Quizzer::class);

    }

    public function results()
    {
        return $this->hasMany(Result::class);
    }

}
