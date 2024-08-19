<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ujian Aktif</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.3/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        .navbar-custom {
            background-color: #005689;
        }

        .navbar-custom .navbar-nav .nav-link {
            color: white;
        }

        .navbar-custom .navbar-brand,
        .navbar-custom .navbar-text {
            color: white;
        }

        .navbar-text {
            margin-left: auto;
            font-size: 1.2rem;
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

    <div class="container mt-5">
        {{-- <h2 class="text-center mb-4">Ujian</h2> --}}
        <div class="row">
            {{-- @foreach ($ujianAktif as $ujian) --}}
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-body text-center">
                        <h5 class="card-title">Situasional Judgement Test ({{ $role }})</h5>
                        <p class="card-text">Tanggal Mulai: {{ $start_exam }}</p>
                        <p class="card-text">Tanggal Akhir: {{ $end_exam }}</p>
                        <p class="card-text">Waktu: {{ $time_limit }} Menit</p>
                        <p class="card-text">Status: {{ ucfirst($status) }}</p>

                        @if ($statusMessage)
                            <p class="card-text">{{ $statusMessage }}</p>
                        @else
                            @if ($status === 'not_started')
                                <a href="{{ route('examPage') }}" class="btn btn-primary"
                                    style="background-color: #005689;" id="start-exam-btn">Mulai Ujian</a>
                            @elseif ($status === 'on_going')
                                <a href="{{ route('examPage') }}" class="btn btn-primary"
                                    style="background-color: #005689;" id="submit-exam-btn">Lanjutkan Ujian</a>
                            @elseif ($status === 'submitted')
                                <p class="card-text">Ujian sudah disubmit. Terima kasih atas partisipasi anda.</p>
                            @endif
                        @endif

                    </div>
                </div>
            </div>
            {{-- @endforeach --}}
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
        document.addEventListener('DOMContentLoaded', function() {
            const logoutBtn = document.getElementById('logout-btn');
            const startExam = document.getElementById('start-exam-btn');

            if (startExam) {
                startExam.addEventListener('click', function(event) {
                    event.preventDefault();

                    Swal.fire({
                        title: 'Anda yakin ingin memulai ujian?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, mulai!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = "{{ route('examPage') }}";
                        }
                    });
                });
            }

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
        });
    </script>
</body>

</html>
