@extends('layout.admin.admin-layout')

@section('title', 'Detail Soal')

@section('content')
    <style>
        .table-custom {
            border-collapse: collapse;
            width: 100%;
        }

        .table-custom th,
        .table-custom td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .table-custom th {
            background-color: #f2f2f2;
            color: #333;
            font-weight: bold;
        }

        .table-custom tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .table-custom tr:hover {
            background-color: #eaeaea;
        }

        .table-custom td a,
        .table-custom td form button {
            margin-right: 5px;
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
        <table class="table table-custom">
            <thead>
                <tr>
                    <th>Pertanyaan</th>
                    <th>Jawaban 1</th>
                    <th>Bobot 1</th>
                    <th>Jawaban 2</th>
                    <th>Bobot 2</th>
                    <th>Jawaban 3</th>
                    <th>Bobot 3</th>
                    <th>Jawaban 4</th>
                    <th>Bobot 4</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($questions as $question)
                    <tr>
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
                        <td>
                            <a href="{{ route('admin.soal', $question->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('admin.soal', $question->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

@endsection
