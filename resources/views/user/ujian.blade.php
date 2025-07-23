@extends('layout.user.user-layout')

@section('title', 'Pengerjaan Aktif')

@section('content')
    <style>
        body {
            background: linear-gradient(135deg, #e3f2fd 0%, #f8f9fa 100%);
        }

        .card {
            border-radius: 18px;
            box-shadow: 0 4px 24px rgba(0, 86, 137, 0.10);
            border: none;
        }

        .card-title i {
            font-size: 2.2rem;
            color: #005689;
            margin-bottom: 8px;
        }

        .card-title {
            font-weight: 700;
            font-size: 1.5rem;
            color: #005689;
        }

        .card-text i {
            color: #0077b6;
            font-size: 1.2rem;
            margin-right: 6px;
        }

        .badge-status {
            font-size: 1rem;
            padding: 0.5em 1.2em;
            border-radius: 20px;
            font-weight: 600;
        }

        .badge-not_started {
            background: #f1c40f;
            color: #fff;
        }

        .badge-on_going {
            background: #0077b6;
            color: #fff;
        }

        .badge-submitted {
            background: #27ae60;
            color: #fff;
        }

        .btn-custom {
            background: linear-gradient(90deg, #005689 70%, #0077b6 100%);
            color: white;
            font-size: 1.2rem;
            padding: 0.7rem 2.5rem;
            border-radius: 30px;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(0, 86, 137, 0.12);
            transition: background 0.2s;
            border: none;
        }

        .btn-custom:hover {
            background: linear-gradient(90deg, #0077b6 70%, #005689 100%);
            color: #fff;
        }

        .btn-warning {
            background: linear-gradient(90deg, #f1c40f 70%, #f39c12 100%);
            color: #fff;
            border: none;
        }

        .btn-warning:hover {
            background: linear-gradient(90deg, #f39c12 70%, #f1c40f 100%);
            color: #fff;
        }

        .alert-info {
            border-radius: 10px;
            background: #e3f2fd;
            color: #005689;
            font-weight: 500;
        }
    </style>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-7 col-md-10">
                <div class="card mb-4 shadow-lg">
                    <div class="card-body text-center">
                        <h5 class="card-title mb-3">
                            <i class="fas fa-clipboard-list"></i><br>
                            Pemaknaan Awal ({{ ucwords($role) }})
                        </h5>
                        <div class="mb-2">
                            <span class="card-text text-muted">
                                <i class="fas fa-calendar-day"></i> Tanggal Mulai:
                                <strong>{{ \Carbon\Carbon::parse($start_exam)->format('d-m-Y H:i') }} WIB</strong>
                            </span>
                        </div>
                        <div class="mb-2">
                            <span class="card-text text-muted">
                                <i class="fas fa-calendar-alt"></i> Tanggal Akhir:
                                <strong>{{ \Carbon\Carbon::parse($end_exam)->format('d-m-Y H:i') }} WIB</strong>
                            </span>
                        </div>
                        <div class="mb-4">
                            <span class="card-text">
                                <i class="fas fa-info-circle"></i> Status:
                                <span class="badge badge-status badge-{{ $status }}">
                                    {{ ucwords(str_replace('_', ' ', $status)) }}
                                </span>
                            </span>
                        </div>

                        @if ($statusMessage)
                            <div class="alert alert-info mb-4" role="alert">
                                <i class="fas fa-info-circle"></i> {{ $statusMessage }}
                            </div>
                        @else
                            @if ($status === 'not_started')
                                <a href="{{ route('examPage') }}" class="btn btn-custom btn-lg" id="start-exam-btn">
                                    <i class="fas fa-play"></i> Mulai
                                </a>
                            @elseif ($status === 'on_going')
                                <a href="{{ route('examPage') }}" class="btn btn-warning btn-lg" id="submit-exam-btn">
                                    <i class="fas fa-arrow-right"></i> Lanjutkan
                                </a>
                            @elseif ($status === 'submitted')
                                <p class="card-text text-success mt-3">
                                    <i class="fas fa-check-circle"></i> Hasil sudah disubmit. Terima kasih atas partisipasi
                                    anda.
                                </p>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const startExam = document.getElementById('start-exam-btn');
            if (startExam) {
                startExam.addEventListener('click', function(event) {
                    event.preventDefault();
                    Swal.fire({
                        title: 'Anda yakin ingin memulai sesi?',
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
            const submitExam = document.getElementById('submit-exam-btn');
            if (submitExam) {
                submitExam.addEventListener('click', function(event) {
                    event.preventDefault();
                    Swal.fire({
                        title: 'Lanjutkan sesi ujian?',
                        icon: 'info',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, lanjutkan!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = "{{ route('examPage') }}";
                        }
                    });
                });
            }
        });
    </script>
@endsection
