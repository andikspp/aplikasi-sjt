<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hapus Soal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.3/dist/sweetalert2.min.css" rel="stylesheet">
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
        <h2 class="text-center mb-4">Hapus Soal</h2>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Apakah Anda yakin ingin menghapus soal ini?</h5>
                <p class="card-text"><strong>Pertanyaan:</strong> {{ $question->question_text }}</p>
                <p class="card-text"><strong>Jawaban:</strong></p>
                <ul>
                    @foreach($question->answers as $answer)
                        <li>{{ $answer->answer_text }} (Bobot: {{ $answer->score }})</li>
                    @endforeach
                </ul>
                <form action="{{ route('admin.soal.destroy', $question->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus Soal</button>
                    <a href="{{ route('admin.soal.detail', $question->question_set_id) }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>

    <!-- Footer (same as in the original file) -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.3/dist/sweetalert2.all.min.js"></script>
    <script>
        document.querySelector('form').addEventListener('submit', function(event) {
            event.preventDefault();
            Swal.fire({
                title: 'Anda yakin ingin menghapus soal ini?',
                text: "Tindakan ini tidak dapat dibatalkan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });
    </script>
</body>

</html>