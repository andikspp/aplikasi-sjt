<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Allowance extends Model
{
    use HasFactory;

    protected $table = 'allowance';

    protected $fillable = [
        'user_id', // id hasil tes yang ingin dihapus
        'quiz_attempt_id', // id quiz attempt yang ingin dihapus
        'requested_by',   // id admin yang meminta
        'reason',
        'status',         // pending, approved, rejected
    ];

    public function adminrequested()
    {
        return $this->belongsTo(Admin::class, 'requested_by', 'id');
    }

    public function peserta()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function quizAttempt()
    {
        return $this->belongsTo(QuizAttempt::class, 'quiz_attempt_id', 'id');
    }

    public function approvals()
    {
        return $this->hasMany(AllowanceApproval::class, 'allowance_id', 'id');
    }
}
