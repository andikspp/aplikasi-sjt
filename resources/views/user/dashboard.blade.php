@extends('layout.user.user-layout')

@section('title', 'Dashboard')

@section('content')
    <style>
        body {
            background: linear-gradient(135deg, #e3f2fd 0%, #f8f9fa 100%);
        }

        .dashboard-card {
            border-radius: 18px;
            box-shadow: 0 4px 24px rgba(0, 86, 137, 0.08);
            border: none;
            margin-bottom: 32px;
        }

        .card-header {
            background: linear-gradient(90deg, #005689 70%, #0077b6 100%);
            color: #fff;
            font-weight: bold;
            border-radius: 18px 18px 0 0;
            padding: 1.2rem 1.5rem;
        }

        .avatar {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #005689;
            margin-right: 18px;
        }

        .info-item i {
            color: #005689;
            font-size: 1.5rem;
            margin-right: 14px;
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
        }

        .btn-custom:hover {
            background: linear-gradient(90deg, #0077b6 70%, #005689 100%);
            color: #fff;
        }

        .progress {
            height: 18px;
            border-radius: 10px;
        }

        .progress-bar {
            background-color: #005689;
        }

        .welcome-title {
            font-weight: 700;
            font-size: 2rem;
            margin-bottom: 10px;
        }
    </style>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="dashboard-card card shadow-lg">
                    <div class="card-header d-flex align-items-center">
                        <div>
                            <div class="welcome-title">Selamat Datang, {{ $user->name }}</div>
                            <span class="badge bg-primary">{{ ucwords($user->role) }}</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="info-item mb-3 d-flex align-items-center">
                            <i class="fas fa-user"></i>
                            <span>Nama: <strong>{{ $user->name }}</strong></span>
                        </div>
                        <div class="info-item mb-3 d-flex align-items-center">
                            <i class="fas fa-envelope"></i>
                            <span>Username: <strong>{{ $user->username }}</strong></span>
                        </div>
                        <div class="info-item mb-3 d-flex align-items-center">
                            <i class="fas fa-home"></i>
                            <span>Instansi: <strong>{{ strtoupper($user->instansi) }}</strong></span>
                        </div>
                    </div>
                </div>

                <div class="dashboard-card card shadow-lg">
                    <div class="card-header">
                        <h5 class="mb-0">Riwayat Ujian</h5>
                    </div>
                    <div class="card-body">
                        @if ($quizAttempt)
                            <div class="info-item d-flex align-items-center mb-3">
                                <i class="fas fa-calendar-alt"></i>
                                <span>Waktu Selesai Submit: <strong>{{ $endedAt->format('d-m-Y, H:i:s') }}
                                        WIB</strong></span>
                            </div>
                            {{-- Contoh progress bar jika ingin tampilkan progress --}}
                            <div class="mb-3">
                                <label class="form-label">Progress Ujian</label>
                                <div class="progress">
                                    <div class="progress-bar" role="progressbar" style="width: 100%">Selesai</div>
                                </div>
                            </div>
                        @else
                            <div class="text-center text-muted mb-0">
                                <i class="fas fa-info-circle fa-2x mb-2"></i>
                                <div>Belum ada riwayat submit.</div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ route('exam') }}">
                        <button class="btn btn-custom shadow">Mulai Ujian <i class="fas fa-arrow-right ms-2"></i></button>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
