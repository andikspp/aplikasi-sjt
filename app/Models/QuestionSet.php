<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionSet extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'time_limit', 'start_exam', 'end_exam', 'role'];

    public function questions()
    {
        return $this->hasMany(Question::class);
    }
}
