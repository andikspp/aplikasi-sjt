<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Indikator extends Model
{
    use HasFactory;

    protected $table = 'indikator';

    protected $fillable = ['nama', 'kompetensi_id'];

    /**
     * Relasi ke model Kompetensi
     */
    public function kompetensi()
    {
        return $this->belongsTo(Kompetensi::class);
    }
}
