@extends('layout.user.user-layout')

@section('title', 'Pengerjaan Aktif')

@section('content')
    <style>
        .card {
            border-radius: 10px;
            /* Membuat sudut kartu lebih membulat */
        }

        .btn-primary {
            border-radius: 25px;
            /* Membuat sudut tombol lebih membulat */
        }

        .btn-warning {
            border-radius: 25px;
            /* Membuat sudut tombol lebih membulat */
        }

        .alert-info {
            border-radius: 5px;
            /* Membuat sudut alert lebih membulat */
        }
    </style>

    <div class="container mt-5">
        {{-- <h2 class="text-center mb-4">Ujian</h2> --}}
        <div class="row">
            {{-- @foreach ($ujianAktif as $ujian) --}}
            <div class="col-md-12">
                <div class="card mb-4 shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title mb-3">
                            <i class="fas fa-clipboard-list mr-2"></i> Pemaknaan Awal ({{ ucwords($role) }})
                        </h5>
                        <p class="card-text text-muted mb-2">
                            <i class="fas fa-calendar-day mr-2"></i> Tanggal Mulai:
                            <strong>{{ \Carbon\Carbon::parse($start_exam)->format('d-m-Y H:i') }} WIB</strong>
                        </p>
                        <p class="card-text text-muted mb-2">
                            <i class="fas fa-calendar-alt mr-2"></i> Tanggal Akhir:
                            <strong>{{ \Carbon\Carbon::parse($end_exam)->format('d-m-Y H:i') }} WIB</strong>
                        </p>
                        <p class="card-text text-muted mb-4">
                            <i class="fas fa-info-circle mr-2"></i> Status:
                            <strong>{{ ucwords(str_replace('_', ' ', $status)) }}</strong>
                        </p>

                        @if ($statusMessage)
                            <div class="alert alert-info mb-4" role="alert">
                                <i class="fas fa-info-circle mr-2"></i>{{ $statusMessage }}
                            </div>
                        @else
                            @if ($status === 'not_started')
                                <a href="{{ route('examPage') }}" class="btn btn-primary btn-lg"
                                    style="background-color: #005689; border-color: #004a6b;" id="start-exam-btn">
                                    <i class="fas fa-play mr-2"></i>Mulai
                                </a>
                            @elseif ($status === 'on_going')
                                <a href="{{ route('examPage') }}" class="btn btn-warning btn-lg"
                                    style="background-color: #f1c40f; border-color: #d4ac0d;" id="submit-exam-btn">
                                    <i class="fas fa-arrow-right mr-2"></i>Lanjutkan
                                </a>
                            @elseif ($status === 'submitted')
                                <p class="card-text text-success mt-3">
                                    <i class="fas fa-check-circle mr-2"></i>Hasil sudah disubmit. Terima kasih atas
                                    partisipasi anda.
                                </p>
                            @endif
                        @endif
                    </div>
                </div>

            </div>
            {{-- @endforeach --}}
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const logoutBtn = document.getElementById('logout-btn');
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
@endsection
