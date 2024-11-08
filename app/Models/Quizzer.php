<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quizzer extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
    ];

    /**
     * Get the user that owns the Quizzer
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function quiz()
    {
        return $this->hasMany(Quizzer::class);
    }

    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }

}
