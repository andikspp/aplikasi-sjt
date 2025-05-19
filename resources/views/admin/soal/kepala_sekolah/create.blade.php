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
            <h2 class="text-center mb-4">Tambah Soal Kepala Sekolah</h2>
            <form action="{{ route('admin.storeQuestion') }}" method="POST">
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
                    <label for="kompetensi_id" class="form-label">Kompetensi <span style="color: red">*</span></label>
                    <select class="form-select" id="kompetensi_id" name="kompetensi_id" required>
                        <option value="">-- Pilih Kompetensi --</option>
                        @foreach ($kompetensi as $kompeten)
                            <option value="{{ $kompeten->id }}">{{ $kompeten->nama }}</option>
                        @endforeach
                    </select>
                </div>


                <div class="mb-3">
                    <label for="indikator_id" class="form-label">Indikator <span style="color: red">*</span></label>
                    <select class="form-select" id="indikator_id" name="indikator_id" required>
                        <option value="">-- Pilih Indikator --</option>
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
                            indikatorSelect.appendChild(opt);
                        });
                    });
            }
        });

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
