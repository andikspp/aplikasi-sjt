@extends('layout.admin.admin-layout')

@section('title', 'Data Hasil Guru')

@section('content')
    <style>
        .table th,
        .table td {
            vertical-align: middle;
            text-align: center;
        }

        .table th {
            background-color: #005689;
            color: #fff;
            font-weight: bold;
        }

        .table td {
            background-color: #f8f9fa;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #e9ecef;
        }

        .table-bordered {
            border: 1px solid #dee2e6;
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid #dee2e6;
        }

        .btn-dikmas {
            background-color: #005689;
            color: white;
        }

        .btn-dikmas:hover {
            background-color: #004a73;
            color: white;
        }
    </style>

    <div class="container mt-5">
        <div class="mb-3">
            <a href="{{ route('hasil') }}" class="btn btn-dikmas">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
        <h2 class="text-center mb-4">Hasil Ujian Guru</h2>
        <div class="row align-items-end mb-4 g-2">
            <div class="col-md-8">
                <form action="{{ route('search.guru') }}" method="GET" class="row g-2">
                    <div class="col-md-4">
                        <label for="search" class="form-label mb-1">Cari</label>
                        <input type="text" name="search" id="search" class="form-control"
                            placeholder="Nama/Username/Instansi" value="{{ request('search') }}">
                    </div>
                    <div class="col-md-4">
                        <label for="jenis_paud" class="form-label mb-1">Jenis PAUD</label>
                        <select name="jenis_paud" id="jenis_paud" class="form-select">
                            <option value="">Semua Jenis PAUD</option>
                            <option value="Mitra" {{ request('jenis_paud') == 'Mitra' ? 'selected' : '' }}>Mitra</option>
                            <option value="Pembelajar" {{ request('jenis_paud') == 'Pembelajar' ? 'selected' : '' }}>
                                Pembelajar</option>
                        </select>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100" style="height:38px; max-width:120px;">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </form>
            </div>
            <div class="col-md-4 d-flex justify-content-md-end justify-content-start gap-2 mt-3 mt-md-0">
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#exportModal">
                    <i class="bi bi-download me-1"></i> Ekspor Data
                </button>
                <a href="{{ route('grafik.guru') }}" class="btn btn-primary">
                    <i class="bi bi-bar-chart-fill me-1"></i> Lihat Grafik
                </a>
            </div>
        </div>

        {{-- Modal Export --}}
        <div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form action="{{ route('admin.results.guru') }}" method="GET" class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exportModalLabel">Ekspor Data Hasil Tes Guru</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-info py-2 mb-3" style="font-size: 0.95rem;">
                            <i class="bi bi-info-circle me-1"></i>
                            Pengisian tanggal <b>opsional</b>. Klik <b>Ekspor</b> langsung untuk mengunduh semua data, atau
                            isi tanggal untuk filter data berdasarkan rentang waktu.
                        </div>
                        <div class="mb-3">
                            <label for="start_date" class="form-label">Dari Tanggal:</label>
                            <input type="date" name="start_date" id="start_date" class="form-control"
                                value="{{ request('start_date') }}">
                        </div>
                        <div class="mb-3">
                            <label for="end_date" class="form-label">Sampai Tanggal:</label>
                            <input type="date" name="end_date" id="end_date" class="form-control"
                                value="{{ request('end_date') }}">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Ekspor</button>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="table-responsive mt-4">
            <table class="table table-striped table-bordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>No.</th>
                        <th>Nama</th>
                        <th>Instansi</th>
                        <th>Jenis</th>
                        <th>Paket Soal</th>
                        <th>Waktu Selesai</th>
                        <th>Score</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($results as $result)
                        <tr>
                            <td>{{ $loop->iteration + $results->firstItem() - 1 }}</td>
                            <td>
                                <a href="{{ route('jawaban.peserta', ['userId' => $result->id]) }}">
                                    {{ $result->name }}
                                </a>
                            </td>
                            <td>{{ strtoupper($result->instansi) }}</td>
                            <td>{{ ucwords($result->jenis_paud) }}</td>
                            <td>{{ $result->question_set_name }}</td>
                            <td>{{ \Carbon\Carbon::parse($result->ended_at)->setTimezone('Asia/Jakarta')->format('d-m-Y H:i') }}
                                WIB</td>
                            <td>{{ $result->score }}</td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm"
                                    onclick="confirmDeletion({{ $result->id }}, {{ $result->quiz_attempt_id }})">Hapus</button>
                                <form id="delete-form-{{ $result->id }}"
                                    action="{{ route('hapus.hasil.guru', $result->id) }}" method="POST"
                                    style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">Data Tidak Ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="d-flex justify-content-center">
                {{ $results->links('pagination.pagination') }}
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.3/dist/sweetalert2.all.min.js"></script>

    <script>
        function confirmDeletion(guruId, quizAttemptId) {
            // Cek jumlah admin dulu
            $.get('{{ route('admin.checkAdminCount') }}', function(response) {
                const adminCount = response.count;
                Swal.fire({
                    title: adminCount > 1 ? 'Ajukan Penghapusan Hasil Tes' :
                        'Konfirmasi Penghapusan Hasil Tes',
                    text: adminCount > 1 ?
                        "Masukkan alasan penghapusan. Permintaan akan dikonfirmasi oleh admin lain." :
                        "Masukkan alasan penghapusan. Data akan langsung dihapus.",
                    icon: 'warning',
                    input: 'text',
                    inputLabel: 'Alasan penghapusan',
                    inputPlaceholder: 'Masukkan alasan penghapusan hasil tes',
                    inputValidator: (value) => {
                        if (!value) {
                            return 'Alasan penghapusan wajib diisi!';
                        }
                    },
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: adminCount > 1 ? 'Ajukan' : 'Hapus',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        if (adminCount > 1) {
                            // Proses pengajuan seperti sebelumnya
                            $.ajax({
                                url: '{{ route('admin.permintaan.store') }}',
                                type: 'POST',
                                data: {
                                    _token: '{{ csrf_token() }}',
                                    user_id: guruId,
                                    quiz_attempt_id: quizAttemptId,
                                    reason: result.value
                                },
                                success: function(response) {
                                    Swal.fire('Berhasil',
                                            'Permintaan penghapusan telah diajukan dan menunggu persetujuan admin lain.',
                                            'success')
                                        .then(() => location.reload());
                                },
                                error: function(xhr) {
                                    let msg = 'Terjadi kesalahan.';
                                    if (xhr.responseJSON && xhr.responseJSON.message) {
                                        msg = xhr.responseJSON.message;
                                    }
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Gagal',
                                        text: msg
                                    });
                                }
                            });
                        } else {
                            // Langsung hapus
                            $.ajax({
                                url: '{{ route('hapus.hasil.guru', ':id') }}'.replace(':id',
                                    guruId),
                                type: 'POST',
                                data: {
                                    _token: '{{ csrf_token() }}',
                                    reason: result.value,
                                    quiz_attempt_id: quizAttemptId
                                },
                                success: function(response) {
                                    Swal.fire('Berhasil',
                                            'Hasil tes berhasil dihapus.',
                                            'success')
                                        .then(() => location.reload());
                                },
                                error: function(xhr) {
                                    let msg = 'Terjadi kesalahan.';
                                    if (xhr.responseJSON && xhr.responseJSON.message) {
                                        msg = xhr.responseJSON.message;
                                    }
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Gagal',
                                        text: msg
                                    });
                                }
                            });
                        }
                    }
                });
            });
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
