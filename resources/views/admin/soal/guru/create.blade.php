@extends('layout.admin.admin-layout')

@section('title', 'Tambah Soal')

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
            <h2 class="text-center mb-4">Tambah Soal Guru</h2>
            <form action="{{ route('admin.storeQuestionGuru') }}" method="POST">
                @csrf

                <small class="text-muted mb-2 d-block">
                    <span style="color: red">*</span> Wajib diisi
                </small>

                <div class="mb-3">
                    <label for="question_set_id" class="form-label">Paket Soal</label>
                    <select class="form-select" id="question_set_id" name="question_set_id" disabled>
                        <option value="{{ $selectedSet->id }}">{{ $selectedSet->name }}</option>
                    </select>
                    <input type="hidden" name="question_set_id" value="{{ $selectedSet->id }}">
                </div>

                <div class="mb-3">
                    <label for="kompetensi" class="form-label">Kompetensi <span style="color: red">*</span></label>
                    <select name="kompetensi_guru" id="kompetensi" class="form-control" required>
                        @foreach ($kompetensi as $item)
                            <option value="">Pilih Kompetensi</option>
                            <option value="{{ $item->id }}">{{ $item->nama }}</option>
                        @endforeach
                    </select>
                    <small class="text-muted">Pilih atau ketik untuk menambah kompetensi baru.</small>
                </div>

                <div class="mb-3">
                    <label for="indikator" class="form-label">Indikator <span style="color: red">*</span></label>
                    <select name="indikator_guru" id="indikator" class="form-control" required>
                        <option value="">-- Pilih atau tambah indikator --</option>
                        {{-- Opsi akan diisi via JS --}}
                    </select>
                </div>

                <div class="mb-3">
                    <label for="question_text" class="form-label">Pertanyaan <span style="color: red">*</span></label>
                    <textarea class="form-control" id="question_text" name="question_text" rows="4" required></textarea>
                </div>

                <div class="mb-3">
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label for="option_a" class="form-label">Jawaban 1 <span style="color: red">*</span></label>
                            <textarea class="form-control" id="option_a" name="option_a" rows="2" required></textarea>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="score_a" class="form-label">Pembobotan Jawaban 1 <span
                                    style="color: red">*</span></label>
                            <select class="form-select" id="score_a" name="score_a" required>
                                <option value="" selected disabled>-- Pilih Bobot --</option>
                                <option value="4">4</option>
                                <option value="3">3</option>
                                <option value="2">2</option>
                                <option value="1">1</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label for="option_a" class="form-label">Jawaban 2 <span style="color: red">*</span></label>
                            <textarea class="form-control" id="option_b" name="option_b" rows="2" required></textarea>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="score_a" class="form-label">Pembobotan Jawaban 2 <span
                                    style="color: red">*</span></label>
                            <select class="form-select" id="score_b" name="score_b" required>
                                <option value="" selected disabled>-- Pilih Bobot --</option>
                                <option value="4">4</option>
                                <option value="3">3</option>
                                <option value="2">2</option>
                                <option value="1">1</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label for="option_a" class="form-label">Jawaban 3 <span style="color: red">*</span></label>
                            <textarea class="form-control" id="option_c" name="option_c" rows="2" required></textarea>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="score_a" class="form-label">Pembobotan Jawaban 3 <span
                                    style="color: red">*</span></label>
                            <select class="form-select" id="score_c" name="score_c" required>
                                <option value="" selected disabled>-- Pilih Bobot --</option>
                                <option value="4">4</option>
                                <option value="3">3</option>
                                <option value="2">2</option>
                                <option value="1">1</option>
                            </select>
                        </div>
                    </div>
                </div>


                <div class="mb-3">
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label for="option_a" class="form-label">Jawaban 4 <span style="color: red">*</span></label>
                            <textarea class="form-control" id="option_d" name="option_d" rows="2" required></textarea>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="score_a" class="form-label">Pembobotan Jawaban 4 <span
                                    style="color: red">*</span></label>
                            <select class="form-select" id="score_d" name="score_d" required>
                                <option value="" selected disabled>-- Pilih Bobot --</option>
                                <option value="4">4</option>
                                <option value="3">3</option>
                                <option value="2">2</option>
                                <option value="1">1</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-danger me-2" onclick="goBack()">Kembali</button>
                    <button type="submit" class="btn btn-success me-2">Simpan</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        function goBack() {
            window.history.back();
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

                // Event listener untuk perubahan pada select kompetensi
                $('#kompetensi').on('change', function() {
                    let kompetensiId = $(this).val();
                    let $indikator = $('#indikator');
                    $indikator.empty();
                    $indikator.append('<option value="">-- Pilih atau tambah indikator --</option>');

                    if (kompetensiId) {
                        $.ajax({
                            url: '/admin/indikator/by-kompetensi/' + kompetensiId,
                            type: 'GET',
                            success: function(data) {
                                data.forEach(function(item) {
                                    $indikator.append('<option value="' + item.id + '">' +
                                        item.nama + '</option>');
                                });
                                // Jika pakai select2, refresh
                                $indikator.val(null).trigger('change');
                            }
                        });
                    } else {
                        $indikator.val(null).trigger('change');
                    }
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

                // Script untuk memastikan bobot jawaban hanya bisa dipilih satu kali
                const scoreSelects = [
                    document.getElementById('score_a'),
                    document.getElementById('score_b'),
                    document.getElementById('score_c'),
                    document.getElementById('score_d')
                ];

                function updateScoreOptions() {
                    // Ambil semua value yang sudah dipilih
                    const selected = scoreSelects.map(sel => sel.value);

                    scoreSelects.forEach((select, idx) => {
                        Array.from(select.options).forEach(option => {
                            // Enable semua dulu
                            option.disabled = false;
                            // Jika value ini sudah dipilih di select lain, disable
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
            });
        </script>
    @endpush
@endsection
