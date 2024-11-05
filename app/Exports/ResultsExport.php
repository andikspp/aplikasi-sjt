<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Collection;

class ResultsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $results;

    public function __construct(Collection $results)
    {
        $this->results = $results;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->results;
    }

    public function map($result): array
    {

        $competencyScores = collect($result->competency_scores)
            ->map(function ($score, $competency) {
                return "$competency: $score";
            })->join(', ');

        return [
            $result->name,
            $result->username,
            $result->telepon,
            $result->instansi,
            $result->role,
            $result->question_set_name,
            $result->ended_at,
            $result->score,
            $competencyScores,
        ];
    }

    public function headings(): array
    {
        return [
            'Nama',
            'Username',
            'Telepon',
            'Instansi',
            'Role',
            'Paket Soal',
            'Waktu Selesai',
            'Score',
            'Skor Kompetensi',
        ];
    }
}
