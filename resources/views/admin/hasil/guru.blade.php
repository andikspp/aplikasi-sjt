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
        <h2 class="text-center mb-4">Hasil Ujian Guru</h2>
        <a href="#" class="btn btn-success mb-3">Export to Excel</a>
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

    <!-- Footer -->
    <footer class="text-center text-lg-start mt-5">
        <div class="text-center p-3">
            &copy; 2024 Guru PAUD Dikmas
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.3/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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
