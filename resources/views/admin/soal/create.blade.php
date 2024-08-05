<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Soal</title>
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
        <h2 class="text-center mb-4">Tambah Soal</h2>
        <form action="{{ route('admin.storeQuestion') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="question_set_id" class="form-label">Paket Soal</label>
                <select class="form-select" id="question_set_id" name="question_set_id" required>
                    @php
                        $questionSets = App\Models\QuestionSet::all();
                    @endphp
                    @foreach ($questionSets as $set)
                        <option value="{{ $set->id }}">{{ $set->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="question_text" class="form-label">Pertanyaan</label>
                <textarea class="form-control" id="question_text" name="question_text" rows="4" required></textarea>
            </div>

            <div class="mb-3">
                <div class="row">
                    <div class="col-md-8 mb-3">
                        <label for="option_a" class="form-label">Jawaban 1</label>
                        <input type="text" class="form-control" id="option_a" name="option_a" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="score_a" class="form-label">Pembobotan Jawaban 1</label>
                        <select class="form-select" id="score_a" name="score_a" required>
                            <option value="4">4</option>
                            <option value="3">3</option>
                            <option value="2">2</option>
                            <option value="1">1</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <div class="row">
                    <div class="col-md-8 mb-3">
                        <label for="option_a" class="form-label">Jawaban 2</label>
                        <input type="text" class="form-control" id="option_b" name="option_b" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="score_a" class="form-label">Pembobotan Jawaban 2</label>
                        <select class="form-select" id="score_b" name="score_b" required>
                            <option value="4">4</option>
                            <option value="3">3</option>
                            <option value="2">2</option>
                            <option value="1">1</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <div class="row">
                    <div class="col-md-8 mb-3">
                        <label for="option_a" class="form-label">Jawaban 3</label>
                        <input type="text" class="form-control" id="option_c" name="option_c" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="score_a" class="form-label">Pembobotan Jawaban 3</label>
                        <select class="form-select" id="score_c" name="score_c" required>
                            <option value="4">4</option>
                            <option value="3">3</option>
                            <option value="2">2</option>
                            <option value="1">1</option>
                        </select>
                    </div>
                </div>
            </div>


            <div class="mb-3">
                <div class="row">
                    <div class="col-md-8 mb-3">
                        <label for="option_a" class="form-label">Jawaban 4</label>
                        <input type="text" class="form-control" id="option_d" name="option_d" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="score_a" class="form-label">Pembobotan Jawaban 4</label>
                        <select class="form-select" id="score_d" name="score_d" required>
                            <option value="4">4</option>
                            <option value="3">3</option>
                            <option value="2">2</option>
                            <option value="1">1</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-danger me-2" onclick="goBack()">Kembali</button>
                <button type="submit" class="btn btn-primary me-2">Simpan Soal</button>
            </div>
        </form>
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

        function goBack() {
            window.history.back();
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
    </script>
</body>

</html>
