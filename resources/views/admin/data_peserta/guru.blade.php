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
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No results found</td>
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
