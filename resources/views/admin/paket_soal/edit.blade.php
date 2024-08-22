@extends('layout.admin.admin-layout')

@section('title', 'Edit Paket Soal')

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

    <div class="container">
        @if ($errors->any())
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: '@foreach ($errors->all() as $error) {{ $error }} @endforeach',
                    });
                });
            </script>
        @endif
        <div class="form-container">
            <h5 class="mb-4">Edit Paket Soal</h5>
            <form action="{{ route('admin.putPaketSoal', $questionSet->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="name" class="form-label">Nama Paket</label>
                    <input type="text" class="form-control" id="name" name="name"
                        value="{{ old('name', $questionSet->name) }}" required>
                </div>
                <div class="mb-3">
                    <label for="start_exam" class="form-label">Waktu Mulai Ujian</label>
                    <input type="datetime-local" class="form-control" id="start_exam" name="start_exam" required>
                </div>
                <div class="mb-3">
                    <label for="end_exam" class="form-label">Waktu Berakhir Ujian</label>
                    <input type="datetime-local" class="form-control" id="end_exam" name="end_exam" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Role</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" id="role_guru" name="role" value="Guru"
                            {{ old('role', $questionSet->role) == 'Guru' ? 'checked' : '' }} required>
                        <label class="form-check-label" for="role_guru">Guru</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" id="role_kepala_sekolah" name="role"
                            value="Kepala Sekolah"
                            {{ old('role', $questionSet->role) == 'Kepala Sekolah' ? 'checked' : '' }} required>
                        <label class="form-check-label" for="role_kepala_sekolah">Kepala Sekolah</label>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="time_limit" class="form-label">Durasi Ujian (Menit)</label>
                    <input type="number" class="form-control" id="time_limit" name="time_limit"
                        value="{{ old('time_limit', $questionSet->time_limit) }}" required>
                </div>
                <a href="{{ route('admin.soal') }}" class="btn btn-danger">
                    Kembali
                </a>
                <button type="submit" class="btn btn-success">Simpan</button>
            </form>
        </div>
    </div>
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
                title: 'Berhasil',
                text: '{{ session('success') }}',
            });
        @endif
    </script>
@endsection
