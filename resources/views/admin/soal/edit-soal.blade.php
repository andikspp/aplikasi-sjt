@extends('layout.admin.admin-layout')

@section('title', 'Edit Soal')

@section('content')
    <style>
        .btn-custom {
            background-color: #005689;
            color: white;
        }

        .btn-custom:hover {
            background-color: #012a41;
            color: white;
        }
    </style>

    <div class="container mt-5">
        <h2 class="text-center mb-4">Edit Soal</h2>
        <form action="{{ route('admin.soal.update', $question->id) }}" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="question_set_id" value="{{ $question->question_set_id }}">
            <div class="mb-3">
                <label for="question_set_id" class="form-label">Paket Soal</label>
                <select class="form-select" id="question_set_id" name="question_set_id" required>
                    @php
                        $questionSets = App\Models\QuestionSet::all();
                    @endphp
                    @foreach ($questionSets as $set)
                        <option value="{{ $set->id }}" {{ $question->question_set_id == $set->id ? 'selected' : '' }}>
                            {{ $set->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="question_text" class="form-label">Pertanyaan</label>
                <textarea class="form-control" id="question_text" name="question_text" rows="3" required>{{ old('question_text', $question->question_text) }}</textarea>
            </div>
            @foreach ($question->answers as $index => $answer)
                <input type="hidden" name="answer_ids[{{ $index }}]" value="{{ $answer->id }}">
                <div class="row mb-3">
                    <div class="col-md-8">
                        <label for="answer_text_{{ $index }}" class="form-label">Jawaban {{ $index + 1 }}</label>
                        <input type="text" class="form-control" id="answer_text_{{ $index }}"
                            name="answers[{{ $index }}][answer_text]"
                            value="{{ old('answers.' . $index . '.answer_text', $answer->answer_text) }}" required>
                    </div>
                    <div class="col-md-4">
                        <label for="score_{{ $index }}" class="form-label">Bobot {{ $index + 1 }}</label>
                        <input type="number" class="form-control" id="score_{{ $index }}"
                            name="answers[{{ $index }}][score]"
                            value="{{ old('answers.' . $index . '.score', $answer->score) }}" required>
                    </div>
                </div>
            @endforeach
            <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-danger me-2" onclick="goBack()">Kembali</button>
                <button type="submit" class="btn btn-success me-2">Simpan</button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.3/dist/sweetalert2.all.min.js"></script>
    <script>
        function goBack() {
            window.history.back();
        }

        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Maaf',
                text: '{{ session('error') }}',
            });
        @endif

        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
            });
        @endif
    </script>
@endsection
