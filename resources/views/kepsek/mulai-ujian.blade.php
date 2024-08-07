<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Situational Judgement Test</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.3/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .navbar {
            background-color: #005689;
        }

        .navbar-custom .navbar-nav .nav-link {
            color: white;
        }

        .navbar-custom .navbar-brand {
            color: white;
        }

        .navbar-text {
            margin-left: auto;
            color: white;
            font-size: 1.5rem;
            text-decoration: none;
        }

        .navbar-text:hover {
            text-decoration: underline;
        }

        .navbar-nav .nav-item:hover {
            transform: scale(1.05);
        }

        footer {
            background-color: #005689;
            color: white;
        }

        .question-card {
            margin-bottom: 20px;
        }

        .question-card h5 {
            margin-bottom: 15px;
        }

        .question-card .form-check {
            color: #000;
            /* Warna hitam untuk opsi jawaban */
        }

        .question-card .form-check-label {
            color: #000;
            /* Warna hitam untuk label opsi jawaban */
        }

        .question-number {
            cursor: pointer;
        }

        .question-number.active {
            background-color: #007bff;
            color: #fff;
        }

        .question-number.answered {
            background-color: #28a745;
            color: #fff;
        }

        .timer {
            font-size: 1.5rem;
            font-weight: bold;
        }

        .remaining-time {
            font-size: 1.2rem;
            color: #dc3545;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light navbar-custom">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <img src="{{ asset('assets/logo kemendikbudristek.png') }}" alt="Logo" style="max-width: 100px;">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#">FAQ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" id="logout-btn">Logout</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </li>
                </ul>
                <a class="navbar-text" href="https://gurupauddikmas.kemdikbud.go.id/" target="_blank">
                    Direktorat Guru PAUD dan Dikmas
                </a>
            </div>
        </div>
    </nav>
    
    <div class="container mt-5">
        @if (session('warning'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                {{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="row">
            <div class="col-md-8">
                <h2 class="text-center mb-4">Situational Judgement Test</h2>
                <form id="quizForm" action="{{ route('submitExam') }}" method="POST">
                    @csrf
                    @foreach ($questions as $index => $question)
                        <div class="card question-card question" id="question-{{ $index + 1 }}"
                            style="display: {{ $index === 0 ? 'block' : 'none' }};">
                            <div class="card-body">
                                <h5 class="card-title">Soal {{ $index + 1 }}: {{ $question['question_text'] }}</h5>
                                @if (isset($question['answers']))
                                    @foreach ($question['answers'] as $answer)
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio"
                                                name="answers[{{ $index + 1 }}]"
                                                id="q{{ $index + 1 }}{{ $answer['id'] }}"
                                                value="{{ $answer['id'] }}">
                                            <label class="form-check-label"
                                                for="q{{ $index + 1 }}{{ $answer['id'] }}">
                                                {{ $answer['answer_text'] }}
                                            </label>
                                        </div>
                                    @endforeach
                                @else
                                    <p>Tidak ada jawaban tersedia untuk soal ini.</p>
                                @endif

                                <!-- Navigation buttons -->
                                <div class="d-flex justify-content-between mt-4">
                                    @if ($index > 0)
                                        <button type="button" class="btn btn-secondary prev-question"
                                            id="prevBtn">Sebelumnya</button>
                                    @else
                                        <span></span>
                                    @endif

                                    @if ($index < count($questions) - 1)
                                        <button type="button" class="btn btn-primary next-question"
                                            id="nextBtn">Berikutnya</button>
                                    @else
                                        <button type="button" class="btn btn-success" id="finishBtn">Selesai</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </form>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="remaining-time ms-2 mt-2" id="remaining-time">Waktu: <span
                            id="timer">00:00:00</span></div>
                    <div class="card-body">
                        <h5 class="card-title">Nomor Soal</h5>
                        <div class="d-flex flex-wrap">
                            @foreach ($questions as $index => $question)
                                <div class="question-number border text-center p-2 m-1"
                                    id="question-number-{{ $index + 1 }}"
                                    onclick="showQuestion({{ $index + 1 }})">{{ $index + 1 }}</div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="text-center text-lg-start mt-5">
        <div class="text-center p-3">
            &copy; 2024 Guru PAUD Dikmas
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        let currentQuestion = 1;
        let timer;
        let totalTime = {{ $questionSet->time_limit }} * 60;
        let examEnded = false;

        document.addEventListener('DOMContentLoaded', function() {
            startExam();
            setupEventListeners();
            showQuestion(currentQuestion);
            updateButtonState();
        });

        function startExam() {
            document.getElementById('timer').textContent = formatTime(totalTime);
            timer = setInterval(() => {
                totalTime--;
                document.getElementById('timer').textContent = formatTime(totalTime);
                if (totalTime <= 0) {
                    clearInterval(timer);
                    examEnded = true;
                    Swal.fire({
                        title: 'Waktu ujian telah berakhir!',
                        text: 'Form akan dikirimkan secara otomatis.',
                        icon: 'info',
                        showConfirmButton: false,
                        timer: 2000 // Set timer to close SweetAlert after 2 seconds
                    }).then(() => {
                        checkCompletion();
                    });
                }
            }, 1000);
        }

        function formatTime(seconds) {
            const minutes = Math.floor(seconds / 60);
            const secs = seconds % 60;
            return `${String(minutes).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
        }

        function showQuestion(questionNumber) {
            document.querySelectorAll('.question-card').forEach(card => card.style.display = 'none');
            document.getElementById(`question-${questionNumber}`).style.display = 'block';
            currentQuestion = questionNumber;
            updateButtonState();
            markAnswered();
        }

        function changeQuestion(step) {
            let nextQuestion = currentQuestion + step;
            if (nextQuestion < 1 || nextQuestion > {{ count($questions) }}) return;
            showQuestion(nextQuestion);
        }

        function updateButtonState() {
            document.getElementById('prevBtn').disabled = currentQuestion === 1;
            document.getElementById('nextBtn').style.display = currentQuestion === {{ count($questions) }} ? 'none' :
                'block';
            document.getElementById('finishBtn').style.display = currentQuestion === {{ count($questions) }} ?
                'block' : 'none';
            markActive();
        }

        function markActive() {
            document.querySelectorAll('.question-number').forEach(el => el.classList.remove('active'));
            document.getElementById(`question-number-${currentQuestion}`).classList.add('active');
        }

        function checkCompletion() {
            if (examEnded) {
                document.getElementById('quizForm').submit();
                return;
            }

            let unansweredQuestions = [];
            for (let i = 1; i <= {{ count($questions) }}; i++) {
                if (!document.querySelector(`input[name="answers[${i}]"]:checked`)) {
                    unansweredQuestions.push(i);
                }
            }
            if (unansweredQuestions.length > 0) {
                Swal.fire({
                    title: 'Ada soal yang belum terisi',
                    text: 'Semua soal harus terisi sebelum mengirimkan ujian.',
                    icon: 'warning',
                    confirmButtonText: 'Ok'
                });
            } else {
                document.getElementById('quizForm').submit();
            }
        }

        function markAnswered() {
            document.querySelectorAll('.question-number').forEach(el => el.classList.remove('answered'));
            for (let i = 1; i <= {{ count($questions) }}; i++) {
                if (document.querySelector(`input[name="answers[${i}]"]:checked`)) {
                    document.getElementById(`question-number-${i}`).classList.add('answered');
                }
            }
        }

        function setupEventListeners() {
            document.querySelectorAll('.next-question').forEach(btn => {
                btn.addEventListener('click', function() {
                    changeQuestion(1);
                });
            });

            document.querySelectorAll('.prev-question').forEach(btn => {
                btn.addEventListener('click', function() {
                    changeQuestion(-1);
                });
            });

            document.getElementById('finishBtn').addEventListener('click', function(event) {
                event.preventDefault();
                Swal.fire({
                    title: 'Anda yakin ingin menyelesaikan ujian?',
                    text: "Pastikan Anda sudah menjawab semua pertanyaan.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, selesai!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        checkCompletion();
                    }
                });
            });

            document.querySelectorAll('.form-check-input').forEach(input => {
                input.addEventListener('change', markAnswered);
            });
        }

        document.querySelectorAll('input[type="radio"]').forEach(input => {
            input.addEventListener('change', markAnswered);
        });

        document.getElementById('logout-btn').addEventListener('click', function(event) {
            event.preventDefault();

            Swal.fire({
                title: 'Anda yakin ingin logout?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, logout!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logout-form').submit();
                }
            });
        });
    </script>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.3/dist/sweetalert2.all.min.js"></script>
</body>

</html>
