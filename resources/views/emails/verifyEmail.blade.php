<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .email-container {
            max-width: 600px;
            margin: 50px auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
        }

        .logo {
            margin-bottom: 30px;
        }

        .logo img {
            max-width: 150px;
        }

        h1 {
            color: #333333;
            font-size: 24px;
            margin-bottom: 20px;
        }

        p {
            color: #555555;
            font-size: 16px;
            line-height: 1.5;
            margin-bottom: 30px;
        }

        a.button {
            display: inline-block;
            background-color: #005689;
            color: #ffffff;
            padding: 15px 25px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            margin-top: 20px;
        }

        a.button:hover {
            background-color: #003f5c;
        }

        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #888888;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="logo">
            <img src="https://w7.pngwing.com/pngs/115/812/png-transparent-logo-kementerian-pendidikan-dan-kebudayaan-indonesia-others-logo-location-indonesia-thumbnail.png"
                alt="Logo" style="max-width: 100px;">
            <!-- Ganti URL dengan URL logo Anda -->
        </div>
        <h1>Verifikasi Email Anda</h1>
        <p>Terima kasih telah mendaftar! Untuk mulai menggunakan akun Anda, silakan klik tautan di bawah ini untuk
            memverifikasi email Anda:</p>
        <a href="{{ $verificationUrl }}" class="button">Verifikasi Email</a>
        <div class="footer">
            <p>Jika Anda tidak merasa mendaftar, Anda dapat mengabaikan email ini.</p>
            <p>Terima kasih, <br>Direktorat Guru PAUD dan Dikmas</p>
        </div>
    </div>
</body>

</html>
