@extends('layout.admin.admin-layout')

@section('title', 'Grafik Guru')

@section('content')

    <style>
        .chart-container {
            width: 300px;
            height: 300px;
            margin: 0 auto;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

    <div class="container mt-5">
        <h2>Grafik Skor Jawaban Guru</h2>
        <div class="chart-container">
            <canvas id="scorePieChart"></canvas>
        </div>

        <div class="chart-container">
            <canvas id="scoreBarChart"></canvas>
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
                    datalabels: {
                        color: '#fff', // Warna teks label
                        formatter: (value, context) => {
                            const total = context.chart.data.datasets[0].data.reduce((acc, val) => acc + val,
                                0);
                            const percentage = ((value / total) * 100).toFixed(2) + '%';
                            return percentage; // Menampilkan persentase
                        },
                        font: {
                            weight: 'bold', // Membuat teks label menjadi tebal
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
                    label: 'Total Skor berdasarkan Kompetensi',
                    data: Object.values(scoreByCompetency),
                    backgroundColor: '#3e95cd',
                    borderColor: '#1e88e5',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    datalabels: {
                        color: '#fff',
                        formatter: (value) => {
                            return value;
                        },
                        font: {
                            weight: 'bold',
                        }
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true
                    }
                }
            },
            plugins: [ChartDataLabels]
        });
    </script>
@endsection
