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
            <h2 class="text-center mb-4">Edit Soal Guru</h2>
            <form action="{{ route('admin.soal.update.guru', $question->id) }}" method="POST">
                @csrf
                @method('PUT')

                <small class="text-muted mb-2 d-block">
                    <span style="color: red">*</span> Wajib diisi
                </small>

                <div class="mb-3">
                    <label for="question_set_id" class="form-label">Paket Soal</label>
                    <select class="form-select" id="question_set_id" name="question_set_id" disabled>
                        @php
                            $questionSets = App\Models\QuestionSet::where('role', 'Guru')->get();
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
                    <label for="kompetensi" class="form-label">Kompetensi</label>
                    <select name="kompetensi_guru" id="kompetensi" class="form-control">
                        <option value="">Pilih Kompetensi</option>
                        @foreach ($kompetensi as $item)
                            <option value="{{ $item->id }}"
                                {{ $question->kompetensi_id == $item->id ? 'selected' : '' }}>
                                {{ $item->nama }}
                            </option>
                        @endforeach
                    </select>
                    <small class="text-muted">Pilih atau ketik untuk menambah kompetensi baru.</small>
                </div>

                <div class="mb-3">
                    <label for="indikator" class="form-label">Indikator</label>
                    <select name="indikator_guru" id="indikator" class="form-control">
                        <option value="">-- Pilih atau tambah indikator --</option>
                        {{-- Opsi akan diisi via JS --}}
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
            window.location.href = "/admin/soal/guru/" + questionSetId;
        }
    </script>
    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#kompetensi').select2({
                    tags: true,
                    placeholder: "Pilih atau tambah kompetensi...",
                    width: '100%'
                });

                $('#indikator').select2({
                    tags: true,
                    placeholder: "Pilih atau tambah indikator...",
                    width: '100%'
                });

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

                $('#kompetensi').on('change', function() {
                    var selectedKompetensi = $(this).val(); // ambil value baru
                    var initialIndikator = '{{ $question->indikator_id }}';

                    let $indikator = $('#indikator');
                    $indikator.empty();
                    $indikator.append('<option value="">-- Pilih atau tambah indikator --</option>');

                    if (selectedKompetensi) {
                        $.ajax({
                            url: '/admin/indikator/by-kompetensi/' + selectedKompetensi,
                            type: 'GET',
                            success: function(data) {
                                data.forEach(function(item) {
                                    $indikator.append('<option value="' + item.id + '">' +
                                        item.nama + '</option>');
                                });
                                // Jika user baru pertama kali load, set indikator awal
                                if (selectedKompetensi == '{{ $question->kompetensi_id }}') {
                                    $indikator.val(initialIndikator).trigger('change');
                                } else {
                                    $indikator.val('').trigger('change');
                                }
                            }
                        });
                    } else {
                        $indikator.val(null).trigger('change');
                    }
                });

                // Script untuk memastikan bobot jawaban hanya bisa dipilih satu kali
                const scoreSelects = Array.from(document.querySelectorAll('.score-select'));

                function updateScoreOptions() {
                    const selected = scoreSelects.map(sel => sel.value);

                    scoreSelects.forEach((select, idx) => {
                        Array.from(select.options).forEach(option => {
                            option.disabled = false;
                            if (option.value && selected.includes(option.value) && select.value !==
                                option.value) {
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

                // Trigger event change agar indikator terisi otomatis sesuai kompetensi awal
                $('#kompetensi').trigger('change');
            })
        </script>
    @endpush
@endsection
