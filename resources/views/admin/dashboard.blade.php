@extends('layout.admin.admin-layout')

@section('title', 'Dashboard')

@section('content')
    <!-- Main Content -->
    <div class="container mt-5">
        <h3 class="mb-4">Selamat Datang, {{ $admin->username }}</h3>
        <div class="row mb-4">
            <div class="col-12">
                <h5 class="mb-3">Statistik</h5>
            </div>
            <div class="col-sm-6 col-lg-4 mb-4">
                <div class="card shadow-sm border-0 rounded-4 text-white bg-primary h-100">
                    <div class="card-body d-flex align-items-center">
                        <i class="bi bi-people-fill fs-1 me-3"></i>
                        <div>
                            <div class="fw-bold">Total Peserta</div>
                            <h4 class="mb-0">{{ $jumlahUser }}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-4 mb-4">
                <div class="card shadow-sm border-0 rounded-4 text-white bg-success h-100">
                    <div class="card-body d-flex align-items-center">
                        <i class="bi bi-person-badge-fill fs-1 me-3"></i>
                        <div>
                            <div class="fw-bold">Total Guru</div>
                            <h4 class="mb-0">{{ $jumlahGuru }}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-4 mb-4">
                <div class="card shadow-sm border-0 rounded-4 text-white bg-warning h-100">
                    <div class="card-body d-flex align-items-center">
                        <i class="bi bi-person-check-fill fs-1 me-3"></i>
                        <div>
                            <div class="fw-bold">Total Kepala Sekolah</div>
                            <h4 class="mb-0">{{ $jumlahKepalaSekolah }}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-4 mb-4">
                <div class="card shadow-sm border-0 rounded-4 text-white bg-info h-100">
                    <div class="card-body d-flex align-items-center">
                        <i class="bi bi-building fs-1 me-3"></i>
                        <div>
                            <div class="fw-bold">Total Instansi</div>
                            <h4 class="mb-0">{{ $jumlahInstansi }}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-4 mb-4">
                <div class="card shadow-sm border-0 rounded-4 text-white bg-danger h-100">
                    <div class="card-body d-flex align-items-center">
                        <i class="bi bi-check2-circle fs-1 me-3"></i>
                        <div>
                            <div class="fw-bold">Total Peserta Submit</div>
                            <h4 class="mb-0">{{ $jumlahUjianSelesai }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-md-6 mb-4">
                <h6 class="mb-2">Grafik Perbandingan Total Peserta dan Total Peserta Submit</h6>
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-body">
                        <canvas id="pesertaChart" height="200"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <h6 class="mb-2">Grafik Perbandingan Jumlah Guru dan Kepala Sekolah</h6>
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-body">
                        <canvas id="guruChart" height="200"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <h6 class="mb-2">Grafik Perbandingan Jumlah PAUD Mitra dan PAUD Pembelajar</h6>
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-body">
                        <canvas id="instansiChart" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Peserta Chart
        new Chart(document.getElementById('pesertaChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: ['Total Peserta', 'Peserta Submit'],
                datasets: [{
                    label: 'Jumlah',
                    data: [{{ $jumlahUser }}, {{ $jumlahUjianSelesai }}],
                    backgroundColor: ['#36A2EB', '#FF6384'],
                    borderRadius: 5
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        // Guru & Kepala Sekolah Chart
        new Chart(document.getElementById('guruChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: ['Guru', 'Kepala Sekolah'],
                datasets: [{
                    label: 'Jumlah',
                    data: [{{ $jumlahGuru }}, {{ $jumlahKepalaSekolah }}],
                    backgroundColor: ['#4BC0C0', '#FFCE56'],
                    borderRadius: 5
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        // Instansi Chart (Pie per jenis PAUD)
        new Chart(document.getElementById('instansiChart').getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['PAUD Mitra', 'PAUD Pembelajar'],
                datasets: [{
                    data: [{{ $jumlahPaudMitra }}, {{ $jumlahPaudPembelajar }}],
                    backgroundColor: ['#36A2EB', '#FF6384']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true
                    }
                }
            }
        });
    </script>

@endsection
