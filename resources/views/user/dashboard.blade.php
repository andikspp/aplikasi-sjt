@extends('layout.user.user-layout')

@section('title', 'Dashboard')

@section('content')
    <style>
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



        .btn-custom {
            background-color: #005689;
            color: white;
        }

        .btn-custom:hover {
            background-color: #012a41;
            color: white;
        }
    </style>
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
                            <p class="mb-0">Username: <strong>{{ $user->username }}</strong></p>
                        </div>
                        <div class="info-item mb-3 d-flex align-items-center">
                            <i class="fas fa-phone fa-lg mr-3"></i>
                            <p class="mb-0">Nomor Telepon: <strong>{{ $user->telepon }}</strong></p>
                        </div>
                        <div class="info-item mb-3 d-flex align-items-center">
                            <i class="fas fa-home fa-lg mr-3"></i>
                            <p class="mb-0">Satuan PAUD: <strong>{{ strtoupper($user->instansi) }}</strong></p>
                        </div>
                    </div>
                </div>

                <div class="card mt-4 shadow-sm">
                    <div class="card-header text-white">
                        <h5 class="mb-0">Riwayat</h5>
                    </div>
                    <div class="card-body">
                        @if ($quizAttempt)
                            <div class="info-item d-flex align-items-center mb-3">
                                <i class="fas fa-calendar-alt fa-lg mr-3"></i>
                                <p class="mb-0">Waktu Selesai Submit: <strong>{{ $endedAt->format('d-m-Y, H:i:s') }}
                                        WIB</strong></p>
                            </div>
                        @else
                            <p class="text-center mb-0">Belum ada riwayat ujian.</p>
                        @endif
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ route('exam') }}"><button class="btn btn-custom">Mulai</button></a>
                </div>
            </div>
        </div>
    </div>
@endsection
