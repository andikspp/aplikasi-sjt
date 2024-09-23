@extends('layout.admin.admin-layout')

@section('title', 'Tambah Soal')

@section('content')
    <div class="container mt-5">
        <h2 class="text-center mb-4">Tambah Soal Guru</h2>
        <form action="{{ route('admin.storeQuestion') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="question_set_id" class="form-label">Paket Soal</label>
                <select class="form-select" id="question_set_id" name="question_set_id" required>
                    @php
                        $questionSets = App\Models\QuestionSet::where('role', 'Guru')->get();
                    @endphp
                    @foreach ($questionSets as $set)
                        <option value="{{ $set->id }}">{{ $set->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="question_set_id" class="form-label">Kompetensi</label>
                <select class="form-select" id="kompetensi_id" name="kompetensi_id" required>
                    @php
                        $kompetensi = App\Models\Kompetensi::where('role', 'Guru')->get();
                    @endphp
                    @foreach ($kompetensi as $kompeten)
                        <option value="{{ $kompeten->id }}">{{ $kompeten->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="question_set_id" class="form-label">Indikator</label>
                <select class="form-select" id="indikator_id" name="indikator_id" required>
                    @php
                        $indikator = App\Models\Indikator::whereHas('kompetensi', function ($query) {
                            $query->where('role', 'Guru');
                        })->get();
                    @endphp
                    @foreach ($indikator as $ind)
                        <option value="{{ $ind->id }}">{{ $ind->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="question_text" class="form-label">Pertanyaan</label>
                <textarea class="form-control" id="question_text" name="question_text" rows="4" required></textarea>
            </div>

            <div class="mb-3">
                <div class="row">
                    <div class="col-md-8 mb-3">
                        <label for="option_a" class="form-label">Jawaban 1</label>
                        <input type="text" class="form-control" id="option_a" name="option_a" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="score_a" class="form-label">Pembobotan Jawaban 1</label>
                        <select class="form-select" id="score_a" name="score_a" required>
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
                        <label for="option_a" class="form-label">Jawaban 2</label>
                        <input type="text" class="form-control" id="option_b" name="option_b" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="score_a" class="form-label">Pembobotan Jawaban 2</label>
                        <select class="form-select" id="score_b" name="score_b" required>
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
                        <label for="option_a" class="form-label">Jawaban 3</label>
                        <input type="text" class="form-control" id="option_c" name="option_c" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="score_a" class="form-label">Pembobotan Jawaban 3</label>
                        <select class="form-select" id="score_c" name="score_c" required>
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
                        <label for="option_a" class="form-label">Jawaban 4</label>
                        <input type="text" class="form-control" id="option_d" name="option_d" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="score_a" class="form-label">Pembobotan Jawaban 4</label>
                        <select class="form-select" id="score_d" name="score_d" required>
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
