@extends('layout.admin.admin-layout')

@section('title', 'Hasil Ujian')

@section('content')
    <style>
        .table th,
        .table td {
            vertical-align: middle;
            text-align: center;
        }

        .table th {
            background-color: #005689;
            color: #fff;
            font-weight: bold;
        }

        .table td {
            background-color: #f8f9fa;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #e9ecef;
        }

        .table-bordered {
            border: 1px solid #dee2e6;
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid #dee2e6;
        }

        .btn-dikmas {
            background-color: #005689;
            color: white;
        }

        .btn-dikmas:hover {
            background-color: #004a73;
            color: white;
        }
    </style>
    <div class="container mt-5">
        <div class="mb-3">
            @if ($userRole === 'guru')
                <a href="{{ route('hasil.guru') }}" class="btn btn-dikmas">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            @elseif ($userRole === 'kepala sekolah')
                <a href="{{ route('hasil.kepala_sekolah') }}" class="btn btn-dikmas">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            @endif
        </div>
        <h2 class="text-center">Detail Jawaban {{ $userName }}</h2>
        <a href="{{ route('grafik.individu', ['userId' => $userId]) }}" class="btn btn-primary">
            Lihat Grafik
        </a>
        <a href="{{ route('export.answers', ['userId' => $userId]) }}" class="btn btn-success">Export to Excel</a>

        <table class="table table-striped table-bordered table-hover mt-4">
            <thead class="thead-dark">
                <tr>
                    <th>Nomor</th>
                    <th>Pertanyaan</th>
                    <th>Jawaban</th>
                    <th>Kompetensi</th>
                    <th>Indikator</th>
                    <th>Skor</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($answers as $index => $answer)
                    <tr>
                        <td>{{ $loop->iteration + $answers->firstItem() - 1 }}</td>
                        <td>{{ $answer->question_text }}</td>
                        <td>{{ $answer->answer_text }}</td>
                        <td>{{ $answer->nama }}</td>
                        <td>{{ $answer->indikator_nama }}</td>
                        <td>{{ $answer->score }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">Belum ada jawaban.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="d-flex justify-content-center">
            {{ $answers->links('pagination.pagination') }}
        </div>
    </div>

@endsection

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.3/dist/sweetalert2.all.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
