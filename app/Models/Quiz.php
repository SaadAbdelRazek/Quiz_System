<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'subject'];

    public function questions()
    {
        return $this->hasMany(Question::class);
    }
    public function results()
    {
        return $this->hasMany(Result::class);
    }
}
