<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
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

        .card-header {
            background-color: #005689;
            color: #fff;
            font-weight: bold;
        }

        .card-body {
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            border-radius: 8px;
        }

        .info-item {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .info-item i {
            color: #005689;
            margin-right: 10px;
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
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <img src="{{ asset('assets/logo kemendikbudristek.png') }}" alt="Logo" style="max-width: 100px;">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">FAQ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('profil') }}">Profil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" id="logout-btn">Logout</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
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


    <!-- Main Content -->
    <div class="container mt-5">
        <!-- resources/views/dashboard.blade.php -->
        {{-- @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif --}}

        <div class="row">
            <div class="col-md-12">
                <h3>Selamat Datang, {{ $user->name }}</h3>

                {{-- <div class="card mt-4">
                    <div class="card-header">
                        Ujian Aktif
                    </div>
                    <div class="card-body">
                        <p>Tidak ada ujian yang aktif.</p>
                    </div>
                </div>  --}}

                <!-- Another Content Section -->
                <div class="card mt-4 shadow-sm">
                    <div class="card-header text-white">
                        <h5 class="mb-0">Informasi Pribadi</h5>
                    </div>
                    <div class="card-body">
                        <div class="info-item mb-3 d-flex align-items-center">
                            <i class="fas fa-user fa-lg mr-3"></i>
                            <p class="mb-0">Nama: <strong>{{ $user->name }}</strong> ({{ ucwords($user->role) }})
                            </p>
                        </div>
                        <div class="info-item mb-3 d-flex align-items-center">
                            <i class="fas fa-envelope fa-lg mr-3"></i>
                            <p class="mb-0">Email: <strong>{{ $user->email }}</strong></p>
                        </div>
                        <div class="info-item mb-3 d-flex align-items-center">
                            <i class="fas fa-phone fa-lg mr-3"></i>
                            <p class="mb-0">Nomor Telepon: <strong>{{ $user->telepon }}</strong></p>
                        </div>
                        <div class="info-item mb-3 d-flex align-items-center">
                            <i class="fas fa-home fa-lg mr-3"></i>
                            <p class="mb-0">Instansi: <strong>{{ strtoupper($user->instansi) }}</strong></p>
                        </div>
                    </div>
                </div>

                <div class="card mt-4 shadow-sm">
                    <div class="card-header text-white">
                        <h5 class="mb-0">Riwayat Ujian</h5>
                    </div>
                    <div class="card-body">
                        @if ($quizAttempt)
                            <div class="info-item d-flex align-items-center mb-3">
                                <i class="fas fa-calendar-alt fa-lg mr-3"></i>
                                <p class="mb-0">Waktu Selesai Ujian: <strong>{{ $endedAt->format('d-m-Y, H:i:s') }}
                                        WIB</strong></p>
                            </div>
                        @else
                            <p class="text-center mb-0">Belum ada riwayat ujian.</p>
                        @endif
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ route('exam') }}"><button class="btn btn-custom">Mulai Ujian</button></a>
                </div>
            </div>
        </div>
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
        document.addEventListener('DOMContentLoaded', function() {
            const logoutBtn = document.getElementById('logout-btn');

            if (logoutBtn) {
                logoutBtn.addEventListener('click', function(event) {
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
            }

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Maaf',
                    text: '{{ session('error') }}',
                });
            @endif

            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: '{{ session('success') }}',
                });
            @endif
        });
    </script>
</body>

</html>
