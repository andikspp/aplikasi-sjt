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
        </div>

        <div class="row align-items-center mb-3">
            <div class="col d-flex justify-content-end align-items-center gap-2">
                <select id="filterStatus" class="form-select w-auto me-2">
                    <option value="">-- Semua Status --</option>
                    <option value="aktif">Aktif</option>
                    <option value="belum">Belum Mulai</option>
                    <option value="selesai">Selesai</option>
                </select>
                <select id="filterRole" class="form-select w-auto me-2">
                    <option value="">-- Semua Role --</option>
                    <option value="Guru">Guru</option>
                    <option value="Kepala Sekolah">Kepala Sekolah</option>
                </select>
                <a href="{{ route('create.QuestionSet') }}" class="btn btn-custom">Tambah Paket Soal</a>
            </div>
        </div>

        <div class="row mt-3" id="paket-soal-list">
            {{-- Data akan diisi oleh jQuery --}}
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.3/dist/sweetalert2.all.min.js"></script>
    <script>
        function confirmDeletion(questionId) {
            event.preventDefault();
            Swal.fire({
                title: 'Anda yakin?',
                text: "Data akan dihapus secara permanen!",
                icon: 'warning',
                input: 'text',
                inputLabel: 'Alasan penghapusan',
                inputPlaceholder: 'Masukkan alasan penghapusan paket soal',
                inputValidator: (value) => {
                    if (!value) {
                        return 'Alasan penghapusan wajib diisi!';
                    }
                },
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Set alasan ke input hidden di form
                    let form = document.getElementById('delete-form-' + questionId);
                    let alasanInput = form.querySelector('input[name="alasan_penghapusan"]');
                    if (!alasanInput) {
                        alasanInput = document.createElement('input');
                        alasanInput.type = 'hidden';
                        alasanInput.name = 'alasan_penghapusan';
                        form.appendChild(alasanInput);
                    }
                    alasanInput.value = result.value;
                    form.submit();
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

        $(document).ready(function() {
            const csrfToken = $('meta[name=csrf-token]').attr('content');
            let allData = [];

            // Load data awal paket soal via AJAX
            $.get('{{ route('admin.soal') }}', function(data) {
                console.log(data);
                allData = data;
                renderPaketSoal(allData);
            });

            // Fungsi render paket soal dengan filter status dan role
            function renderPaketSoal(data) {
                var role = $('#filterRole').val();
                var status = $('#filterStatus').val();
                var now = moment();

                var filtered = data.filter(function(set) {
                    // Filter role
                    var roleMatch = !role || set.role === role;
                    // Filter status waktu
                    var mulai = moment(set.start_exam);
                    var selesai = moment(set.end_exam);
                    var statusMatch = true;
                    if (status === 'aktif') {
                        statusMatch = now.isBetween(mulai, selesai, null, '[]');
                    } else if (status === 'belum') {
                        statusMatch = now.isBefore(mulai);
                    } else if (status === 'selesai') {
                        statusMatch = now.isAfter(selesai);
                    }
                    return roleMatch && statusMatch;
                });

                var html = '';
                if (!filtered.length) {
                    html =
                        '<div class="col-12"><div class="alert alert-danger text-center">Tidak ada paket soal.</div></div>';
                } else {
                    filtered.forEach(function(set) {
                        html += `
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card border-light shadow-sm rounded-lg mb-4">
                            <div class="card-body">
                                <h5 class="card-title d-flex justify-content-between align-items-center">
                                    ${set.name}
                                    <span class="badge bg-custom">${set.role.charAt(0).toUpperCase() + set.role.slice(1)}</span>
                                </h5>
                                <p class="card-text text-muted mb-3">
                                    <i class="bi bi-calendar-event"></i> Mulai Ujian:
                                    ${moment(set.start_exam).format('DD-MM-YYYY HH:mm')} WIB<br>
                                    <i class="bi bi-calendar-check"></i> Selesai Ujian:
                                    ${moment(set.end_exam).format('DD-MM-YYYY HH:mm')} WIB<br>
                                    <i class="bi bi-file-text"></i> Jumlah Soal: ${set.questions.length}
                                    <br>
                                    <i class="bi bi-person"></i> Dibuat oleh: <b>${set.creator_name ?? '-'}</b>
                                </p>
                                <div class="d-flex justify-content-between">
                                    <a href="${set.role === 'Kepala Sekolah'
                                        ? '{{ route('admin.ks.detail-soal', ':id') }}'.replace(':id', set.id)
                                        : '{{ route('admin.guru.detail-soal', ':id') }}'.replace(':id', set.id)
                                    }" class="btn btn-custom">
                                        <i class="bi bi-eye"></i> Lihat Soal
                                    </a>
                                    <a href="${'{{ route('admin.editPaketSoal', ':id') }}'.replace(':id', set.id)}" class="btn btn-warning text-white">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <button type="button" class="btn btn-danger"
                                        onclick="confirmDeletion(${set.id})">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                    <form id="delete-form-${set.id}"
                                        action="${'{{ route('admin.deletePaketSoal', ':id') }}'.replace(':id', set.id)}"
                                        method="POST"
                                        style="display: none;">
                                        <input type="hidden" name="_token" value="${csrfToken}">
                                        <input type="hidden" name="_method" value="DELETE">
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                `;
                    });
                }
                $('#paket-soal-list').html(html);
            }

            // Event filter role dan status
            $('#filterRole, #filterStatus').on('change', function() {
                renderPaketSoal(allData);
            });
        });
    </script>
    <!-- Tambahkan moment.js untuk format tanggal -->
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
@endsection
