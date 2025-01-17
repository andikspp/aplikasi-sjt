@extends('layout.admin.admin-layout')

@section('title', 'Data Guru')

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
        <h2 class="text-center mb-4">Data Peserta Guru</h2>

        <div class="d-flex justify-content-between mb-3">
            <a href="{{ route('admin.tambah.guru') }}" class="btn btn-success mb-3">Tambah Guru</a>
            <form method="GET" action="{{ route('data.guru') }}" class="mb-3 d-flex align-items-center">
                <input type="text" name="search" value="{{ $search }}" placeholder="Search..."
                    class="form-control me-2" style="width: 150px;" />
                <!-- Filter Jenis PAUD -->
                <select name="jenis_paud" class="form-control me-2" style="width: 150px;">
                    <option value="">Jenis PAUD</option>
                    <option value="mitra" {{ $jenisPaudFilter == 'mitra' ? 'selected' : '' }}>Mitra</option>
                    <option value="pembelajar" {{ $jenisPaudFilter == 'pembelajar' ? 'selected' : '' }}>Pembelajar</option>
                </select>
                <select name="status" class="form-control me-2" style="width: 150px;">
                    <option value="">Status</option>
                    <option value="not_started" {{ $statusFilter == 'not_started' ? 'selected' : '' }}>Not Started</option>
                    <option value="on_going" {{ $statusFilter == 'on_going' ? 'selected' : '' }}>On Going</option>
                    <option value="submitted" {{ $statusFilter == 'submitted' ? 'selected' : '' }}>Submitted</option>
                </select>
                <button type="submit" class="btn btn-primary">Cari</button>
            </form>
        </div>

        @if ($results instanceof \Illuminate\Pagination\LengthAwarePaginator)
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
                            <th>Status</th>
                            <th>Aksi</th> <!-- Tambahkan kolom Aksi -->
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($results as $result)
                            <tr>
                                <td>{{ $loop->iteration + $results->firstItem() - 1 }}</td>
                                <td>{{ $result->name ?? 'N/A' }}</td>
                                <td>{{ $result->username ?? 'N/A' }}</td>
                                <td>{{ $result->telepon ?? 'N/A' }}</td>
                                <td>{{ strtoupper($result->instansi) ?? 'N/A' }}</td>
                                <td>{{ ucwords($result->jenis_paud) ?? 'N/A' }}</td>
                                <td>{{ ucwords($result->role) ?? 'N/A' }}</td>
                                <td>{{ $result->question_set_name ?? 'N/A' }}</td>
                                <td>{{ ucwords(str_replace('_', ' ', $result->status)) }}</td>
                                <td>
                                    <a href="{{ route('admin.edit.guru', $result->id) }}"
                                        class="btn btn-warning btn-sm">Edit</a>
                                    <button type="button" class="btn btn-danger btn-sm"
                                        onclick="confirmDeletion({{ $result->id }})">Hapus</button>

                                    <!-- Form untuk penghapusan, di-submit secara dinamis melalui JavaScript -->
                                    <form id="delete-form-{{ $result->id }}"
                                        action="{{ route('admin.delete.guru', $result->id) }}" method="POST"
                                        style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No results found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="d-flex justify-content-center">
                    {{ $results->links('pagination.pagination') }}
                </div>
            </div>
        @else
            <div class="alert alert-warning">
                Unexpected data type for results. Please check your controller.
            </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.3/dist/sweetalert2.all.min.js"></script>
    <script>
        function confirmDeletion(guruId) {
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
                    // Jika dikonfirmasi, submit form secara dinamis
                    document.getElementById('delete-form-' + guruId).submit();
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
