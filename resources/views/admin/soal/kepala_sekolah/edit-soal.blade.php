@extends('layout.admin.admin-layout')

@section('title', 'Edit Soal')

@section('content')
    <style>
        .form-container {
            background-color: #f8f9fa;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            margin-top: 2rem;
        }
    </style>

    <div class="container mt-5">
        <div class="form-container">
            <h2 class="text-center mb-4">Edit Soal Kepala Sekolah</h2>
            <form action="{{ route('admin.soal.update', $question->id) }}" method="POST">
                @csrf
                @method('PUT')

                <small class="text-muted mb-2 d-block">
                    <span style="color: red">*</span> Wajib diisi
                </small>

                <div class="mb-3">
                    <label for="question_set_id" class="form-label">Paket Soal</label>
                    <select class="form-select" id="question_set_id" name="question_set_id" disabled>
                        @php
                            $questionSets = App\Models\QuestionSet::where('role', 'Kepala Sekolah')->get();
                        @endphp
                        @foreach ($questionSets as $set)
                            <option value="{{ $set->id }}"
                                {{ $question->question_set_id == $set->id ? 'selected' : '' }}>
                                {{ $set->name }}
                            </option>
                        @endforeach
                    </select>
                    <input type="hidden" name="question_set_id" value="{{ $question->question_set_id }}">
                </div>

                <div class="mb-3">
                    <label for="kompetensi_id" class="form-label">Kompetensi <span style="color: red">*</span></label>
                    <select class="form-select" id="kompetensi_id" name="kompetensi_id" required>
                        <option value="">-- Pilih Kompetensi --</option>
                        @php
                            $kompetensi = App\Models\Kompetensi::where('role', 'Kepala Sekolah')->get();
                        @endphp
                        @foreach ($kompetensi as $kompeten)
                            <option value="{{ $kompeten->id }}"
                                {{ $question->kompetensi_id == $kompeten->id ? 'selected' : '' }}>
                                {{ $kompeten->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="indikator_id" class="form-label">Indikator <span style="color: red">*</span></label>
                    <select class="form-select" id="indikator_id" name="indikator_id" required>
                        <option value="">-- Pilih Indikator --</option>
                        @php
                            $indikator = App\Models\Indikator::whereHas('kompetensi', function ($query) {
                                $query->where('role', 'Kepala Sekolah');
                            })->get();
                        @endphp
                        @foreach ($indikator as $ind)
                            <option value="{{ $ind->id }}" {{ $question->indikator_id == $ind->id ? 'selected' : '' }}>
                                {{ $ind->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="question_text" class="form-label">Pertanyaan <span style="color: red">*</span></label>
                    <textarea class="form-control" id="question_text" name="question_text" rows="4" required>{{ old('question_text', $question->question_text) }}</textarea>
                </div>

                @foreach ($question->answers as $index => $answer)
                    <input type="hidden" name="answer_ids[{{ $index }}]" value="{{ $answer->id }}">
                    <div class="mb-3">
                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label for="answer_text_{{ $index }}" class="form-label">Jawaban
                                    {{ $index + 1 }} <span style="color: red">*</span></label>
                                <textarea class="form-control" id="answer_text_{{ $index }}" name="answers[{{ $index }}][answer_text]"
                                    rows="2" required>{{ old('answers.' . $index . '.answer_text', $answer->answer_text) }}</textarea>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="score_{{ $index }}" class="form-label">Pembobotan Jawaban
                                    {{ $index + 1 }} <span style="color: red">*</span></label>
                                <select class="form-select score-select" id="score_{{ $index }}"
                                    name="answers[{{ $index }}][score]" required>
                                    <option value="" disabled>-- Pilih Bobot --</option>
                                    <option value="4"
                                        {{ old('answers.' . $index . '.score', $answer->score) == 4 ? 'selected' : '' }}>4
                                    </option>
                                    <option value="3"
                                        {{ old('answers.' . $index . '.score', $answer->score) == 3 ? 'selected' : '' }}>3
                                    </option>
                                    <option value="2"
                                        {{ old('answers.' . $index . '.score', $answer->score) == 2 ? 'selected' : '' }}>2
                                    </option>
                                    <option value="1"
                                        {{ old('answers.' . $index . '.score', $answer->score) == 1 ? 'selected' : '' }}>1
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-danger me-2" onclick="goBack()">Kembali</button>
                    <button type="submit" class="btn btn-success me-2">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.3/dist/sweetalert2.all.min.js"></script>
    <script>
        function goBack() {
            var questionSetId = document.querySelector('input[name="question_set_id"]').value;
            window.location.href = "/admin/soal/ks/" + questionSetId;
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

        // Dynamic indikator by kompetensi
        document.getElementById('kompetensi_id').addEventListener('change', function() {
            var kompetensiId = this.value;
            var indikatorSelect = document.getElementById('indikator_id');
            indikatorSelect.innerHTML = '<option value="">-- Pilih Indikator --</option>';
            if (kompetensiId) {
                fetch('{{ url('/admin/indikator/by-kompetensi') }}/' + kompetensiId)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(function(ind) {
                            var opt = document.createElement('option');
                            opt.value = ind.id;
                            opt.text = ind.nama;
                            if (ind.id == {{ $question->indikator_id ?? 'null' }}) {
                                opt.selected = true;
                            }
                            indikatorSelect.appendChild(opt);
                        });
                    });
            }
        });

        // Script untuk memastikan bobot jawaban hanya bisa dipilih satu kali
        const scoreSelects = Array.from(document.querySelectorAll('.score-select'));

        function updateScoreOptions() {
            const selected = scoreSelects.map(sel => sel.value);

            scoreSelects.forEach((select, idx) => {
                Array.from(select.options).forEach(option => {
                    option.disabled = false;
                    if (option.value && selected.includes(option.value) && select.value !== option.value) {
                        option.disabled = true;
                    }
                });
            });
        }

        scoreSelects.forEach(select => {
            select.addEventListener('change', updateScoreOptions);
        });

        // Inisialisasi saat halaman pertama kali dibuka
        updateScoreOptions();
    </script>
@endsection
