@extends('layout.admin.admin-layout')

@section('title', 'Data Hasil Guru')

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
    </style>

    <div class="container mt-5">
        <h2 class="text-center mb-4">Hasil Ujian Guru</h2>
        <div class="d-flex justify-content-between mb-3">
                <a href="#" class="btn btn-success mb-3">Export to Excel</a>
                <form method="GET" action="#" class="mb-3" style="text-align: right;">
                    <input type="text" name="search" placeholder="Search..." class="form-control mb-2" style="width: 150px; display: inline-block;" />
                    <button type="submit" class="btn btn-primary mb-2" style="margin-left: 10px;">Cari</button>
            </form>
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Telepon</th>
                        <th>Instansi</th>
                        <th>Role</th>
                        <th>Paket Soal</th>
                        <th>Waktu Selesai</th>
                        <th>Score</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($results as $result)
                        <tr>
                            <td>
                                <a href="{{ route('jawaban.peserta', ['userId' => $result->id]) }}">
                                    {{ $result->name }}
                                </a>
                            </td>
                            <td>{{ $result->email }}</td>
                            <td>{{ $result->telepon }}</td>
                            <td>{{ strtoupper($result->instansi) }}</td>
                            <td>{{ ucwords($result->role) }}</td>
                            <td>{{ $result->question_set_name }}</td>
                            <td>{{ \Carbon\Carbon::parse($result->ended_at)->setTimezone('Asia/Jakarta')->format('d-m-Y H:i') }}
                                WIB
                            </td>
                            <td>{{ $result->score }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
