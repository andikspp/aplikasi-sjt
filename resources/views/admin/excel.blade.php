<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ResultsExport implements FromCollection, WithHeadings
{
    protected $results;

    public function __construct($results)
    {
        $this->results = $results;
    }

    public function collection()
    {
        return $this->results;
    }

    public function headings(): array
    {
        return [
            'Nama',
            'Email',
            'Telepon',
            'Instansi',
            'Role',
            'Paket Soal',
            'Waktu Mulai',
            'Waktu Selesai',
            'Score',
        ];
    }
}