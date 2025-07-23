@extends('layout.admin.admin-layout')

@section('title', 'Detail Soal')

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

        .btn-custom {
            background-color: #005689;
            color: white;
        }

        .btn-custom:hover {
            background-color: #012a41;
            color: white;
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
            <a href="{{ route('admin.soal') }}" class="btn btn-dikmas">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
        <h2 class="text-center mb-4">{{ $questionSet->name }}</h2>
        <div class="row mb-3">
            <div class="col d-flex justify-content-end align-items-center gap-2">
                <select id="filterKompetensi" class="form-select w-auto me-2">
                    <option value="">-- Semua Kompetensi --</option>
                    @foreach ($kompetensi as $kompeten)
                        <option value="{{ $kompeten->id }}">{{ $kompeten->nama }}</option>
                    @endforeach
                </select>
                <select id="perPage" class="form-select w-auto me-2">
                    <option value="5">5</option>
                    <option value="10" selected>10</option>
                    <option value="all">All</option>
                </select>
                <a href="{{ route('admin.soal.ks.create', ['questionSetId' => $questionSet->id]) }}" class="btn btn-custom">
                    Tambah Soal
                </a>
            </div>
        </div>
        <table class="table table-striped table-bordered table-hover" id="soalTable">
            <thead class="thead-dark">
                <tr>
                    <th>No.</th>
                    <th>Pertanyaan</th>
                    <th>Jawaban 1</th>
                    <th>Bobot 1</th>
                    <th>Jawaban 2</th>
                    <th>Bobot 2</th>
                    <th>Jawaban 3</th>
                    <th>Bobot 3</th>
                    <th>Jawaban 4</th>
                    <th>Bobot 4</th>
                    <th>Kompetensi</th>
                    <th>Indikator</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @if ($questions->isEmpty())
                    <tr>
                        <td colspan="13" class="text-center">Tidak ada soal.</td>
                    </tr>
                @else
                    @foreach ($questions as $question)
                        <tr data-kompetensi="{{ $question->kompetensi_id }}">
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $question->question_text }}</td>
                            @foreach ($question->answers as $index => $answer)
                                @if ($index == 0)
                                    <td>{{ $answer->answer_text }}</td>
                                    <td>{{ $answer->score }}</td>
                                @elseif ($index == 1)
                                    <td>{{ $answer->answer_text }}</td>
                                    <td>{{ $answer->score }}</td>
                                @elseif ($index == 2)
                                    <td>{{ $answer->answer_text }}</td>
                                    <td>{{ $answer->score }}</td>
                                @elseif ($index == 3)
                                    <td>{{ $answer->answer_text }}</td>
                                    <td>{{ $answer->score }}</td>
                                @endif
                            @endforeach
                            <td>{{ $question->kompetensi ? $question->kompetensi->nama : '-' }}</td>
                            <td>{{ $question->indikator ? $question->indikator->nama : '-' }}</td>
                            <td>
                                <a href="{{ route('admin.soal.edit.ks', $question->id) }}"
                                    class="btn btn-warning btn-sm">Edit</a>
                                <form id="delete-form-{{ $question->id }}"
                                    action="{{ route('hapus.soal', $question->id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger btn-sm"
                                        onclick="confirmDelete({{ $question->id }})">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>

        {{-- <div class="d-flex justify-content-center">
            {{ $questions->links('pagination.pagination') }}
        </div> --}}
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.3/dist/sweetalert2.all.min.js"></script>
    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Konfirmasi Penghapusan',
                text: 'Anda yakin ingin menghapus soal ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Jika dikonfirmasi, kirimkan formulir
                    document.getElementById('delete-form-' + id).submit();
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
            function loadSoal(page = 1) {
                var kompetensiId = $('#filterKompetensi').val();
                var perPage = $('#perPage').val();
                var questionSetId = "{{ $questionSet->id }}";

                $.get("{{ route('admin.soal.filter') }}", {
                    questionSetId: questionSetId,
                    kompetensiId: kompetensiId,
                    perPage: perPage,
                    page: page // penting untuk pagination
                }, function(data) {
                    var html = '';
                    if (data.data.length === 0) {
                        html = '<tr><td colspan="13" class="text-center">Tidak ada soal.</td></tr>';
                    } else {
                        data.data.forEach(function(q, idx) {
                            html += `<tr data-kompetensi="${q.kompetensi_id}">
                        <td>${idx + 1 + ((data.current_page-1)*data.per_page)}</td>
                        <td>${q.question_text}</td>`;
                            for (let i = 0; i < 4; i++) {
                                if (q.answers[i]) {
                                    html +=
                                        `<td>${q.answers[i].answer_text}</td><td>${q.answers[i].score}</td>`;
                                } else {
                                    html += `<td>-</td><td>-</td>`;
                                }
                            }
                            html += `<td>${q.kompetensi ? q.kompetensi.nama : '-'}</td>
                        <td>${q.indikator ? q.indikator.nama : '-'}</td>
                        <td>
                            <a href="{{ route('admin.soal.edit.ks', '') }}/${q.id}" class="btn btn-warning btn-sm">Edit</a>
                            <form id="delete-form-${q.id}" action="{{ route('hapus.soal', '') }}/${q.id}" method="POST" style="display:inline;">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete(${q.id})">Hapus</button>
                            </form>
                        </td>
                    </tr>`;
                        });
                    }
                    $('#soalTable tbody').html(html);

                    // Handle pagination
                    var paginationHtml = '';
                    if (perPage !== 'all' && data.last_page > 1) {
                        paginationHtml += '<nav><ul class="pagination justify-content-center">';
                        for (let i = 1; i <= data.last_page; i++) {
                            paginationHtml += `<li class="page-item${i === data.current_page ? ' active' : ''}">
                        <a class="page-link" href="#" data-page="${i}">${i}</a>
                    </li>`;
                        }
                        paginationHtml += '</ul></nav>';
                    }
                    $('#pagination-wrapper').html(paginationHtml);
                });
            }

            // Trigger loadSoal saat filter berubah
            $('#filterKompetensi, #perPage').on('change', function() {
                loadSoal(1);
            });

            // Handle klik pagination
            $(document).on('click', '.pagination .page-link', function(e) {
                e.preventDefault();
                var page = $(this).data('page');
                loadSoal(page);
            });

            // Tempatkan wrapper pagination di bawah tabel
            $('#soalTable').after('<div id="pagination-wrapper" class="mt-3"></div>');

            // Load awal
            loadSoal(1);
        });
    </script>

@endsection
