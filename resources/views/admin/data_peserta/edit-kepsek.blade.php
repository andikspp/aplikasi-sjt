@extends('layout.admin.admin-layout')

@section('title', 'Edit Kepala Sekolah')

@section('content')
    <style>
        .bg-custom {
            background-color: #005689;
            color: white;
        }
    </style>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Edit Data Kepala Sekolah</h2>

        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-custom text-white">
                        <h5 class="card-title mb-0">Edit Form</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.update.kepsek', $kepsek->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <!-- Nama -->
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name', $kepsek->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Telepon -->
                            <div class="mb-3">
                                <label for="telepon" class="form-label">Telepon</label>
                                <input type="text" class="form-control @error('telepon') is-invalid @enderror"
                                    id="telepon" name="telepon" value="{{ old('telepon', $kepsek->telepon) }}" required>
                                @error('telepon')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Instansi -->
                            <div class="mb-3">
                                <label for="instansi" class="form-label">Instansi</label>
                                <input type="text" class="form-control @error('instansi') is-invalid @enderror"
                                    id="instansi" name="instansi" value="{{ old('instansi', $kepsek->instansi) }}"
                                    required>
                                @error('instansi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Role -->
                            <div class="mb-3">
                                <label for="role" class="form-label">Role</label>
                                <select id="role" name="role"
                                    class="form-select @error('role') is-invalid @enderror" required>
                                    <option value="Guru">Guru</option>
                                    <option value="Kepala Sekolah" selected>Kepala Sekolah</option>
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Paket Soal -->
                            <div class="mb-3">
                                <label for="question_set_id" class="form-label">Paket Soal</label>
                                <select id="question_set_id" name="question_set_id"
                                    class="form-select @error('question_set_id') is-invalid @enderror" required>
                                    <option value="">Pilih Paket Soal</option>
                                    @foreach ($questionSets as $id => $name)
                                        <option value="{{ $id }}"
                                            {{ old('question_set_id', $kepsek->question_set_id) == $id ? 'selected' : '' }}>
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('question_set_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select id="status" name="status"
                                    class="form-select @error('status') is-invalid @enderror" required>
                                    <option value="">Pilih Status</option>
                                    <option value="not_started"
                                        {{ old('status', $kepsek->status) == 'not_started' ? 'selected' : '' }}>Not Started
                                    </option>
                                    <option value="on_going"
                                        {{ old('status', $kepsek->status) == 'on_going' ? 'selected' : '' }}>On Going
                                    </option>
                                    <option value="submitted"
                                        {{ old('status', $kepsek->status) == 'submitted' ? 'selected' : '' }}>Submitted
                                    </option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Submit Button -->
                            <div class="d-flex justify-content-between">
                                <button type="submit" class="btn btn-success">Update</button>
                                <button type="button" class="btn btn-danger" onclick="goBack()">Kembali</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
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
    </script>
@endsection
