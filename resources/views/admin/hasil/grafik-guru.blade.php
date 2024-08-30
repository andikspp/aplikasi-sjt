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

    <div class="container mt-5">
        <h2>Grafik Skor Jawaban Guru</h2>
        <div class="chart-container">
            <canvas id="scorePieChart"></canvas>
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
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((acc, value) => acc + value, 0);
                                const value = context.raw;
                                const percentage = ((value / total) * 100).toFixed(2) + '%';
                                return context.label + ': ' + value + ' (' + percentage + ')';
                            }
                        }
                    }
                }
            }
        });
    </script>
@endsection
