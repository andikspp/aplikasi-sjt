<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Soal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.3/dist/sweetalert2.min.css" rel="stylesheet">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css">
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

        .btn-custom {
            background-color: #005689;
            color: white;
        }

        .btn-custom:hover {
            background-color: #012a41;
            color: white;
        }

        .bg-custom {
            background-color: #005689;
            color: white;
            ;
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
        <div class="row align-items-center">
            <div class="col">
                <h3>Paket Soal</h3>
            </div>
            <div class="col-auto">
                <a href="{{ route('create.QuestionSet') }}" class="btn btn-custom">Tambah Paket Soal</a>
            </div>
        </div>

        <div class="row mt-3">
            @foreach ($questionSets as $set)
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card border-light shadow-sm rounded-lg mb-4">
                            <div class="card-body">
                                <h5 class="card-title d-flex justify-content-between align-items-center">
                                    {{ $set->name }}
                                    <span class="badge bg-custom">{{ ucwords($set->role) }}</span>
                                </h5>
                                <p class="card-text text-muted mb-3">
                                    <i class="bi bi-calendar-event"></i> Mulai Ujian:
                                    {{ \Carbon\Carbon::parse($set->start_exam)->format('d-m-Y H:i') }} WIB<br>
                                    <i class="bi bi-calendar-check"></i> Selesai Ujian:
                                    {{ \Carbon\Carbon::parse($set->end_exam)->format('d-m-Y H:i') }} WIB<br>
                                    <i class="bi bi-file-text"></i> Jumlah Soal: {{ $set->questions->count() }}
                                </p>
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('admin.detail-soal', ['question_set_id' => $set->id]) }}"
                                        class="btn btn-custom">
                                        <i class="bi bi-eye"></i> Lihat Soal
                                    </a>
                                    <button class="btn btn-danger delete-set" data-id="{{ $set->id }}">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
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
            // Logout functionality
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

            // Delete question set functionality
            document.querySelectorAll('.delete-set').forEach(button => {
                button.addEventListener('click', function() {
                    const setId = this.getAttribute('data-id');

                    Swal.fire({
                        title: 'Anda yakin ingin menghapus paket soal ini?',
                        text: "Tindakan ini tidak dapat dibatalkan!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Send delete request
                            fetch(`#`, {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                    body: JSON.stringify({
                                        id: setId
                                    })
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        Swal.fire(
                                            'Terhapus!',
                                            'Paket soal telah dihapus.',
                                            'success'
                                        ).then(() => {
                                            location.reload();
                                        });
                                    } else {
                                        Swal.fire(
                                            'Error!',
                                            'Terjadi kesalahan saat menghapus paket soal.',
                                            'error'
                                        );
                                    }
                                });
                        }
                    });
                });
            });
        });

        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
            });
        @endif
    </script>
</body>

</html>
