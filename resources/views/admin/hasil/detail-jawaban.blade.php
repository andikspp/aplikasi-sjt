<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Ujian</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.3/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        .navbar {
            background-color: #005689;
        }

        .navbar-custom .navbar-nav .nav-link {
            color: white;
        }

        .navbar-custom .navbar-brand {
            color: white;
        }

        .navbar-text {
            margin-left: auto;
            color: white;
            font-size: 1.5rem;
            text-decoration: none;
        }

        .navbar-text:hover {
            text-decoration: underline;
        }

        .navbar-nav .nav-item:hover {
            transform: scale(1.05);
        }

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

        footer {
            background-color: #005689;
            color: white;
        }
    </style>
</head>

<body>
    {{-- navbar --}}
    <nav class="navbar navbar-expand-lg navbar-light navbar-custom">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('admin.dashboard') }}">
                <img src="{{ asset('assets/logo kemendikbudristek.png') }}" alt="Logo" style="max-width: 100px;">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.soal') }}">Paket Soal</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('hasil') }}">Hasil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('data.peserta') }}">Data Peserta</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" id="logout-btn">Logout</a>
                        <form id="logout-form" action="{{ route('admin.logout') }}" method="POST"
                            style="display: none;">
                            @csrf
                        </form>
                    </li>
                </ul>
                <a class="navbar-text" href="https://gurupauddikmas.kemdikbud.go.id/" target="_blank">
                    Direktorat Guru PAUD dan Dikmas
                </a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h2 class="text-center">Detail Jawaban {{ $userName }}</h2>
        <a href="{{ route('grafik.individu', ['userId' => $userId]) }}" class="btn btn-primary">
            Lihat Grafik
        </a>
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

    <!-- Footer -->
    <footer class="text-center text-lg-start mt-5">
        <div class="text-center p-3">
            &copy; 2024 Guru PAUD Dikmas
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.3/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
