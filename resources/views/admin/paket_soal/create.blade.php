<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Soal</title>
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

        footer {
            background-color: #005689;
            color: white;
        }

        .form-container {
            background-color: #f8f9fa;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            margin-top: 2rem;
        }
    </style>
</head>

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
                    <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
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

<div class="container">
    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: '@foreach ($errors->all() as $error) {{ $error }} @endforeach',
                });
            });
        </script>
    @endif
    <div class="form-container">
        <h5 class="mb-4">Tambah Paket Soal</h5>
        <form action="{{ route('store.QuestionSet') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Nama Paket</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="start_exam" class="form-label">Waktu Mulai Ujian</label>
                <input type="datetime-local" class="form-control" id="start_exam" name="start_exam" required>
            </div>
            <div class="mb-3">
                <label for="end_exam" class="form-label">Waktu Berakhir Ujian</label>
                <input type="datetime-local" class="form-control" id="end_exam" name="end_exam" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Role</label>
                <div class="form-check">
                    <input class="form-check-input" type="radio" id="role_guru" name="role" value="guru"
                        required>
                    <label class="form-check-label" for="role_guru">Guru</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" id="role_kepala_sekolah" name="role"
                        value="kepala_sekolah" required>
                    <label class="form-check-label" for="role_kepala_sekolah">Kepala Sekolah</label>
                </div>
            </div>
            <div class="mb-3">
                <label for="time_limit" class="form-label">Durasi Ujian (Menit)</label>
                <input type="number" class="form-control" id="time_limit" name="time_limit" required>
            </div>
            <a href="{{ route('admin.soal') }}" class="btn btn-danger">
                Kembali
            </a>
            <button type="submit" class="btn btn-success">Simpan</button>
        </form>
    </div>
</div>

<footer class="text-center text-lg-start mt-5">
    <div class="text-center p-3">
        &copy; 2024 Guru PAUD Dikmas
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.3/dist/sweetalert2.all.min.js"></script>

</html>
