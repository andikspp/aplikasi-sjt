<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllowanceApproval extends Model
{
    use HasFactory;

    protected $table = 'allowance_approval';

    protected $fillable = [
        'allowance_id', // id dari allowance yang disetujui
        'admin_id',     // id admin yang menyetujui
        'status',       // approved, rejected
        'approved_at',  // waktu persetujuan
    ];

    public function allowance()
    {
        return $this->belongsTo(Allowance::class, 'allowance_id', 'id');
    }
}
