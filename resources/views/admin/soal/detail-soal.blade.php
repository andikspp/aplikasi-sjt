<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Soal</title>
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

        footer {
            background-color: #005689;
            color: white;
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
</head>

<body>

    <!-- Navbar -->
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
                        <a class="nav-link" href="{{ route('admin.soal') }}">Soal</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('hasil') }}">Hasil</a>
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
        <h2 class="text-center mb-4">Soal Paket: {{ $questionSet->name }}</h2>
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
                            <form action="{{ route('admin.soal', $question->id) }}" method="POST"
                                style="display:inline;">
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

    <!-- Footer -->
    <footer class="text-center text-lg-start mt-5">
        <div class="text-center p-3">
            &copy; 2024 Guru PAUD Dikmas
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.3/dist/sweetalert2.all.min.js"></script>
    <script>
        document.getElementById('logout-btn').addEventListener('click', function(event) {
            event.preventDefault();

            Swal.fire({
                title: 'Anda yakin ingin logout?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, logout!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logout-form').submit();
                }
            });
        });
    </script>
</body>

</html>
