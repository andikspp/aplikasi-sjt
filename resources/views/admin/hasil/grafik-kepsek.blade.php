@extends('layout.admin.admin-layout')

@section('title', 'Grafik Kepala Sekolah')

@section('content')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/animate.css@4.1.1/animate.min.css" rel="stylesheet">

    <style>
        :root {
            --primary-color: #005689;
            --primary-hover: #004a73;
            --secondary-color: #6c757d;
            --success-color: #28a745;
            --info-color: #17a2b8;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
            --light-color: #f8f9fa;
            --dark-color: #343a40;
            --border-radius: 20px;
            --box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            --transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            --gradient-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --gradient-secondary: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --gradient-success: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --gradient-kepsek: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
        }

        body {
            background: var(--gradient-kepsek);
            min-height: 100vh;
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--dark-color);
        }

        .main-wrapper {
            background: rgba(255, 255, 255, 0.95);
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            margin: 20px;
            overflow: hidden;
        }

        .header-section {
            background: var(--gradient-kepsek);
            padding: 40px 30px;
            position: relative;
            overflow: hidden;
        }

        .header-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, rgba(255, 255, 255, 0.1), transparent);
            pointer-events: none;
        }

        .header-content {
            position: relative;
            z-index: 2;
            text-align: center;
            color: white;
        }

        .header-title {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 15px;
            text-shadow: 2px 4px 8px rgba(0, 0, 0, 0.3);
            letter-spacing: -0.5px;
        }

        .header-subtitle {
            font-size: 1.2rem;
            opacity: 0.9;
            font-weight: 300;
            margin-bottom: 0;
        }

        .back-button {
            position: absolute;
            top: 30px;
            left: 30px;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50px;
            padding: 12px 25px;
            font-weight: 600;
            text-decoration: none;
            transition: var(--transition);
            backdrop-filter: blur(10px);
            z-index: 3;
        }

        .back-button:hover {
            background: rgba(255, 255, 255, 0.3);
            color: white;
            transform: translateX(-5px);
            border-color: rgba(255, 255, 255, 0.5);
        }

        .stats-overview {
            padding: 40px 30px;
            background: linear-gradient(135deg, #f8f9ff, #ffffff);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: white;
            padding: 30px 25px;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            text-align: center;
            transition: var(--transition);
            border: 1px solid rgba(0, 0, 0, 0.05);
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient-kepsek);
        }

        .stat-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            background: var(--gradient-kepsek);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 1.8rem;
            color: white;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 8px;
        }

        .stat-label {
            font-size: 1rem;
            color: var(--secondary-color);
            font-weight: 500;
        }

        .charts-section {
            padding: 0 30px 40px;
        }

        .section-title {
            text-align: center;
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 40px;
            color: var(--dark-color);
            position: relative;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: var(--gradient-kepsek);
            border-radius: 2px;
        }

        .chart-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(450px, 1fr));
            gap: 30px;
            margin-bottom: 30px;
        }

        .chart-card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            overflow: hidden;
            transition: var(--transition);
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .chart-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 25px 45px rgba(0, 0, 0, 0.15);
        }

        .chart-header {
            background: var(--gradient-kepsek);
            padding: 25px 30px;
            text-align: center;
            position: relative;
        }

        .chart-title {
            font-size: 1.4rem;
            font-weight: 600;
            color: white;
            margin: 0;
            text-shadow: 1px 2px 4px rgba(0, 0, 0, 0.2);
        }

        .chart-body {
            padding: 40px 30px;
            background: linear-gradient(135deg, #ffffff, #f8f9ff);
        }

        .chart-container {
            width: 100%;
            height: 400px;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .chart-container canvas {
            max-width: 100%;
            max-height: 100%;
        }

        .additional-charts {
            display: grid;
            grid-template-columns: 1fr;
            gap: 30px;
            margin-top: 30px;
        }

        .trend-chart-card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            overflow: hidden;
        }

        .trend-chart-header {
            background: var(--gradient-secondary);
            padding: 25px 30px;
            text-align: center;
        }

        .trend-chart-body {
            padding: 40px 30px;
            background: linear-gradient(135deg, #fff5f5, #ffffff);
        }

        .floating-actions {
            position: fixed;
            bottom: 30px;
            right: 30px;
            display: flex;
            flex-direction: column;
            gap: 15px;
            z-index: 1000;
        }

        .floating-btn {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            border: none;
            color: white;
            font-size: 1.5rem;
            box-shadow: var(--box-shadow);
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        .floating-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        }

        .floating-btn.download {
            background: var(--gradient-success);
        }

        .floating-btn.print {
            background: var(--gradient-secondary);
        }

        .floating-btn.top {
            background: var(--gradient-kepsek);
        }

        .insights-section {
            padding: 30px;
            background: linear-gradient(135deg, #f0f4ff, #ffffff);
            border-top: 1px solid rgba(0, 0, 0, 0.1);
        }

        .insights-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
        }

        .insight-card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            border-left: 4px solid var(--primary-color);
            transition: var(--transition);
        }

        .insight-card:hover {
            transform: translateX(5px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.12);
        }

        .insight-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .insight-text {
            color: var(--secondary-color);
            line-height: 1.6;
            margin: 0;
        }

        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.95);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            backdrop-filter: blur(5px);
        }

        .spinner {
            width: 60px;
            height: 60px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .pulse-animation {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }

            100% {
                transform: scale(1);
            }
        }

        /* Custom Chart Styles */
        .chart-legend {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 20px;
            margin-top: 20px;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .legend-color {
            width: 16px;
            height: 16px;
            border-radius: 3px;
        }

        @media (max-width: 768px) {
            .main-wrapper {
                margin: 10px;
            }

            .header-section {
                padding: 30px 20px;
            }

            .header-title {
                font-size: 2.2rem;
            }

            .back-button {
                position: relative;
                top: auto;
                left: auto;
                margin-bottom: 20px;
                display: inline-block;
            }

            .stats-overview,
            .charts-section {
                padding: 30px 20px;
            }

            .chart-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .chart-container {
                height: 300px;
            }

            .floating-actions {
                position: relative;
                bottom: auto;
                right: auto;
                flex-direction: row;
                justify-content: center;
                margin: 30px 20px;
            }

            .floating-btn {
                position: relative;
            }
        }
    </style>

    <div class="loading-overlay" id="loadingOverlay">
        <div class="spinner"></div>
    </div>

    <div class="main-wrapper animate__animated animate__fadeIn">
        <!-- Header Section -->
        <div class="header-section">
            <a href="{{ route('hasil.kepala_sekolah') }}" class="back-button animate__animated animate__fadeInLeft">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>

            <div class="header-content animate__animated animate__fadeInDown">
                <h1 class="header-title">
                    <i class="fas fa-chart-pie me-3"></i>
                    Analisis Grafik
                </h1>
                <p class="header-subtitle">
                    Visualisasi Data Skor Kepala Sekolah dalam Bentuk Grafik Interaktif
                </p>
            </div>
        </div>

        <!-- Statistics Overview -->
        <div class="stats-overview">
            <div class="stats-grid animate__animated animate__fadeInUp">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-users-cog"></i>
                    </div>
                    <div class="stat-number" id="totalKepsek">{{ $jumlahKs }}</div>
                    <div class="stat-label">Total Responden</div>
                </div>

                @php
                    $avgScore = 0;
                    if (count($scoreByCompetency) > 0) {
                        $avgScore = array_sum($scoreByCompetency->toArray()) / count($scoreByCompetency);
                    }
                @endphp
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <div class="stat-number" id="topScore">{{ max(array_keys($scoreData)) }}</div>
                    <div class="stat-label">Skor Tertinggi</div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-cogs"></i>
                    </div>
                    <div class="stat-number" id="totalComps">{{ count($scoreByCompetency) }}</div>
                    <div class="stat-label">Kompetensi</div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="charts-section">
            <h2 class="section-title animate__animated animate__fadeInUp">
                Visualisasi Data
            </h2>

            <div class="chart-grid">
                <!-- Pie Chart -->
                <div class="chart-card animate__animated animate__fadeInLeft">
                    <div class="chart-header">
                        <h3 class="chart-title">
                            <i class="fas fa-chart-pie me-2"></i>
                            Distribusi Skor
                        </h3>
                    </div>
                    <div class="chart-body">
                        <div class="chart-container">
                            <canvas id="scorePieChart"></canvas>
                        </div>
                        <div class="chart-legend" id="pieChartLegend"></div>
                    </div>
                </div>

                <!-- Bar Chart -->
                @if (count($scoreByCompetency) > 0)
                    <div class="chart-card animate__animated animate__fadeInRight">
                        <div class="chart-header">
                            <h3 class="chart-title">
                                <i class="fas fa-chart-bar me-2"></i>
                                Rata-rata Skor per Kompetensi
                            </h3>
                        </div>
                        <div class="chart-body">
                            <div class="chart-container">
                                <canvas id="scoreBarChart"></canvas>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="chart-card animate__animated animate__fadeInRight">
                        <div class="chart-body">
                            <div class="text-center py-5">
                                <i class="fas fa-info-circle text-muted" style="font-size: 3rem;"></i>
                                <h5 class="mt-3 text-muted">Kompetensi Tidak Tersedia</h5>
                                <p class="text-muted">Data kompetensi belum tersedia atau kosong.</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Additional Charts -->
            @if (count($scoreByCompetency) > 0)
                <div class="additional-charts">
                    <div class="trend-chart-card animate__animated animate__fadeInUp">
                        <div class="trend-chart-header">
                            <h3 class="chart-title">
                                <i class="fas fa-chart-area me-2"></i>
                                Analisis Performa Detail Kepala Sekolah
                            </h3>
                        </div>
                        <div class="trend-chart-body">
                            <div class="chart-container">
                                <canvas id="performanceChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Insights Section -->
        <div class="insights-section animate__animated animate__fadeInUp">
            <h3 class="section-title" style="font-size: 1.8rem;">Insight & Analisis Kepala Sekolah</h3>
            <div class="insights-grid">
                <div class="insight-card">
                    <h4 class="insight-title">
                        <i class="fas fa-lightbulb text-warning"></i>
                        Kompetensi Terbaik
                    </h4>
                    <p class="insight-text">
                        @php
                            $maxCompetency = 'Belum tersedia';
                            $maxScore = 0;
                            if (count($scoreByCompetency) > 0) {
                                $maxCompetency = $scoreByCompetency->keys()->first();
                                $maxScore = $scoreByCompetency->first();
                                foreach ($scoreByCompetency as $comp => $score) {
                                    if ($score > $maxScore) {
                                        $maxCompetency = $comp;
                                        $maxScore = $score;
                                    }
                                }
                            }
                        @endphp
                        <strong>{{ $maxCompetency }}</strong> menunjukkan performa terbaik dengan rata-rata skor
                        <strong>{{ number_format($maxScore, 2) }}</strong> dari 4.0.
                    </p>
                </div>

                <div class="insight-card">
                    <h4 class="insight-title">
                        <i class="fas fa-target text-success"></i>
                        Metodologi
                    </h4>
                    <p class="insight-text">
                        Grafik menampilkan <strong>rata-rata skor</strong> per kompetensi untuk memberikan perbandingan yang
                        adil antar kompetensi dengan jumlah soal berbeda.
                    </p>
                </div>

                <div class="insight-card">
                    <h4 class="insight-title">
                        <i class="fas fa-chart-line text-info"></i>
                        Interpretasi Skor
                    </h4>
                    <p class="insight-text">
                        Skor 4.0 = Sangat Baik, 3.0 = Baik, 2.0 = Cukup, 1.0 = Perlu Perbaikan.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Floating Action Buttons -->
    <div class="floating-actions animate__animated animate__fadeInRight">
        <button class="floating-btn download" onclick="downloadCharts()" title="Download Charts">
            <i class="fas fa-download"></i>
        </button>
        <button class="floating-btn print" onclick="printCharts()" title="Print Charts">
            <i class="fas fa-print"></i>
        </button>
        <button class="floating-btn top" onclick="scrollToTop()" title="Scroll to Top">
            <i class="fas fa-arrow-up"></i>
        </button>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Show loading overlay initially
            document.getElementById('loadingOverlay').style.display = 'flex';

            // Data from server
            const scoreData = @json($scoreData);
            const scoreByCompetency = @json($scoreByCompetency ?? []); // Rata-rata skor
            const percentageByCompetency = @json($percentageByCompetency ?? []); // Persentase
            const questionCountByCompetency = @json($questionCountByCompetency ?? []); // Jumlah soal

            // Color schemes untuk kepala sekolah
            const colors = {
                primary: ['#764ba2', '#667eea', '#f093fb', '#f5576c'],
                success: ['#4facfe', '#00f2fe', '#43e97b', '#38f9d7'],
                gradient: ['#ff9a9e', '#fecfef', '#fecfef', '#fecfef']
            };

            // Chart configurations
            const chartOptions = {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: 'rgba(255, 255, 255, 0.2)',
                        borderWidth: 1,
                        cornerRadius: 8,
                        displayColors: true
                    }
                },
                animation: {
                    duration: 2000,
                    easing: 'easeOutQuart'
                }
            };

            // Pie Chart (selalu tampil)
            const ctxPie = document.getElementById('scorePieChart').getContext('2d');
            const pieChart = new Chart(ctxPie, {
                type: 'doughnut',
                data: {
                    labels: ['Score 4', 'Score 3', 'Score 2', 'Score 1'],
                    datasets: [{
                        data: [
                            scoreData['4'] || 0,
                            scoreData['3'] || 0,
                            scoreData['2'] || 0,
                            scoreData['1'] || 0
                        ],
                        backgroundColor: [
                            '#4caf50',
                            '#ffeb3b',
                            '#f57c00',
                            '#f44336'
                        ],
                        borderWidth: 3,
                        borderColor: '#fff',
                        hoverBorderWidth: 5,
                        hoverOffset: 10
                    }]
                },
                options: {
                    ...chartOptions,
                    cutout: '60%',
                    plugins: {
                        ...chartOptions.plugins,
                        datalabels: {
                            color: '#fff',
                            formatter: (value, context) => {
                                const total = context.chart.data.datasets[0].data.reduce((acc, val) =>
                                    acc + val, 0);
                                if (total === 0) return '0%';
                                const percentage = ((value / total) * 100).toFixed(1);
                                return value > 0 ? percentage + '%' : '';
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

            // Bar Chart - hanya jika ada data kompetensi
            if (Object.keys(scoreByCompetency).length > 0) {
                const ctxBar = document.getElementById('scoreBarChart').getContext('2d');
                const barChart = new Chart(ctxBar, {
                    type: 'bar',
                    data: {
                        labels: Object.keys(scoreByCompetency),
                        datasets: [{
                            label: 'Rata-rata Skor',
                            data: Object.values(scoreByCompetency),
                            backgroundColor: colors.primary.map(color => color + '80'),
                            borderColor: colors.primary,
                            borderWidth: 2,
                            borderRadius: 8,
                            borderSkipped: false,
                        }]
                    },
                    options: {
                        ...chartOptions,
                        plugins: {
                            ...chartOptions.plugins,
                            datalabels: {
                                color: '#333',
                                anchor: 'end',
                                align: 'top',
                                font: {
                                    weight: 'bold',
                                    size: 12
                                },
                                formatter: (value) => value.toFixed(2) // Tampilkan 2 desimal
                            },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                titleColor: '#fff',
                                bodyColor: '#fff',
                                callbacks: {
                                    afterBody: function(tooltipItems) {
                                        const index = tooltipItems[0].dataIndex;
                                        const competency = Object.keys(scoreByCompetency)[index];
                                        const questionCount = questionCountByCompetency[competency] ||
                                            0;
                                        const percentage = percentageByCompetency[competency] || 0;
                                        return `Jumlah soal: ${questionCount}\nPersentase capaian: ${percentage}%`;
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    font: {
                                        size: 12,
                                        weight: 'bold'
                                    },
                                    color: '#666'
                                }
                            },
                            y: {
                                beginAtZero: true,
                                max: 4, // Skor maksimal adalah 4
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.05)'
                                },
                                ticks: {
                                    font: {
                                        size: 12
                                    },
                                    color: '#666',
                                    stepSize: 0.5
                                }
                            }
                        }
                    },
                    plugins: [ChartDataLabels]
                });

                // Performance Chart (Radar) - hanya jika ada data kompetensi
                const ctxPerf = document.getElementById('performanceChart').getContext('2d');
                const performanceChart = new Chart(ctxPerf, {
                    type: 'radar',
                    data: {
                        labels: Object.keys(scoreByCompetency),
                        datasets: [{
                            label: 'Rata-rata Skor Kompetensi Kepala Sekolah',
                            data: Object.values(scoreByCompetency),
                            backgroundColor: 'rgba(118, 75, 162, 0.2)',
                            borderColor: 'rgba(118, 75, 162, 1)',
                            pointBackgroundColor: 'rgba(118, 75, 162, 1)',
                            pointBorderColor: '#fff',
                            pointHoverBackgroundColor: '#fff',
                            pointHoverBorderColor: 'rgba(118, 75, 162, 1)',
                            borderWidth: 3,
                            pointRadius: 6,
                            pointHoverRadius: 8
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top',
                                labels: {
                                    font: {
                                        size: 14,
                                        weight: 'bold'
                                    },
                                    color: '#333'
                                }
                            }
                        },
                        scales: {
                            r: {
                                beginAtZero: true,
                                max: 4, // Skor maksimal 4
                                ticks: {
                                    stepSize: 0.5,
                                    display: true,
                                    font: {
                                        size: 10
                                    },
                                    color: '#666'
                                },
                                angleLines: {
                                    color: 'rgba(0, 0, 0, 0.1)'
                                },
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.1)'
                                },
                                pointLabels: {
                                    font: {
                                        size: 12,
                                        weight: 'bold'
                                    },
                                    color: '#666'
                                }
                            }
                        },
                        animation: {
                            duration: 2500,
                            easing: 'easeOutQuart'
                        }
                    }
                });
            }

            // Create custom legend for pie chart
            function createPieChartLegend() {
                const legendContainer = document.getElementById('pieChartLegend');
                const labels = pieChart.data.labels;
                const data = pieChart.data.datasets[0].data;
                const colors = pieChart.data.datasets[0].backgroundColor;

                legendContainer.innerHTML = '';

                labels.forEach((label, index) => {
                    if (data[index] > 0) {
                        const legendItem = document.createElement('div');
                        legendItem.className = 'legend-item';
                        legendItem.innerHTML = `
                    <div class="legend-color" style="background-color: ${colors[index]}"></div>
                    <span>${label}: ${data[index]}</span>
                `;
                        legendContainer.appendChild(legendItem);
                    }
                });
            }

            // Functions
            window.downloadCharts = function() {
                Swal.fire({
                    title: 'Download Grafik',
                    text: 'Fitur download akan segera tersedia',
                    icon: 'info',
                    confirmButtonColor: '#764ba2'
                });
            };

            window.printCharts = function() {
                window.print();
            };

            window.scrollToTop = function() {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            };

            // Initialize animations and effects
            setTimeout(() => {
                document.getElementById('loadingOverlay').style.display = 'none';
                createPieChartLegend();

                // Add pulse effect to stat numbers
                document.querySelectorAll('.stat-number').forEach(el => {
                    el.classList.add('pulse-animation');
                });

                // Success notification
                const toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });

                toast.fire({
                    icon: 'success',
                    title: 'Grafik Kepala Sekolah berhasil dimuat!'
                });
            }, 2000);

            // Animate numbers
            function animateNumbers() {
                const statNumbers = document.querySelectorAll('.stat-number');
                statNumbers.forEach(el => {
                    const target = parseFloat(el.textContent);
                    let current = 0;
                    const increment = target / 50;

                    const timer = setInterval(() => {
                        current += increment;
                        if (current >= target) {
                            if (el.id === 'avgScore') {
                                el.textContent = target.toFixed(1);
                            } else {
                                el.textContent = Math.floor(target);
                            }
                            clearInterval(timer);
                        } else {
                            if (el.id === 'avgScore') {
                                el.textContent = current.toFixed(1);
                            } else {
                                el.textContent = Math.floor(current);
                            }
                        }
                    }, 30);
                });
            }

            // Start number animation after loading
            setTimeout(animateNumbers, 2200);
        });
    </script>

@endsection
