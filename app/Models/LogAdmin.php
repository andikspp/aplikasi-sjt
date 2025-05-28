<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogAdmin extends Model
{
    use HasFactory;

    protected $table = 'logs_admin';

    protected $fillable = [
        'admin_id',
        'admin_name',
        'action',
        'ip_address',
        'question_set_id',
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }
}
