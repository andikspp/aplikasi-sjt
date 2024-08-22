@extends('layout.admin.admin-layout')

@section('title', 'Data Kepala Sekolah')

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
        <h2 class="text-center mb-4">Data Peserta Kepala Sekolah</h2>
        
        <!-- Export to Excel button -->
        <div class="text-right mb-3">
            <a href="#" class="btn btn-success">Export to Excel</a> <!-- Export button -->
        </div>

        @if ($results instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Telepon</th>
                            <th>Instansi</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Aksi</th> <!-- Added action column -->
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($results as $result)
                            <tr>
                                <td>{{ $result->name ?? 'N/A' }}</td>
                                <td>{{ $result->email ?? 'N/A' }}</td>
                                <td>{{ $result->telepon ?? 'N/A' }}</td>
                                <td>{{ strtoupper($result->instansi) ?? 'N/A' }}</td>
                                <td>{{ ucwords($result->role) ?? 'N/A' }}</td>
                                <td>{{ ucwords(str_replace('_', ' ', $result->status)) }}</td>
                                <td>
                                    <a href="#" class="btn btn-warning btn-sm">Edit</a> <!-- Edit button -->
                                    <form action="#" method="POST" style="display:inline;"> <!-- Delete form -->
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No results found</td> <!-- Adjusted colspan -->
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination links -->
            <div class="d-flex justify-content-center mt-4">
                {{ $results->links() }}
            </div>
        @else
            <div class="alert alert-warning">
                Unexpected data type for results. Please check your controller.
            </div>
        @endif
    </div>
@endsection