@extends('layout.admin.admin-layout')

@section('title', 'Detail Soal')

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

        .btn-custom {
            background-color: #005689;
            color: white;
        }

        .btn-custom:hover {
            background-color: #012a41;
            color: white;
        }
    </style>

    <div class="container mt-5">
        <h2 class="text-center mb-4">{{ $questionSet->name }}</h2>
        <div class="tambahSoal mb-3 d-flex justify-content-end">
            <a href="{{ route('admin.soal.create') }}">
                <button class="btn btn-custom">
                    Tambah Soal
                </button>
            </a>
        </div>
        <table class="table table-striped table-bordered table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>No.</th>
                    <th>Pertanyaan</th>
                    <th>Jawaban 1</th>
                    <th>Bobot 1</th>
                    <th>Jawaban 2</th>
                    <th>Bobot 2</th>
                    <th>Jawaban 3</th>
                    <th>Bobot 3</th>
                    <th>Jawaban 4</th>
                    <th>Bobot 4</th>
                    <th>Kompetensi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($questions as $question)
                    <tr>
                        <td>{{ $loop->iteration + $questions->firstItem() - 1 }}</td>
                        <td>{{ $question->question_text }}</td>
                        @foreach ($question->answers as $index => $answer)
                            @if ($index == 0)
                                <td>{{ $answer->answer_text }}</td>
                                <td>{{ $answer->score }}</td>
                            @elseif ($index == 1)
                                <td>{{ $answer->answer_text }}</td>
                                <td>{{ $answer->score }}</td>
                            @elseif ($index == 2)
                                <td>{{ $answer->answer_text }}</td>
                                <td>{{ $answer->score }}</td>
                            @elseif ($index == 3)
                                <td>{{ $answer->answer_text }}</td>
                                <td>{{ $answer->score }}</td>
                            @endif
                        @endforeach
                        <td>{{ $question->kompetensi ? $question->kompetensi->nama : '-' }}</td>
                        <td>
                            <a href="{{ route('admin.soal.edit', $question->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form id="delete-form-{{ $question->id }}" action="{{ route('hapus.soal', $question->id) }}"
                                method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger btn-sm"
                                    onclick="confirmDelete({{ $question->id }})">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="d-flex justify-content-center">
            {{ $questions->links('pagination.pagination') }}
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.3/dist/sweetalert2.all.min.js"></script>
    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Konfirmasi Penghapusan',
                text: 'Anda yakin ingin menghapus soal ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Jika dikonfirmasi, kirimkan formulir
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }

        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
            });
        @endif
    </script>

@endsection
