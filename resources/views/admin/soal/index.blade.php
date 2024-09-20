@extends('layout.admin.admin-layout')

@section('title', 'Paket Soal')

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">
    <style>
        .btn-custom {
            background-color: #005689;
            color: white;
        }

        .btn-custom:hover {
            background-color: #012a41;
            color: white;
        }

        .bg-custom {
            background-color: #005689;
            color: white;
        }
    </style>

    <div class="container mt-5">
        <div class="row align-items-center">
            <div class="col">
                <h3>Paket Soal</h3>
            </div>
            <div class="col-auto">
                <a href="{{ route('create.QuestionSet') }}" class="btn btn-custom">Tambah Paket Soal</a>
            </div>
        </div>

        <div class="row mt-3">
            @foreach ($questionSets as $set)
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card border-light shadow-sm rounded-lg mb-4">
                            <div class="card-body">
                                <h5 class="card-title d-flex justify-content-between align-items-center">
                                    {{ $set->name }}
                                    <span class="badge bg-custom">{{ ucwords($set->role) }}</span>
                                </h5>
                                <p class="card-text text-muted mb-3">
                                    <i class="bi bi-calendar-event"></i> Mulai Ujian:
                                    {{ \Carbon\Carbon::parse($set->start_exam)->format('d-m-Y H:i') }} WIB<br>
                                    <i class="bi bi-calendar-check"></i> Selesai Ujian:
                                    {{ \Carbon\Carbon::parse($set->end_exam)->format('d-m-Y H:i') }} WIB<br>
                                    <i class="bi bi-file-text"></i> Jumlah Soal: {{ $set->questions->count() }}
                                </p>
                                <div class="d-flex justify-content-between">
                                    <a href="{{ $set->role === 'Kepala Sekolah'
                                        ? route('admin.ks.detail-soal', ['question_set_id' => $set->id])
                                        : route('admin.guru.detail-soal', ['question_set_id' => $set->id]) }}"
                                        class="btn btn-custom">
                                        <i class="bi bi-eye"></i> Lihat Soal
                                    </a>
                                    <a href="{{ route('admin.editPaketSoal', ['question_set_id' => $set->id]) }}"
                                        class="btn btn-warning text-white">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <button type="button" class="btn btn-danger"
                                        onclick="confirmDeletion({{ $set->id }})">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>

                                    <!-- Form untuk penghapusan, di-submit secara dinamis melalui JavaScript -->
                                    <form id="delete-form-{{ $set->id }}"
                                        action="{{ route('admin.deletePaketSoal', $set->id) }}" method="POST"
                                        style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.3/dist/sweetalert2.all.min.js"></script>
    <script>
        function confirmDeletion(questionId) {
            event.preventDefault(); // Prevent the form from submitting immediately
            Swal.fire({
                title: 'Anda yakin?',
                text: "Data akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // If confirmed, submit the form
                    document.getElementById('delete-form-' + questionId).submit();
                }
            });
        }

        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
            });
        @endif
    </script>
@endsection
