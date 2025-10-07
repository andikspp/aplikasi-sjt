@extends('layout.admin.admin-layout')

@section('title', 'Detail Jawaban - ' . $userName)

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
            --border-radius: 12px;
            --box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .main-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            margin: 20px;
            padding: 30px;
        }

        .header-section {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-hover));
            color: white;
            padding: 30px;
            border-radius: var(--border-radius);
            margin-bottom: 30px;
            box-shadow: var(--box-shadow);
        }

        .header-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .header-subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
            margin-bottom: 0;
        }

        .stats-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 25px;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, rgba(255, 255, 255, 0.1), transparent);
            pointer-events: none;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        }

        .stat-icon {
            font-size: 2.5rem;
            margin-bottom: 15px;
            opacity: 0.8;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 0.9rem;
            opacity: 0.9;
        }

        .action-buttons {
            display: flex;
            gap: 15px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }

        .btn-custom {
            padding: 12px 25px;
            border-radius: 50px;
            font-weight: 600;
            text-decoration: none;
            transition: var(--transition);
            border: none;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .btn-custom::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s;
        }

        .btn-custom:hover::before {
            left: 100%;
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-hover));
            color: white;
        }

        .btn-success-custom {
            background: linear-gradient(135deg, var(--success-color), #218838);
            color: white;
        }

        .btn-info-custom {
            background: linear-gradient(135deg, var(--info-color), #138496);
            color: white;
        }

        .btn-warning-custom {
            background: linear-gradient(135deg, var(--warning-color), #e0a800);
            color: #212529;
        }

        .search-filter-section {
            background: white;
            padding: 25px;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            margin-bottom: 30px;
        }

        .search-input {
            border: 2px solid #e9ecef;
            border-radius: 50px;
            padding: 12px 20px;
            transition: var(--transition);
            width: 100%;
        }

        .search-input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(0, 86, 137, 0.25);
            outline: none;
        }

        .table-container {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            overflow: hidden;
            margin-bottom: 30px;
        }

        .table-responsive {
            border-radius: var(--border-radius);
        }

        .custom-table {
            margin-bottom: 0;
            border-collapse: separate;
            border-spacing: 0;
        }

        .custom-table thead th {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-hover));
            color: white;
            font-weight: 700;
            padding: 20px 15px;
            border: none;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .custom-table tbody tr {
            transition: var(--transition);
            border-bottom: 1px solid #e9ecef;
        }

        .custom-table tbody tr:hover {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
            transform: scale(1.01);
        }

        .custom-table td {
            padding: 18px 15px;
            vertical-align: middle;
            border: none;
            position: relative;
        }

        .question-text {
            max-width: 300px;
            word-wrap: break-word;
            line-height: 1.5;
        }

        .answer-text {
            max-width: 200px;
            word-wrap: break-word;
            font-weight: 600;
            color: var(--primary-color);
        }

        .competency-badge {
            background: linear-gradient(135deg, var(--info-color), #138496);
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-block;
        }

        .indicator-badge {
            background: linear-gradient(135deg, var(--success-color), #218838);
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-block;
        }

        .score-badge {
            font-size: 1.2rem;
            font-weight: 700;
            padding: 10px 15px;
            border-radius: 50px;
            display: inline-block;
            min-width: 60px;
            text-align: center;
        }

        .score-excellent {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
        }

        .score-good {
            background: linear-gradient(135deg, #17a2b8, #20c997);
            color: white;
        }

        .score-average {
            background: linear-gradient(135deg, #ffc107, #fd7e14);
            color: #212529;
        }

        .score-poor {
            background: linear-gradient(135deg, #dc3545, #e74c3c);
            color: white;
        }

        .pagination-container {
            background: white;
            padding: 20px;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            display: flex;
            justify-content: center;
        }

        .no-data {
            text-align: center;
            padding: 60px 20px;
            color: var(--secondary-color);
        }

        .no-data i {
            font-size: 4rem;
            margin-bottom: 20px;
            opacity: 0.5;
        }

        .back-button {
            position: fixed;
            top: 100px;
            left: 20px;
            z-index: 1000;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-hover));
            color: white;
            border: none;
            border-radius: 50px;
            padding: 12px 20px;
            box-shadow: var(--box-shadow);
            transition: var(--transition);
        }

        .back-button:hover {
            transform: translateX(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
            color: white;
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
        }

        .floating-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.3);
        }

        .progress-bar-container {
            background: white;
            padding: 20px;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            margin-bottom: 30px;
        }

        .progress-custom {
            height: 10px;
            border-radius: 50px;
            background: #e9ecef;
            overflow: hidden;
        }

        .progress-bar-custom {
            background: linear-gradient(135deg, var(--success-color), #20c997);
            border-radius: 50px;
            transition: width 1s ease-in-out;
        }

        .filter-stats {
            background: linear-gradient(135deg, #e3f2fd, #bbdefb) !important;
            border: 1px solid #2196f3 !important;
            border-radius: var(--border-radius);
            animation: slideDown 0.3s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .filter-stats .btn {
            border-radius: 20px;
            font-size: 0.85rem;
            padding: 5px 15px;
        }

        @media (max-width: 768px) {
            .main-container {
                margin: 10px;
                padding: 20px;
            }

            .header-title {
                font-size: 2rem;
            }

            .stats-cards {
                grid-template-columns: 1fr;
            }

            .action-buttons {
                flex-direction: column;
            }

            .btn-custom {
                text-align: center;
            }

            .back-button {
                position: relative;
                top: auto;
                left: auto;
                margin-bottom: 20px;
            }

            .floating-actions {
                position: relative;
                bottom: auto;
                right: auto;
                flex-direction: row;
                justify-content: center;
                margin-top: 20px;
            }
        }

        .score-summary {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef) !important;
            border: 1px solid #dee2e6;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .score-summary h6 {
            color: var(--primary-color);
            font-weight: 600;
        }

        .table-footer {
            font-weight: 600;
            border-top: 2px solid var(--primary-color) !important;
        }

        .table-footer .badge {
            font-size: 1rem !important;
            padding: 8px 12px !important;
        }

        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.9);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            backdrop-filter: blur(5px);
        }

        .spinner {
            width: 50px;
            height: 50px;
            border: 3px solid #f3f3f3;
            border-top: 3px solid var(--primary-color);
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

        .fade-in {
            animation: fadeIn 0.6s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>

    <div class="loading-overlay" id="loadingOverlay" style="display: none;">
        <div class="spinner"></div>
    </div>

    <!-- Back Button -->
    @if ($userRole === 'guru')
        <a href="{{ route('hasil.guru') }}" class="back-button animate__animated animate__fadeInLeft">
            <i class="fas fa-arrow-left me-2"></i> Kembali
        </a>
    @elseif ($userRole === 'kepala sekolah')
        <a href="{{ route('hasil.kepala_sekolah') }}" class="back-button animate__animated animate__fadeInLeft">
            <i class="fas fa-arrow-left me-2"></i> Kembali
        </a>
    @endif

    <div class="main-container animate__animated animate__fadeIn">
        <!-- Header Section -->
        <div class="header-section animate__animated animate__fadeInDown">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="header-title">
                        <i class="fas fa-user-graduate me-3"></i>
                        Detail Jawaban
                    </h1>
                    <p class="header-subtitle">
                        <i class="fas fa-user me-2"></i>{{ $userName }}
                    </p>
                </div>
                <div class="col-md-4 text-end">
                    <i class="fas fa-chart-line" style="font-size: 4rem; opacity: 0.3;"></i>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="stats-cards animate__animated animate__fadeInUp">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-question-circle"></i>
                </div>
                <div class="stat-number">{{ $answers->count() }}</div>
                <div class="stat-label">Total Pertanyaan</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-trophy"></i>
                </div>
                <div class="stat-number">{{ number_format($quizAttempt->score, 1) }}</div>
                <div class="stat-label">Total Skor Diperoleh</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-percentage"></i>
                </div>
                <div class="stat-number">
                    {{ number_format(($answers->sum('score') / ($answers->count() * 5)) * 100, 1) }}%
                </div>
                <div class="stat-label">Persentase Capaian</div>
            </div>
        </div>

        <!-- Progress Bar dengan Total Skor -->
        <div class="progress-bar-container animate__animated animate__fadeInLeft">
            <div class="row">
                <div class="col-md-8">
                    <h5 class="mb-3"><i class="fas fa-chart-bar me-2"></i>Progress Penyelesaian</h5>
                    <div class="progress-custom">
                        <div class="progress-bar-custom"
                            style="width: {{ ($answers->count() / max($answers->count(), 1)) * 100 }}%">
                        </div>
                    </div>
                    <div class="mt-2 text-muted">
                        {{ $answers->count() }} dari {{ $answers->count() }} jawaban ditampilkan
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="score-summary p-3 bg-light rounded">
                        <h6 class="text-center mb-2">
                            <i class="fas fa-calculator me-2 text-primary"></i>Ringkasan Skor
                        </h6>
                        <div class="text-center">
                            <div class="h4 text-primary mb-1">
                                {{ number_format($quizAttempt->score, 1) }} / {{ $answers->count() * 5 }}
                            </div>
                            <small class="text-muted">
                                Skor Diperoleh / Skor Maksimal
                            </small>
                        </div>
                        <hr class="my-2">
                        <div class="d-flex justify-content-between">
                            <small>Tertinggi:</small>
                            <small class="fw-bold text-success">{{ $answers->max('score') }}</small>
                        </div>
                        <div class="d-flex justify-content-between">
                            <small>Terendah:</small>
                            <small class="fw-bold text-danger">{{ $answers->min('score') }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons animate__animated animate__fadeInRight">
            <a href="{{ route('grafik.individu', ['userId' => $userId]) }}" class="btn-custom btn-info-custom">
                <i class="fas fa-chart-pie me-2"></i>Lihat Grafik
            </a>
            <a href="{{ route('export.answers', ['userId' => $userId]) }}" class="btn-custom btn-success-custom">
                <i class="fas fa-file-excel me-2"></i>Export Excel
            </a>
            <button class="btn-custom btn-warning-custom" onclick="printTable()">
                <i class="fas fa-print me-2"></i>Cetak
            </button>
        </div>

        <!-- Search and Filter Section -->
        <div class="search-filter-section animate__animated animate__fadeInUp">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" class="search-input form-control border-start-0" id="searchInput"
                            placeholder="Cari pertanyaan, jawaban, atau kompetensi...">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-6">
                            <select class="form-select border-2" id="competencyFilter">
                                <option value="">Semua Kompetensi</option>
                                @foreach ($answers->unique('nama') as $answer)
                                    <option value="{{ $answer->nama }}">{{ $answer->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6">
                            <select class="form-select border-2" id="scoreFilter">
                                <option value="">Semua Skor</option>
                                <option value="5">Skor 5</option>
                                <option value="4">Skor 4</option>
                                <option value="3">Skor 3</option>
                                <option value="2">Skor 2</option>
                                <option value="1">Skor 1</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Container dengan Footer Summary -->
        <div class="table-container animate__animated animate__fadeInUp">
            <div class="table-responsive">
                @if ($answers->count() > 0)
                    <table class="table custom-table" id="answersTable">
                        <thead>
                            <tr>
                                <th><i class="fas fa-hashtag me-2"></i>No</th>
                                <th><i class="fas fa-question me-2"></i>Pertanyaan</th>
                                <th><i class="fas fa-comment me-2"></i>Jawaban</th>
                                <th><i class="fas fa-cogs me-2"></i>Kompetensi</th>
                                <th><i class="fas fa-bullseye me-2"></i>Indikator</th>
                                <th><i class="fas fa-star me-2"></i>Skor</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($answers as $index => $answer)
                                <tr class="answer-row animate__animated animate__fadeInUp"
                                    style="animation-delay: {{ $loop->iteration * 0.1 }}s"
                                    data-question="{{ strtolower($answer->question_text) }}"
                                    data-answer="{{ strtolower($answer->answer_text) }}"
                                    data-competency="{{ strtolower($answer->nama) }}" data-score="{{ $answer->score }}">
                                    <td>
                                        <span class="badge bg-primary rounded-pill fs-6">
                                            {{ $loop->iteration }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="question-text">
                                            {{ $answer->question_text }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="answer-text">
                                            {{ $answer->answer_text }}
                                        </div>
                                    </td>
                                    <td>
                                        <span class="competency-badge">
                                            {{ $answer->nama }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="indicator-badge">
                                            {{ $answer->indikator_nama }}
                                        </span>
                                    </td>
                                    <td>
                                        <span
                                            class="score-badge 
                                        @if ($answer->score >= 4.5) score-excellent
                                        @elseif($answer->score >= 3.5) score-good
                                        @elseif($answer->score >= 2.5) score-average
                                        @else score-poor @endif">
                                            {{ $answer->score }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <!-- Table Footer dengan Total -->
                        <tfoot>
                            <tr class="table-footer bg-light">
                                <td colspan="5" class="text-end fw-bold">
                                    <i class="fas fa-calculator me-2"></i>TOTAL SKOR:
                                </td>
                                <td>
                                    <span class="badge bg-primary fs-6 px-3 py-2" id="totalScoreDisplay">
                                        {{ number_format($quizAttempt->score, 1) }}
                                    </span>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                @else
                    <div class="no-data animate__animated animate__fadeIn">
                        <i class="fas fa-inbox"></i>
                        <h4 class="mt-3">Belum Ada Jawaban</h4>
                        <p class="text-muted">Peserta belum mengerjakan soal atau data belum tersedia.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Floating Action Buttons -->
    <div class="floating-actions animate__animated animate__fadeInRight">
        <button class="floating-btn" style="background: linear-gradient(135deg, var(--info-color), #138496);"
            onclick="scrollToTop()" title="Scroll to Top">
            <i class="fas fa-arrow-up"></i>
        </button>
        <button class="floating-btn" style="background: linear-gradient(135deg, var(--success-color), #218838);"
            onclick="downloadPDF()" title="Download PDF">
            <i class="fas fa-file-pdf"></i>
        </button>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.3/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Loading overlay
            function showLoading() {
                document.getElementById('loadingOverlay').style.display = 'flex';
            }

            function hideLoading() {
                document.getElementById('loadingOverlay').style.display = 'none';
            }

            // Search functionality
            const searchInput = document.getElementById('searchInput');
            const competencyFilter = document.getElementById('competencyFilter');
            const scoreFilter = document.getElementById('scoreFilter');
            const rows = document.querySelectorAll('.answer-row');

            function filterTable() {
                const searchTerm = searchInput.value.toLowerCase();
                const selectedCompetency = competencyFilter.value.toLowerCase();
                const selectedScore = scoreFilter.value;

                // Check if any filter is active
                const isFilterActive = searchTerm !== '' || selectedCompetency !== '' || selectedScore !== '';

                let visibleCount = 0;
                let totalFilteredScore = 0;

                rows.forEach(row => {
                    const question = row.dataset.question;
                    const answer = row.dataset.answer;
                    const competency = row.dataset.competency;
                    const score = parseFloat(row.dataset.score);

                    const matchesSearch = question.includes(searchTerm) ||
                        answer.includes(searchTerm) ||
                        competency.includes(searchTerm);
                    const matchesCompetency = !selectedCompetency || competency.includes(
                        selectedCompetency);
                    const matchesScore = !selectedScore || score == selectedScore;

                    if (matchesSearch && matchesCompetency && matchesScore) {
                        row.style.display = '';
                        row.classList.add('animate__animated', 'animate__fadeIn');
                        visibleCount++;
                        totalFilteredScore += score;

                        // Update row numbering
                        const numberBadge = row.querySelector('.badge');
                        if (numberBadge) {
                            numberBadge.textContent = visibleCount;
                        }
                    } else {
                        row.style.display = 'none';
                        row.classList.remove('animate__animated', 'animate__fadeIn');
                    }
                });

                // Update total score display
                updateScoreDisplay(visibleCount, totalFilteredScore, isFilterActive);

                updateNoDataMessage();
                updateFilterStats(visibleCount, isFilterActive, totalFilteredScore);
            }

            function updateScoreDisplay(visibleCount, totalScore, isFilterActive) {
                const totalScoreDisplay = document.getElementById('totalScoreDisplay');

                if (totalScoreDisplay) {
                    if (isFilterActive) {
                        totalScoreDisplay.textContent = totalScore.toFixed(1);
                    } else {
                        // Reset to original values
                        totalScoreDisplay.textContent = '{{ number_format($quizAttempt->score, 1) }}';
                    }
                }
            }

            function updateNoDataMessage() {
                const visibleRows = document.querySelectorAll('.answer-row[style=""], .answer-row:not([style])');
                const tbody = document.querySelector('#answersTable tbody');
                const existingNoData = document.querySelector('.no-data-filter');

                if (existingNoData) {
                    existingNoData.remove();
                }

                if (visibleRows.length === 0 && rows.length > 0) {
                    const noDataRow = document.createElement('tr');
                    noDataRow.className = 'no-data-filter';
                    noDataRow.innerHTML = `
                <td colspan="6" class="text-center py-5">
                    <i class="fas fa-search text-muted" style="font-size: 3rem; opacity: 0.5;"></i>
                    <h5 class="mt-3 text-muted">Tidak ada data yang sesuai dengan filter</h5>
                    <p class="text-muted">Coba ubah kata kunci pencarian atau filter yang dipilih</p>
                    <button class="btn btn-outline-primary btn-sm mt-2" onclick="clearAllFilters()">
                        <i class="fas fa-times me-1"></i>Hapus Semua Filter
                    </button>
                </td>
            `;
                    tbody.appendChild(noDataRow);
                }
            }

            function updateFilterStats(visibleCount, isFilterActive, totalScore = 0) {
                let filterStats = document.querySelector('.filter-stats');

                if (!filterStats && isFilterActive) {
                    filterStats = document.createElement('div');
                    filterStats.className =
                        'filter-stats alert alert-info d-flex justify-content-between align-items-center mb-3';
                    filterStats.innerHTML = `
                <div>
                    <i class="fas fa-filter me-2"></i>
                    <span class="filter-count"></span>
                    <span class="filter-score ms-3"></span>
                </div>
                <button class="btn btn-outline-primary btn-sm" onclick="clearAllFilters()">
                    <i class="fas fa-times me-1"></i>Hapus Filter
                </button>
            `;

                    const searchSection = document.querySelector('.search-filter-section');
                    searchSection.insertAdjacentElement('afterend', filterStats);
                }

                if (filterStats) {
                    const filterCountSpan = filterStats.querySelector('.filter-count');
                    const filterScoreSpan = filterStats.querySelector('.filter-score');

                    if (isFilterActive) {
                        filterStats.style.display = 'flex';
                        filterCountSpan.textContent = `Menampilkan ${visibleCount} dari ${rows.length} data`;
                        if (filterScoreSpan) {
                            filterScoreSpan.innerHTML =
                                `| <i class="fas fa-trophy me-1"></i>Total Skor: <strong>${totalScore.toFixed(1)}</strong>`;
                        }
                    } else {
                        filterStats.style.display = 'none';
                    }
                }
            }

            // Function to clear all filters
            window.clearAllFilters = function() {
                searchInput.value = '';
                competencyFilter.value = '';
                scoreFilter.value = '';
                filterTable();

                // Reset numbering to original
                rows.forEach((row, index) => {
                    if (row.style.display !== 'none') {
                        const numberBadge = row.querySelector('.badge');
                        if (numberBadge) {
                            numberBadge.textContent = index + 1;
                        }
                    }
                });

                const toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true
                });

                toast.fire({
                    icon: 'success',
                    title: 'Filter berhasil dihapus!'
                });
            };

            searchInput.addEventListener('input', filterTable);
            competencyFilter.addEventListener('change', filterTable);
            scoreFilter.addEventListener('change', filterTable);

            // Print table - updated to include total score
            window.printTable = function() {
                showLoading();

                setTimeout(() => {
                    const visibleRows = document.querySelectorAll(
                        '.answer-row:not([style*="display: none"])');
                    let tableContent = '';
                    let totalScore = 0;

                    visibleRows.forEach((row, index) => {
                        const cells = row.querySelectorAll('td');
                        const score = parseFloat(row.dataset.score);
                        totalScore += score;

                        tableContent += `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${cells[1].textContent.trim()}</td>
                        <td>${cells[2].textContent.trim()}</td>
                        <td>${cells[3].textContent.trim()}</td>
                        <td>${cells[4].textContent.trim()}</td>
                        <td>${cells[5].textContent.trim()}</td>
                    </tr>
                `;
                    });

                    const avgScore = visibleRows.length > 0 ? (totalScore / visibleRows.length) : 0;

                    const printWindow = window.open('', '_blank');

                    printWindow.document.write(`
                <html>
                    <head>
                        <title>Detail Jawaban - {{ $userName }}</title>
                        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
                        <style>
                            body { font-family: Arial, sans-serif; }
                            .table th { background-color: #005689 !important; color: white !important; }
                            .summary-box { background-color: #f8f9fa; padding: 15px; border-radius: 8px; margin: 20px 0; }
                            @media print { 
                                .no-print { display: none !important; }
                                body { -webkit-print-color-adjust: exact; }
                            }
                        </style>
                    </head>
                    <body>
                        <div class="container mt-4">
                            <h2 class="text-center mb-4">Detail Jawaban {{ $userName }}</h2>
                            <p class="text-center text-muted mb-4">Dicetak pada: {{ date('d F Y H:i') }}</p>
                            
                            <div class="summary-box">
                                <div class="row text-center">
                                    <div class="col-3">
                                        <strong>Jumlah Soal</strong><br>
                                        <span class="text-primary">${visibleRows.length}</span>
                                    </div>
                                    <div class="col-3">
                                        <strong>Total Skor</strong><br>
                                        <span class="text-success">${totalScore.toFixed(1)}</span>
                                    </div>
                                    <div class="col-3">
                                        <strong>Persentase</strong><br>
                                        <span class="text-warning">${((avgScore / 5) * 100).toFixed(1)}%</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Pertanyaan</th>
                                            <th>Jawaban</th>
                                            <th>Kompetensi</th>
                                            <th>Indikator</th>
                                            <th>Skor</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        ${tableContent}
                                    </tbody>
                                    <tfoot class="table-light">
                                        <tr>
                                            <td colspan="5" class="text-end"><strong>TOTAL SKOR:</strong></td>
                                            <td><strong>${totalScore.toFixed(1)}</strong></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </body>
                </html>
            `);

                    printWindow.document.close();
                    printWindow.focus();

                    setTimeout(() => {
                        printWindow.print();
                        printWindow.close();
                        hideLoading();
                    }, 1000);
                }, 500);
            };

            // Download PDF (placeholder - needs server-side implementation)
            window.downloadPDF = function() {
                const visibleCount = document.querySelectorAll('.answer-row:not([style*="display: none"])')
                    .length;

                Swal.fire({
                    title: 'Download PDF',
                    html: `
                <p>Fitur download PDF akan segera tersedia</p>
                <p class="text-muted">Data yang akan diunduh: ${visibleCount} jawaban</p>
            `,
                    icon: 'info',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#005689'
                });
            };

            // Animate progress bar on load
            setTimeout(() => {
                const progressBar = document.querySelector('.progress-bar-custom');
                if (progressBar) {
                    progressBar.style.width = progressBar.style.width;
                }
            }, 500);

            // Add hover effects to table rows
            rows.forEach(row => {
                row.addEventListener('mouseenter', function() {
                    if (this.style.display !== 'none') {
                        this.style.transform = 'scale(1.02)';
                        this.style.zIndex = '10';
                    }
                });

                row.addEventListener('mouseleave', function() {
                    this.style.transform = 'scale(1)';
                    this.style.zIndex = '1';
                });
            });

            // Success message for page load
            setTimeout(() => {
                const toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });

                toast.fire({
                    icon: 'success',
                    title: 'Data berhasil dimuat!'
                });
            }, 1000);
        });

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl + F untuk focus ke search
            if (e.ctrlKey && e.key === 'f') {
                e.preventDefault();
                document.getElementById('searchInput').focus();
            }

            // Escape untuk clear search
            if (e.key === 'Escape') {
                clearAllFilters();
            }

            // Ctrl + P untuk print
            if (e.ctrlKey && e.key === 'p') {
                e.preventDefault();
                printTable();
            }
        });
    </script>

@endsection
