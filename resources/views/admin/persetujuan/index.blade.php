@extends('layout.admin.admin-layout')

@section('title', 'Persetujuan Akses')

@section('content')
    <style>
        #table-header th {
            color: white !important;
            background-color: #005689 !important;
        }
    </style>
    <div class="container mt-4">
        <h3 class="mb-4 text-center">Persetujuan Akses</h3>
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead id="table-header">
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Diajukan Oleh</th>
                        <th class="text-center">Peserta</th>
                        <th class="text-center">Alasan</th>
                        <th class="text-center">Tanggal Permintaan</th>
                        <th class="text-center">Status Saya</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($allowances as $item)
                        @php
                            $myApproval = $item->approvals->where('admin_id', auth('admin')->id())->first();
                        @endphp
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td class="text-center">{{ $item->adminrequested->username ?? '-' }}</td>
                            <td class="text-center">{{ $item->peserta->name ?? '-' }}</td>
                            <td class="text-center">{{ $item->reason }}</td>
                            <td class="text-center">{{ \Carbon\Carbon::parse($item->created_at)->format('d-m-Y H:i') }}</td>
                            <td class="text-center">
                                @if ($myApproval)
                                    @if ($myApproval->status === 'approved')
                                        <span class="badge bg-success">Disetujui</span>
                                    @elseif($myApproval->status === 'rejected')
                                        <span class="badge bg-danger">Ditolak</span>
                                    @endif
                                @else
                                    <span class="badge bg-secondary">Belum Diproses</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if (!$myApproval && $item->status == 'pending')
                                    <form action="{{ route('admin.persetujuan.approve', $item->id) }}" method="POST"
                                        class="d-inline form-approve">
                                        @csrf
                                        <button type="button" class="btn btn-success btn-sm btn-approve">Setujui</button>
                                    </form>
                                    <form action="{{ route('admin.persetujuan.reject', $item->id) }}" method="POST"
                                        class="d-inline form-reject">
                                        @csrf
                                        <button type="button" class="btn btn-danger btn-sm btn-reject">Tolak</button>
                                    </form>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada permintaan yang perlu disetujui.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).on('click', '.btn-approve', function(e) {
                e.preventDefault();
                let form = $(this).closest('form');
                Swal.fire({
                    title: 'Setujui Permintaan?',
                    text: "Anda yakin ingin menyetujui permintaan ini?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Setujui',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });

            $(document).on('click', '.btn-reject', function(e) {
                e.preventDefault();
                let form = $(this).closest('form');
                Swal.fire({
                    title: 'Tolak Permintaan?',
                    text: "Anda yakin ingin menolak permintaan ini?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Tolak',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        </script>
    @endpush
@endsection
