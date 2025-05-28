<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_text',
        'question_set_id',
        'kompetensi_id',
        'indikator_id'
    ];

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    public function questionSet()
    {
        return $this->belongsTo(QuestionSet::class, 'question_set_id');
    }

    public function kompetensi()
    {
        return $this->belongsTo(Kompetensi::class, 'kompetensi_id');
    }

    public function indikator()
    {
        return $this->belongsTo(Indikator::class, 'indikator_id');
    }
}
