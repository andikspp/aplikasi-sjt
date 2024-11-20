@extends('layout.admin.admin-layout')

@section('title', 'Data Hasil Kepala Sekolah')

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
    </style>

    <div class="container mt-5">
        <h2 class="text-center mb-4">Hasil Ujian Kepala Sekolah</h2>
        <div class="d-flex justify-content-between align-items-end mb-3">
            <form action="{{ route('admin.results.kepsek') }}" method="GET" class="d-flex flex-wrap align-items-end">
                <div class="me-3">
                    <label for="start_date" class="form-label">Start Date:</label>
                    <input type="date" name="start_date" id="start_date" class="form-control" required>
                </div>
                <div class="me-3">
                    <label for="end_date" class="form-label">End Date:</label>
                    <input type="date" name="end_date" id="end_date" class="form-control" required>
                </div>
                <div>
                    <button type="submit" class="btn btn-success">Export to Excel</button>
                </div>
            </form>
            <form action="{{ route('search.ks') }}" method="GET" class="d-flex flex-wrap align-items-end">
                <!-- Search Input -->
                <div class="me-3">
                    <label for="search" class="form-label">Search:</label>
                    <input type="text" name="search" id="search" class="form-control"
                        placeholder="Nama/Username/Instansi" value="{{ request('search') }}">
                </div>

                <!-- Filter Jenis PAUD -->
                <div class="me-3">
                    <label for="jenis_paud" class="form-label">Jenis PAUD:</label>
                    <select name="jenis_paud" id="jenis_paud" class="form-select">
                        <option value="">Semua Jenis PAUD</option>
                        <option value="Mitra" {{ request('jenis_paud') == 'Mitra' ? 'selected' : '' }}>Mitra</option>
                        <option value="Pembelajar" {{ request('jenis_paud') == 'Pembelajar' ? 'selected' : '' }}>Pembelajar
                        </option>
                    </select>
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit" class="btn btn-primary">Cari</button>
                </div>
            </form>
            <a href="{{ route('grafik.kepsek') }}" class="btn btn-primary ms-3">Lihat Grafik</a>
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>No.</th>
                        <th>Nama</th>
                        <th>Username</th>
                        <th>Telepon</th>
                        <th>Instansi</th>
                        <th>Jenis</th>
                        <th>Role</th>
                        <th>Paket Soal</th>
                        <th>Waktu Selesai</th>
                        <th>Score</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($results as $result)
                        <tr>
                            <td>{{ $loop->iteration + $results->firstItem() - 1 }}</td>
                            <td>
                                <a href="{{ route('jawaban.peserta', ['userId' => $result->id]) }}">
                                    {{ $result->name }}
                                </a>
                            </td>
                            <td>{{ $result->username }}</td>
                            <td>{{ $result->telepon }}</td>
                            <td>{{ strtoupper($result->instansi) }}</td>
                            <td>{{ ucwords($result->jenis_paud) }}</td>
                            <td>{{ ucwords($result->role) }}</td>
                            <td>{{ $result->question_set_name }}</td>
                            <td>{{ \Carbon\Carbon::parse($result->ended_at)->setTimezone('Asia/Jakarta')->format('d-m-Y H:i') }}
                                WIB
                            </td>
                            <td>{{ $result->score }}</td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm"
                                    onclick="confirmDeletion({{ $result->id }})">Hapus</button>

                                <form id="delete-form-{{ $result->id }}"
                                    action="{{ route('hapus.hasil.kepsek', $result->id) }}" method="POST"
                                    style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="d-flex justify-content-center">
                {{ $results->links('pagination.pagination') }}
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.3/dist/sweetalert2.all.min.js"></script>
    <script>
        function confirmDeletion(kepsekId) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Anda tidak akan bisa mengembalikan data ini!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + kepsekId).submit();
                }
            })
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
