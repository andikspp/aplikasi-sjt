@extends('layout.admin.admin-layout')

@section('title', 'Data Peserta')

@section('content')
    <style>
        .bg-custom {
            background-color: #005689;
            color: white;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .bg-custom:hover {
            background-color: white;
            color: #005689;
        }
    </style>

    <div class="container mt-5">
        <h2 class="text-center mb-4">Data Peserta</h2>
        <div class="row">
            <!-- Card for Guru -->
            <div class="col-md-6 mb-4">
                <a href="{{ route('data.guru') }}" class="card-link" style="text-decoration: none">
                    <div class="card bg-custom">
                        <div class="card-body text-center">
                            <h5 class="card-title">Data Guru</h5>
                            <p class="card-text">Klik untuk melihat data semua guru.</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Card for Kepala Sekolah -->
            <div class="col-md-6 mb-4">
                <a href="{{ route('data.kepala_sekolah') }}" class="card-link" style="text-decoration: none">
                    <div class="card bg-custom">
                        <div class="card-body text-center">
                            <h5 class="card-title">Data Kepala Sekolah</h5>
                            <p class="card-text">Klik untuk melihat data semua kepala sekolah.</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
@endsection
