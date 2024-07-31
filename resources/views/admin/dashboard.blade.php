<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
                        <a class="nav-link" href="{{ route('admin.soal') }}">Soal</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('hasil') }}">Hasil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">User</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">
                            Logout
                        </a>
                        <form id="logout-form" action="{{ '/' }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </li>
                </ul>
                <a class="navbar-text" href="https://gurupauddikmas.kemdikbud.go.id/" target="_blank">
                    Direktorat Jenderal PAUD dan Dikmas
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <h1>Selamat Datang, Admin!</h1>
                <p>This is your user dashboard where you can manage your profile, view activities, and more.</p>

                <!-- Example Content Section -->
                {{-- <div class="card mt-4">
                    <div class="card-header">
                        Ujian Aktif
                    </div>
                    <div class="card-body">
                        <p>Tidak ada ujian yang aktif.</p>
                    </div>
                </div> --}}

                <!-- Another Content Section -->
                <div class="card mt-4">
                    <div class="card-header">
                        Informasi Pribadi
                    </div>
                    <div class="card-body">
                        <p>Nama: name</p>
                        <p>Email: email</p>
                        <p>Nomor Telepon: </p>
                        <!-- Add more user information here -->
                    </div>
                </div>
                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ route('examPage') }}">
                        <button class="btn btn-primary">Mulai Ujian</button>
                    </a>
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
</body>

</html>
