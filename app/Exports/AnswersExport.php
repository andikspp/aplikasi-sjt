<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AnswersExport implements FromCollection, WithHeadings, WithMapping
{
    protected $answers;

    public function __construct($answers)
    {
        $this->answers = $answers;
    }

    public function collection()
    {
        return collect($this->answers);  // Convert to a collection
    }

    public function map($answer): array
    {
        static $index = 1;  // Use a static variable to increment the index
        return [
            $index++,  // Increment and use as the "Nomor" field
            $answer->question_text,
            $answer->score,  // Use score instead of answer_text
            $answer->kompetensi_name,  // Kompetensi name
            $answer->score,
        ];
    }

    public function headings(): array
    {
        return [
            'Nomor',
            'Pertanyaan',
            'Skor Jawaban',  // Update heading to reflect 'score'
            'Kompetensi',
            'Skor',
        ];
    }
}
