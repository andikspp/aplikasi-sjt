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
                <div class="mt-5">
                    <canvas id="resultsChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('resultsChart').getContext('2d');
        const resultsChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Total Peserta', 'Total Guru', 'Total Kepala Sekolah', 'Total Instansi',
                    'Total Peserta Submit'
                ],
                datasets: [{
                    label: 'Jumlah',
                    data: [{{ $jumlahUser }}, {{ $jumlahGuru }}, {{ $jumlahKepalaSekolah }},
                        {{ $jumlahInstansi }}, {{ $jumlahUjianSelesai }}
                    ],
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.8)',
                        'rgba(0, 255, 0)',
                        'rgba(255, 206, 86, 0.8)',
                        'rgba(0, 255, 255)',
                        'rgba(255, 99, 132, 0.8)'
                    ],
                    borderColor: [
                        'rgba(54, 162, 235, 1)',
                        'rgba(0, 255, 0)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(0, 255, 255)',
                        'rgba(255, 99, 132, 0.8)'
                    ],
                    borderWidth: 2,
                    borderRadius: 5,
                    hoverBackgroundColor: [
                        'rgba(54, 162, 235, 1)',
                        'rgba(0, 255, 0)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(0, 255, 255)',
                        'rgba(255, 99, 132, 0.8)'
                    ],
                    hoverBorderColor: [
                        'rgba(0, 0, 0, 1)',
                        'rgba(0, 0, 0, 1)',
                        'rgba(0, 0, 0, 1)',
                        'rgba(0, 0, 0, 1)',
                        'rgba(0, 0, 0, 1)'
                    ],
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                    },
                    tooltip: {
                        enabled: true,
                        backgroundColor: 'rgba(0, 0, 0, 0.7)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Jumlah',
                            font: {
                                size: 16
                            }
                        },
                        ticks: {
                            callback: function(value) {
                                return Math.floor(value); // Mengubah angka menjadi integer
                            }
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Kategori',
                            font: {
                                size: 16
                            }
                        }
                    }
                }
            }
        });
    </script>

@endsection
