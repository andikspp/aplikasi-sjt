<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kompetensi extends Model
{
    use HasFactory;

    protected $table = 'kompetensi';

    protected $fillable = ['nama', 'role'];

    public function indikator()
    {
        return $this->hasMany(Indikator::class);
    }
}
