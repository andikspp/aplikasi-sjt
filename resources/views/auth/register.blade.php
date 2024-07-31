<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #005689;
        }

        .card {
            border-radius: 20px;
        }

        #login {
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-center">
                        <img src="{{ asset('assets/logo kemendikbudristek.png') }}" alt="Logo"
                            class="img-fluid mb-3" style="max-width: 150px;">
                        <h3>Registrasi</h3>
                        <h4>Situational Judgement Test</h4>
                    </div>
                    <div class="card-body">
                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        <form action="{{ route('store.register') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" id="name" name="name" required
                                    autofocus>
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                                @error('password')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" id="password_confirmation"
                                    name="password_confirmation" required>
                            </div>
                            <div class="mb-3">
                                <label for="telepon" class="form-label">No. Telepon</label>
                                <input type="number" class="form-control" id="telepon" name="telepon" required
                                    autofocus>
                                @error('telepon')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="instansi" class="form-label">Instansi</label>
                                <input type="text" class="form-control" id="instansi" name="instansi" required
                                    autofocus>
                                @error('instansi')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Role</label><br>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="role_guru" name="role"
                                        value="Guru" {{ old('role') == 'Guru' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="role_guru">
                                        Guru
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="role_kepala_sekolah"
                                        name="role" value="Kepala Sekolah"
                                        {{ old('role') == 'Kepala Sekolah' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="role_kepala_sekolah">
                                        Kepala Sekolah
                                    </label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <a href="{{ route('login') }}" id="login">
                                    Masuk
                                </a>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 mt-3"
                                style="background-color: #005689">Registrasi</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
