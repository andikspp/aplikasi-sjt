@extends('layout.admin.admin-layout')

@section('title', 'Grafik Individu')

@section('content')

    <style>
        .chart-container {
            width: 100%;
            max-width: 400px;
            height: 350px;
            margin: 0 auto;
        }

        .btn-dikmas {
            background-color: #005689;
            color: white;
        }

        .btn-dikmas:hover {
            background-color: #004a73;
            color: white;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

    <div class="container mt-5">
        <div class="mb-3">
            <a href="{{ route('jawaban.peserta', ['userId' => $userId]) }}" class="btn btn-dikmas">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
        <h2 class="mb-4 text-center">Grafik Skor Jawaban {{ $userName }}</h2>
        <div class="row justify-content-center">
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-body">
                        <h5 class="card-title text-center mb-3">Distribusi Skor</h5>
                        <div class="chart-container">
                            <canvas id="scorePieChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-body">
                        <h5 class="card-title text-center mb-3">Skor per Kompetensi</h5>
                        <div class="chart-container">
                            <canvas id="scoreBarChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const scoreData = @json($scoreData);
        const ctx = document.getElementById('scorePieChart').getContext('2d');
        const pieChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Score 4', 'Score 3', 'Score 2', 'Score 1'],
                datasets: [{
                    data: [
                        scoreData['4'] || 0,
                        scoreData['3'] || 0,
                        scoreData['2'] || 0,
                        scoreData['1'] || 0
                    ],
                    backgroundColor: ['#4caf50', '#ffeb3b', '#f57c00', '#f44336'],
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            font: {
                                size: 14
                            }
                        }
                    },
                    datalabels: {
                        color: '#fff',
                        formatter: (value, context) => {
                            const total = context.chart.data.datasets[0].data.reduce((acc, val) => acc + val,
                                0);
                            if (total === 0) return '0%';
                            const percentage = ((value / total) * 100).toFixed(1) + '%';
                            return percentage;
                        },
                        font: {
                            weight: 'bold',
                            size: 14
                        }
                    }
                }
            },
            plugins: [ChartDataLabels]
        });

        // Bar Chart
        const scoreByCompetency = @json($scoreByCompetency);
        const ctxBar = document.getElementById('scoreBarChart').getContext('2d');
        const barChart = new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: Object.keys(scoreByCompetency),
                datasets: [{
                    label: 'Total Skor',
                    data: Object.values(scoreByCompetency),
                    backgroundColor: '#3e95cd',
                    borderColor: '#1e88e5',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    datalabels: {
                        color: '#333',
                        anchor: 'end',
                        align: 'top',
                        font: {
                            weight: 'bold',
                            size: 13
                        }
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: {
                            font: {
                                size: 13
                            }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            font: {
                                size: 13
                            }
                        }
                    }
                }
            },
            plugins: [ChartDataLabels]
        });
    </script>
@endsection
