@extends('layout.user.user-layout')

@section('title', 'Profile')

@section('content')
    <style>
        .card-header {
            background-color: #005689;
            color: #fff;
            font-weight: bold;
        }

        .card-body {
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            border-radius: 8px;
        }

        .info-item {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .info-item i {
            color: #005689;
            margin-right: 10px;
        }

        .btn-custom {
            background-color: #005689;
            color: white;
        }

        .btn-custom:hover {
            background-color: #012a41;
            color: white;
        }
    </style>

    <!-- Main Content -->
    <div class="container mt-5">
        <!-- resources/views/dashboard.blade.php -->
        {{-- @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif --}}

        <div class="row">
            <div class="col-md-12">
                <h3>Selamat Datang, {{ $user->name }}</h3>

                <!-- Another Content Section -->
                <div class="card mt-4">
                    <div class="card-header">
                        Informasi Pribadi
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 text-center mb-3">
                                <img src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : asset('assets/default-profile.png') }}"
                                    alt="Profile Picture" class="img-fluid rounded-circle"
                                    style="max-width: 150px; max-height: 150px;">
                                <form action="#" method="POST" enctype="multipart/form-data" class="mt-2">
                                    @csrf
                                    <div class="mb-2">
                                        <input type="file" name="profile_picture" id="profile_picture"
                                            class="form-control form-control-sm">
                                    </div>
                                    <button type="submit" class="btn btn-sm btn-custom">Update Foto</button>
                                </form>
                            </div>
                            <div class="col-md-9">
                                <div class="info-item">
                                    <i class="fas fa-user"></i>
                                    <p>Nama: {{ $user->name }} ({{ ucwords($user->role) }})</p>
                                </div>
                                <div class="info-item">
                                    <i class="fas fa-envelope"></i>
                                    <p>Email: {{ $user->email }}</p>
                                </div>
                                <div class="info-item">
                                    <i class="fas fa-phone"></i>
                                    <p>Nomor Telepon: {{ $user->telepon }}</p>
                                </div>
                                <div class="info-item">
                                    <i class="fas fa-home"></i>
                                    <p>Instansi: {{ strtoupper($user->instansi) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('logout-btn').addEventListener('click', function(event) {
            event.preventDefault();

            Swal.fire({
                title: 'Anda yakin ingin logout?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, logout!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logout-form').submit();
                }
            });
        });

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
                title: 'Berhasil',
                text: '{{ session('success') }}',
            });
        @endif
    </script>
@endsection

</body>

</html>
