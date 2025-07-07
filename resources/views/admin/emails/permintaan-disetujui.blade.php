<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Permintaan Penghapusan Data Disetujui</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f4f6f8;
            margin: 0;
            padding: 0;
        }

        .email-container {
            background: #fff;
            max-width: 480px;
            margin: 40px auto;
            border-radius: 10px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
            padding: 32px 28px 24px 28px;
        }

        .logo {
            text-align: center;
            margin-bottom: 24px;
        }

        .logo img {
            max-width: 120px;
            height: auto;
        }

        .title {
            font-size: 20px;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 18px;
            text-align: center;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }

        .info-table td {
            padding: 7px 0;
            vertical-align: top;
        }

        .info-label {
            color: #718096;
            width: 130px;
            font-weight: 500;
        }

        .info-value {
            color: #2d3748;
            font-weight: 500;
        }

        .footer {
            text-align: center;
            color: #a0aec0;
            font-size: 12px;
            margin-top: 24px;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="logo">
            <img src="{{ asset('logo kemendikbudristek.png') }}" alt="Logo Kemendikbudristek">
        </div>
        <div class="title">
            Permintaan Penghapusan Data/Hasil Tes <span style="color: green;">Disetujui</span>
        </div>
        <table class="info-table">
            <tr>
                <td class="info-label">User ID</td>
                <td class="info-value">{{ $allowance->user_id }}</td>
            </tr>
            <tr>
                <td class="info-label">Nama Peserta</td>
                <td class="info-value">{{ $allowance->name ?? '-' }}</td>
            </tr>
            <tr>
                <td class="info-label">Alasan</td>
                <td class="info-value">{{ $allowance->reason }}</td>
            </tr>
            <tr>
                <td class="info-label">Status</td>
                <td class="info-value">Disetujui</td>
            </tr>
        </table>
        <div style="text-align:center; margin-bottom: 18px;">
            <a href="http://127.0.0.1:8000/admin/login"
                style="display:inline-block; background:#2563eb; color:#fff; padding:10px 28px; border-radius:6px; text-decoration:none; font-weight:600; font-size:15px;">
                Login
            </a>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Direktorat Guru PAUD dan PNF. All rights reserved.
        </div>
    </div>
</body>

</html>
