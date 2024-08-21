<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ended_at',
        'score',
    ];

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($quizAttempt) {
            $quizAttempt->userAnswers()->delete();
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function userAnswers()
    {
        return $this->hasMany(UserAnswer::class, 'user_id', 'user_id');
    }
}
