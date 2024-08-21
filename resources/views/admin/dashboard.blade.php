@extends('layout.admin.admin-layout')

@section('title', 'Dashboard')

@section('content')
    <!-- Main Content -->
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <h3>Selamat Datang, {{ $admin->username }}</h3>
                <div class="mt-4">
                    <div class="row">
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card text-white bg-primary">
                                <div class="card-header">Total Peserta</div>
                                <div class="card-body">
                                    <h5 class="card-title">{{ $jumlahUser }}</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card text-white bg-success">
                                <div class="card-header">Total Guru</div>
                                <div class="card-body">
                                    <h5 class="card-title">{{ $jumlahGuru }}</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card text-white bg-warning">
                                <div class="card-header">Total Kepala Sekolah</div>
                                <div class="card-body">
                                    <h5 class="card-title">{{ $jumlahKepalaSekolah }}</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card text-white bg-info">
                                <div class="card-header">Total Instansi</div>
                                <div class="card-body">
                                    <h5 class="card-title">{{ $jumlahInstansi }}</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card text-white bg-danger">
                                <div class="card-header">Total Peserta Submit</div>
                                <div class="card-body">
                                    <h5 class="card-title">{{ $jumlahUjianSelesai }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
