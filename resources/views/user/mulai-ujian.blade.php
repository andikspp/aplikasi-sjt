<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('assets/logo kemendikbudristek.png') }}" type="image/png" />
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
            border-radius: 18px;
            border: none;
            margin-bottom: 30px;
            background: #fff;
            transition: box-shadow 0.2s;
        }

        .question-card .card-body {
            padding: 2rem 1.5rem;
        }

        .soal-badge {
            background: linear-gradient(90deg, #005689 70%, #0077b6 100%);
            color: #fff;
            font-weight: bold;
            font-size: 1.3rem;
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px rgba(0, 86, 137, 0.10);
        }

        .question-text {
            text-align: justify;
        }

        .question-number {
            cursor: pointer;
            width: 44px;
            height: 44px;
            line-height: 44px;
            font-size: 1.1rem;
            margin: 6px;
            border-radius: 50%;
            border: 2px solid #0077b6;
            background: #fff;
            color: #0077b6;
            font-weight: 600;
            transition: background 0.2s, color 0.2s, border 0.2s;
            box-shadow: 0 2px 6px rgba(0, 86, 137, 0.07);
            user-select: none;
        }

        .question-number.active,
        .question-number:active {
            background: linear-gradient(90deg, #005689 70%, #0077b6 100%);
            color: #fff;
            border: 2px solid #005689;
        }

        .question-number.answered {
            background: #28a745;
            color: #fff;
            border: 2px solid #28a745;
        }

        .question-number:hover {
            background: #0077b6;
            color: #fff;
            border: 2px solid #0077b6;
        }

        .btn-primary,
        .btn-outline-primary,
        .btn-success {
            border-radius: 30px;
            font-weight: 600;
            font-size: 1.05rem;
            padding: 0.5rem 1.5rem;
            transition: background 0.2s, color 0.2s;
        }

        .btn-primary {
            background: linear-gradient(90deg, #005689 70%, #0077b6 100%);
            border: none;
        }

        .btn-primary:hover,
        .btn-outline-primary:hover {
            background: linear-gradient(90deg, #0077b6 70%, #005689 100%);
            color: #fff;
        }

        .btn-outline-primary {
            border: 2px solid #0077b6;
            color: #0077b6;
            background: #fff;
        }

        .btn-success {
            background: linear-gradient(90deg, #28a745 70%, #218838 100%);
            border: none;
        }

        .btn-success:hover {
            background: linear-gradient(90deg, #218838 70%, #28a745 100%);
        }

        .form-check-input {
            width: 20px;
            height: 20px;
            border: 2px solid #005689;
        }

        .form-check-input:checked {
            background-color: #005689;
            border-color: #005689;
        }

        .form-check-label {
            margin-left: 10px;
            font-size: 1.05rem;
        }

        .remaining-time {
            font-size: 1.2rem;
            color: #dc3545;
            font-weight: 600;
        }

        @media (max-width: 991.98px) {
            .question-card .card-body {
                padding: 1.2rem 0.7rem;
            }

            .soal-badge {
                width: 38px;
                height: 38px;
                font-size: 1rem;
            }

            .question-number {
                width: 32px;
                height: 32px;
                font-size: 0.95rem;
                line-height: 32px;
                margin: 3px 2px;
            }

            .remaining-time {
                font-size: 1rem;
            }

            .card-title {
                font-size: 1.1rem;
            }
        }

        @media (max-width: 576px) {
            .container {
                padding-left: 4px !important;
                padding-right: 4px !important;
            }

            .question-card .card-body {
                padding: 0.7rem 0.3rem;
            }

            .soal-badge {
                width: 30px;
                height: 30px;
                font-size: 0.85rem;
            }

            .question-number {
                width: 26px;
                height: 26px;
                font-size: 0.8rem;
                line-height: 26px;
                margin: 2px 1px;
            }

            .remaining-time {
                font-size: 0.95rem;
            }

            .card-title {
                font-size: 1rem;
            }
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light navbar-custom">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <img src="{{ asset('assets/logo kemendikbudristek.png') }}" alt="Logo" style="max-width: 100px;">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <a class="navbar-text" href="#">
                    Direktorat Guru PAUD dan PNF
                </a>
            </div>
        </div>
    </nav>

    <div class="container py-3">
        @if (session('warning'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                {{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="row g-3">
            <div class="col-12 col-lg-8">
                <form id="quizForm" action="{{ route('submitExam') }}" method="POST">
                    @csrf
                    @foreach ($questions as $index => $question)
                        <div class="card question-card question shadow-lg mb-3" id="question-{{ $index + 1 }}"
                            style="display: {{ $index === 0 ? 'block' : 'none' }};">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3 flex-wrap">
                                    <div class="soal-badge me-3 mb-2 mb-md-0">{{ $index + 1 }}</div>
                                    <h4 class="card-title mb-0 fw-bold text-primary">Soal {{ $index + 1 }}</h4>
                                </div>
                                <div class="question-text mb-4">
                                    <span class="fw-semibold"
                                        style="font-size:1.15rem;">{{ $question['question_text'] }}</span>
                                </div>
                                @if (isset($question['answers']))
                                    @foreach ($question['answers'] as $answer)
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="radio"
                                                name="answers[{{ $index + 1 }}]"
                                                id="q{{ $index + 1 }}{{ $answer['id'] }}"
                                                value="{{ $answer['id'] }}"
                                                {{ isset($savedAnswers[$question['id']]) && $savedAnswers[$question['id']] == $answer['id'] ? 'checked' : '' }}>
                                            <label class="form-check-label fw-semibold"
                                                for="q{{ $index + 1 }}{{ $answer['id'] }}">
                                                {{ $answer['answer_text'] }}
                                            </label>
                                        </div>
                                    @endforeach
                                @else
                                    <p>Tidak ada jawaban tersedia untuk soal ini.</p>
                                @endif

                                <!-- Navigation buttons -->
                                <div class="d-flex justify-content-between mt-4 flex-wrap gap-2">
                                    @if ($index > 0)
                                        <button type="button" class="btn btn-outline-primary prev-question"
                                            id="prevBtn">
                                            <i class="fa fa-arrow-left me-1"></i> Sebelumnya
                                        </button>
                                    @else
                                        <span></span>
                                    @endif

                                    @if ($index < count($questions) - 1)
                                        <button type="button" class="btn btn-primary next-question" id="nextBtn">
                                            Berikutnya <i class="fa fa-arrow-right ms-1"></i>
                                        </button>
                                    @else
                                        <button type="button" class="btn btn-success" id="finishBtn">
                                            <i class="fa fa-check me-1"></i> Selesai
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </form>
            </div>
            <div class="col-12 col-lg-4 mt-3 mt-lg-0">
                <div class="card shadow-sm">
                    <div class="remaining-time ms-2 mt-2" id="remaining-time">
                        <i class="fa fa-clock me-1"></i>Waktu: <span id="timer">00:00:00</span>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title mb-3">Nomor Soal</h5>
                        <div class="d-flex flex-wrap justify-content-start gap-1">
                            @foreach ($questions as $index => $question)
                                <div class="question-number text-center" id="question-number-{{ $index + 1 }}"
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
            &copy; 2025 Direktorat Guru PAUD dan PNF, Kementerian Pendidikan, Kebudayaan, Riset, dan Teknologi Republik
            Indonesia.
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        let currentQuestion = 1;
        let timer;
        let totalTime = {{ $questionSet->time_limit }} * 60;
        let examStartKey = 'examStartTime_{{ auth()->user()->id }}_{{ $questionSet->id }}';
        let examEnded = false;
        let questionOrder = [];

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function getExamStartTime() {
            let stored = localStorage.getItem(examStartKey);
            if (stored) {
                return parseInt(stored, 10);
            } else {
                let now = Math.floor(Date.now() / 1000);
                localStorage.setItem(examStartKey, now);
                return now;
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            startExam();
            setupEventListeners();

            // Ambil nomor soal dari localStorage jika ada
            const savedQuestionNumber = localStorage.getItem('currentQuestion');
            if (savedQuestionNumber) {
                currentQuestion = parseInt(savedQuestionNumber, 10);
            }

            showQuestion(currentQuestion);
            updateButtonState();

            fetch('/get-question-order')
                .then(response => response.json())
                .then(data => {
                    questionOrder = data;
                    showQuestion(currentQuestion);
                    updateButtonState();
                });

            // Ambil data jawaban dari server
            fetch('/get-user-answers')
                .then(response => response.json())
                .then(data => {
                    // Isi jawaban ke dalam radio button berdasarkan ID pertanyaan dan jawaban
                    data.forEach(answer => {
                        const answerElement = document.querySelector(
                            `input[name="answers[${answer.question_id}]"][value="${answer.answer_id}"]`
                        );
                        if (answerElement) {
                            answerElement.checked = true;
                            document.getElementById(`question-number-${answer.question_id}`).classList
                                .add('answered');
                        }
                    });
                    markActive();
                });
        });

        function startExam() {
            let examStartTime = getExamStartTime(); // detik epoch
            let now = Math.floor(Date.now() / 1000);
            let elapsed = now - examStartTime;
            let remainingTime = totalTime - elapsed;

            if (remainingTime <= 0) {
                remainingTime = 0;
            }

            document.getElementById('timer').textContent = formatTime(remainingTime);

            timer = setInterval(() => {
                remainingTime--;
                document.getElementById('timer').textContent = formatTime(remainingTime);
                if (remainingTime <= 0) {
                    clearInterval(timer);
                    examEnded = true;
                    localStorage.removeItem(examStartKey); // hapus waktu mulai dari localStorage
                    Swal.fire({
                        title: 'Waktu sesi telah berakhir!',
                        text: 'Form akan dikirimkan secara otomatis.',
                        icon: 'info',
                        showConfirmButton: false,
                        timer: 2000
                    }).then(() => {
                        checkCompletion();
                    });
                }
            }, 1000);
        }

        function saveAnswer(answerId, questionId) {
            console.log('Saving answer with questionId:', questionId);
            $.ajax({
                url: '/save-answer',
                method: 'POST',
                data: {
                    user_id: {{ auth()->user()->id }},
                    answer_id: answerId,
                    question_id: questionId
                },
                success: function(response) {
                    console.log('Answer saved successfully.');
                },
                error: function(xhr) {
                    console.log('Error saving answer:', xhr.responseText);
                }
            });
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
            localStorage.setItem('currentQuestion', currentQuestion); // Simpan nomor soal ke localStorage
            updateButtonState();
            markAnswered();
        }

        function changeQuestion(step) {
            saveCurrentAnswer();
            let nextQuestion = currentQuestion + step;
            if (nextQuestion < 1 || nextQuestion > {{ count($questions) }}) return;
            showQuestion(nextQuestion);
        }

        function saveCurrentAnswer() {
            const selectedInput = document.querySelector(`input[name="answers[${currentQuestion}]"]:checked`);
            if (selectedInput) {
                const answerId = selectedInput.value;
                // Ambil questionId dari server menggunakan answerId
                fetch(`/get-question-id/${answerId}`)
                    .then(response => response.json())
                    .then(data => {
                        const questionId = data.question_id;
                        // Simpan jawaban ke server
                        saveAnswer(answerId, questionId);
                    })
                    .catch(error => console.error('Error fetching question ID:', error));
            }
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
            document.querySelectorAll('.question-number').forEach((element) => {
                element.classList.remove('active');
            });
            document.getElementById(`question-number-${currentQuestion}`).classList.add('active');
        }

        function checkCompletion() {
            if (examEnded) {
                document.getElementById('quizForm').submit();
                localStorage.removeItem('currentQuestion'); // Hapus nomor soal dari localStorage
                localStorage.removeItem(examStartKey);
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
                    text: 'Semua soal harus terisi sebelum mengirimkan pekerjaan.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            } else {
                document.getElementById('quizForm').submit();
                localStorage.removeItem('currentQuestion'); // Hapus nomor soal dari localStorage
                localStorage.removeItem(examStartKey);
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
                    title: 'Anda yakin ingin mengirim sesi?',
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
            input.addEventListener('change', function() {
                // Simpan jawaban saat radio button dipilih
                saveCurrentAnswer();
                markAnswered();
            });
        });
    </script>



    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.3/dist/sweetalert2.all.min.js"></script>
</body>

</html>
