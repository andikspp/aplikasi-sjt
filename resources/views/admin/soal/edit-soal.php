<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Soal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .navbar {
            background-color: #005689;
        }
        .navbar-custom .navbar-nav .nav-link {
            color: white;
        }
        .navbar-custom .navbar-brand {
            color: white;
        }
        .btn-custom {
            background-color: #005689;
            color: white;
        }
        .btn-custom:hover {
            background-color: #012a41;
            color: white;
        }
    </style>
</head>

<body>
    <!-- Navbar (same as in the original file) -->
    
    <div class="container mt-5">
        <h2 class="text-center mb-4">Edit Soal</h2>
        <form action="{{ route('admin.soal.update', $question->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="question_text" class="form-label">Pertanyaan</label>
                <textarea class="form-control" id="question_text" name="question_text" rows="3" required>{{ $question->question_text }}</textarea>
            </div>
            @foreach($question->answers as $index => $answer)
                <div class="row mb-3">
                    <div class="col-md-8">
                        <label for="answer_text_{{ $index }}" class="form-label">Jawaban {{ $index + 1 }}</label>
                        <input type="text" class="form-control" id="answer_text_{{ $index }}" name="answers[{{ $index }}][answer_text]" value="{{ $answer->answer_text }}" required>
                    </div>
                    <div class="col-md-4">
                        <label for="score_{{ $index }}" class="form-label">Bobot {{ $index + 1 }}</label>
                        <input type="number" class="form-control" id="score_{{ $index }}" name="answers[{{ $index }}][score]" value="{{ $answer->score }}" required>
                    </div>
                </div>
            @endforeach
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-custom">Simpan Perubahan</button>
                <a href="{{ route('admin.soal.detail', $question->question_set_id) }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>

    <!-- Footer (same as in the original file) -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>