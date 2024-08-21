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
                                    <a href="{{ route('admin.detail-soal', ['question_set_id' => $set->id]) }}"
                                        class="btn btn-custom">
                                        <i class="bi bi-eye"></i> Lihat Soal
                                    </a>
                                    <button class="btn btn-danger delete-set" data-id="{{ $set->id }}">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Delete question set functionality
            document.querySelectorAll('.delete-set').forEach(button => {
                button.addEventListener('click', function() {
                    const setId = this.getAttribute('data-id');

                    Swal.fire({
                        title: 'Anda yakin ingin menghapus paket soal ini?',
                        text: "Tindakan ini tidak dapat dibatalkan!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Send delete request
                            fetch(`#`, {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                    body: JSON.stringify({
                                        id: setId
                                    })
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        Swal.fire(
                                            'Terhapus!',
                                            'Paket soal telah dihapus.',
                                            'success'
                                        ).then(() => {
                                            location.reload();
                                        });
                                    } else {
                                        Swal.fire(
                                            'Error!',
                                            'Terjadi kesalahan saat menghapus paket soal.',
                                            'error'
                                        );
                                    }
                                });
                        }
                    });
                });
            });
        });

        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
            });
        @endif
    </script>
@endsection
