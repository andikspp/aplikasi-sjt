<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class GuruAnswersExport implements FromCollection, WithHeadings, WithMapping
{
    protected $results;
    protected $questions;

    public function __construct($results, $questions)
    {
        $this->results = $results;
        $this->questions = $questions;
    }

    public function collection()
    {
        return $this->results;
    }

    public function map($result): array
    {
        // Membuat array yang berisi data dari setiap pengguna
        $data = [
            $result->name,
            $result->username,
            $result->telepon,
            $result->instansi,
            $result->role,
            $result->question_set_name,
            $result->ended_at,
            $result->score,
        ];

        // Menambahkan skor jawaban dari setiap soal berdasarkan soal yang ada
        foreach ($this->questions as $question) {
            $data[] = $result->answers_by_question[$question->question_text]['score'] ?? 'Tidak Ada Skor';
        }

        return $data;
    }

    public function headings(): array
    {
        // Menentukan header kolom untuk Excel, termasuk soal-soal
        $headings = [
            'Nama',
            'Username',
            'Telepon',
            'Instansi',
            'Role',
            'Paket Soal',
            'Waktu Selesai',
            'Total Skor'
        ];

        // Menambahkan soal sebagai header di Excel
        foreach ($this->questions as $question) {
            $headings[] = $question->question_text;
        }

        return $headings;
    }
}
