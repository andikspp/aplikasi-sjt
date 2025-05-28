@extends('layout.admin.admin-layout')

@section('title', 'Log Aktivitas Admin')

@section('content')
    <div class="container mt-4">
        <h3 class="mb-4 text-center">Riwayat Aktivitas</h3>
        <div class="row mb-3">
            <div class="col-12">
                <div class="d-flex justify-content-end gap-2">
                    <select id="adminFilter" class="form-select w-auto">
                        <option value="">Semua Admin</option>
                        @foreach ($admins as $admin)
                            <option value="{{ $admin->id }}">{{ $admin->username }}</option>
                        @endforeach
                    </select>
                    <select id="timeFilter" class="form-select w-auto">
                        <option value="">Semua Waktu</option>
                        <option value="today">Hari Ini</option>
                        <option value="3days">3 Hari Terakhir</option>
                        <option value="1week">1 Minggu Terakhir</option>
                        <option value="2weeks">2 Minggu Terakhir</option>
                        <option value="3weeks">3 Minggu Terakhir</option>
                        <option value="1month">1 Bulan Terakhir</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-primary">
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Admin</th>
                        <th class="text-center">Aksi</th>
                        <th class="text-center" id="sortWaktu" style="cursor:pointer;">
                            Waktu
                            <span id="arrowWaktu">▼</span>
                        </th>
                    </tr>
                </thead>
                <tbody id="logTableBody">
                    @forelse($logs as $log)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td class="text-center">{{ $log->admin_name ?? '-' }}</td>
                            <td class="text-center">{{ $log->action }}</td>
                            <td class="text-center">{{ \Carbon\Carbon::parse($log->created_at)->format('d-m-Y H:i:s') }} WIB
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">Belum ada aktivitas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        var waktuOrder = 'desc';

        function loadLogs() {
            var adminId = $('#adminFilter').val();
            var timeRange = $('#timeFilter').val();
            $.ajax({
                url: '{{ route('admin.logs.filter') }}',
                type: 'GET',
                data: {
                    admin_id: adminId,
                    time_range: timeRange,
                    waktu_order: waktuOrder
                },
                success: function(response) {
                    var html = '';
                    if (response.success && response.logs.length > 0) {
                        response.logs.forEach(function(log) {
                            html += '<tr>' +
                                '<td class="text-center">' + log.no + '</td>' +
                                '<td class="text-center">' + log.admin_name + '</td>' +
                                '<td class="text-center">' + log.action + '</td>' +
                                '<td class="text-center">' + log.waktu + '</td>' +
                                '</tr>';
                        });
                    } else {
                        html = '<tr><td colspan="4" class="text-center">Belum ada aktivitas.</td></tr>';
                    }
                    $('#logTableBody').html(html);
                },
                error: function(xhr) {
                    $('#logTableBody').html(
                        '<tr><td colspan="4" class="text-center text-danger">Gagal memuat data log.</td></tr>'
                    );
                }
            });
        }

        $('#adminFilter, #timeFilter').on('change', function() {
            loadLogs();
        });

        $('#sortWaktu').on('click', function() {
            waktuOrder = (waktuOrder === 'desc') ? 'asc' : 'desc';
            $('#arrowWaktu').text(waktuOrder === 'desc' ? '▼' : '▲');
            loadLogs();
        });
    </script>
@endpush
