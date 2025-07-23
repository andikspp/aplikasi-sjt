<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.3/dist/sweetalert2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .navbar {
            background-color: #005689;
        }

        .navbar-custom .navbar-nav .nav-link {
            color: white;
        }

        .navbar-custom .navbar-brand {
            color: white;
        }

        .navbar-text {
            margin-left: auto;
            color: white;
            font-size: 1.5rem;
            text-decoration: none;
        }

        .navbar-text:hover {
            text-decoration: underline;
        }

        /* .navbar-nav .nav-item:hover {
            transform: scale(1.05);
        } */

        .navbar-nav .nav-link.active {
            text-decoration: underline;
            text-decoration-color: white;
        }

        html,
        body {
            height: 100%;
            margin: 0;
            display: flex;
            flex-direction: column;
        }

        main {
            flex: 1;
        }

        footer {
            background-color: #005689;
            color: white;
            text-align: center;
            padding: 1rem 0;
        }
    </style>
</head>

<body>
    @php
        $adminCount = \App\Models\Admin::count();
    @endphp
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
        <div class="container-fluid">
            <!-- Flex kiri: Logo + Teks -->
            <div class="d-flex align-items-center">
                <a class="navbar-brand d-flex align-items-center" href="{{ route('admin.dashboard') }}">
                    <img src="{{ asset('assets/logo kemendikbudristek.png') }}" alt="Logo"
                        style="max-width: 100px;">
                    <span class="navbar-text ms-2" style="font-size: 1.2rem;">
                        Direktorat Guru PAUD dan PNF
                    </span>
                </a>
            </div>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <div class="collapse navbar-collapse" id="navbarNav">
                    <!-- Nav kanan -->
                    <ul class="navbar-nav ms-auto align-items-center">
                        <li class="nav-item">
                            <a class="nav-link {{ Request::routeIs('admin.dashboard') ? 'active' : '' }}"
                                href="{{ route('admin.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::routeIs('admin.soal') ? 'active' : '' }}"
                                href="{{ route('admin.soal') }}">Paket Soal</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::routeIs('hasil') ? 'active' : '' }}"
                                href="{{ route('hasil') }}">Hasil</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::routeIs('data.peserta') ? 'active' : '' }}"
                                href="{{ route('data.peserta') }}">Data Peserta</a>
                        </li>
                        <!-- Dropdown User -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#"
                                id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="position-relative">
                                    <i class="bi bi-person-circle" style="font-size: 1.5rem;"></i>
                                    @if (!empty($pendingPersetujuanCount) && $pendingPersetujuanCount > 0)
                                        <span
                                            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                                            style="font-size:0.7rem;">
                                            {{ $pendingPersetujuanCount }}
                                        </span>
                                    @endif
                                </span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                @if ($adminCount > 1)
                                    <li>
                                        <a class="dropdown-item {{ Request::routeIs('admin.permintaan') ? 'active' : '' }}"
                                            href="{{ route('admin.permintaan') }}">
                                            Permintaan Akses
                                        </a>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <a class="dropdown-item d-flex justify-content-between align-items-center {{ Request::routeIs('admin.persetujuan') ? 'active' : '' }}"
                                            href="{{ route('admin.persetujuan') }}">
                                            <span>Persetujuan Akses</span>
                                            @if (!empty($pendingPersetujuanCount) && $pendingPersetujuanCount > 0)
                                                <span
                                                    class="badge bg-danger ms-2">{{ $pendingPersetujuanCount }}</span>
                                            @endif
                                        </a>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                @endif
                                <li>
                                    <a class="dropdown-item {{ Request::routeIs('admin.log') ? 'active' : '' }}"
                                        href="{{ route('admin.log') }}">
                                        Riwayat Aktivitas
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <a class="dropdown-item text-danger" href="#"
                                        id="logout-btn-dropdown">Logout</a>
                                    <form id="logout-form" action="{{ route('admin.logout') }}" method="POST"
                                        style="display: none;">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>


    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="text-center text-lg-start mt-5">
        <div class="text-center p-3">
            &copy; 2025 Direktorat Guru PAUD dan PNF, Kementerian Pendidikan dan Kebudayaan Republik Indonesia
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.3/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        document.getElementById('logout-btn-dropdown').addEventListener('click', function(event) {
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
    </script>
    @stack('scripts')
</body>

</html>
