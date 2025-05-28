@extends('layout.admin.admin-layout')

@section('title', 'Daftar Permintaan')

@section('content')
    <div class="container mt-4">
        <h3 class="mb-4 text-center">Permintaan Akses</h3>
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-primary">
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Peserta</th>
                        <th class="text-center">Aksi</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Tanggal Permintaan</th>
                        <th class="text-center">Persetujuan Admin</th>
                        <th class="text-center">Pembatalan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($allowances as $item)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td class="text-center">{{ $item->peserta->name ?? '-' }}</td>
                            <td class="text-center">{{ $item->reason }}</td>
                            <td class="text-center">
                                @if ($item->status == 'pending')
                                    <span class="badge bg-warning">Menunggu</span>
                                @elseif($item->status == 'approved')
                                    <span class="badge bg-success">Disetujui</span>
                                @else
                                    <span class="badge bg-danger">Ditolak</span>
                                @endif
                            </td>
                            <td class="text-center">{{ \Carbon\Carbon::parse($item->created_at)->format('d-m-Y H:i') }}</td>
                            <td class="text-center">
                                @php
                                    // Ambil semua admin kecuali pengaju
                                    $allAdmins = \App\Models\Admin::where('id', '!=', $item->requested_by)->get();
                                    // Buat array approval: [admin_id => status]
                                    $approvalMap = $item->approvals->pluck('status', 'admin_id')->toArray();
                                @endphp
                                @foreach ($allAdmins as $admin)
                                    @php
                                        $status = $approvalMap[$admin->id] ?? 'pending';
                                    @endphp
                                    @if ($status === 'approved')
                                        <span title="{{ $admin->username }}"><i
                                                class="bi bi-check-circle-fill text-success"></i></span>
                                    @elseif($status === 'rejected')
                                        <span title="{{ $admin->username }}"><i
                                                class="bi bi-x-circle-fill text-danger"></i></span>
                                    @else
                                        <span title="{{ $admin->username }}"><i
                                                class="bi bi-circle text-secondary"></i></span>
                                    @endif
                                @endforeach
                            </td>
                            <td class="text-center">
                                @if ($item->status == 'pending')
                                    <form action="{{ route('admin.permintaan.cancel', $item->id) }}" method="POST"
                                        class="form-cancel-allowance d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                            class="btn btn-danger btn-sm btn-cancel-allowance">Batalkan</button>
                                    </form>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Belum ada permintaan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).on('click', '.btn-cancel-allowance', function(e) {
                e.preventDefault();
                let form = $(this).closest('form');
                Swal.fire({
                    title: 'Batalkan Permintaan?',
                    text: "Permintaan penghapusan akan dibatalkan.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, batalkan!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });

            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: '{{ session('success') }}',
                    timer: 2000,
                    showConfirmButton: false
                });
            @endif
        </script>
    @endpush
@endsection
