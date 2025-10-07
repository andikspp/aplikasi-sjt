<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('assets/logo kemendikbudristek.png') }}" type="image/png" />
    <title>Login Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #005689 0%, #0077b6 100%);
            min-height: 100vh;
        }

        .card {
            border-radius: 22px;
            box-shadow: 0 4px 24px rgba(0, 86, 137, 0.15);
            border: none;
        }

        .card-header {
            background: linear-gradient(90deg, #005689 70%, #0077b6 100%);
            color: #fff;
            border-radius: 22px 22px 0 0;
            padding-bottom: 1.2rem;
        }

        .card-header img {
            max-width: 120px;
        }

        .card-header h3 {
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .card-header h4 {
            font-weight: 400;
            font-size: 1.1rem;
            margin-bottom: 0;
        }

        .form-label {
            font-weight: 600;
            color: #005689;
        }

        .btn-primary {
            background: linear-gradient(90deg, #005689 70%, #0077b6 100%);
            border: none;
            font-weight: 600;
            border-radius: 30px;
            font-size: 1.1rem;
            box-shadow: 0 2px 8px rgba(0, 86, 137, 0.10);
        }

        .btn-primary:hover {
            background: linear-gradient(90deg, #0077b6 70%, #005689 100%);
        }

        .input-group-text {
            background: #f8f9fa;
            border-radius: 0 8px 8px 0;
            cursor: pointer;
        }

        .input-group .form-control {
            border-radius: 8px 0 0 8px;
        }

        .alert {
            border-radius: 10px;
        }

        @media (max-width: 576px) {
            .card-header img {
                max-width: 80px;
            }

            .card-header h3 {
                font-size: 1.3rem;
            }

            .card-header h4 {
                font-size: 1rem;
            }
        }
    </style>
</head>

<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-5">
                <div class="card">
                    <div class="card-header text-center">
                        <img src="{{ asset('assets/logo kemendikbudristek.png') }}" alt="Logo"
                            class="img-fluid mb-3">
                        <h3>Login Admin</h3>
                        <h4>Pemaknaan Awal</h4>
                        <div class="mt-2" style="font-size: 1.05rem; font-weight: 500;">
                            Direktorat Guru PAUD dan PNF
                        </div>
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
                        <form action="{{ route('admin.login') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" required
                                    autofocus>
                                @error('username')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password" name="password" required>
                                    <span class="input-group-text" id="togglePassword">
                                        <i class="fa fa-eye" id="eyeIcon"></i>
                                    </span>
                                </div>
                                @error('password')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary w-100 mt-3">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Hide/Show password
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js"></script>
</body>

</html>
