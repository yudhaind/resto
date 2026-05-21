<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <!-- Meta tag wajib agar tampilan pas di layar HP -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Login Modern</title>
    <style>
        /* Reset & Base Styling */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: #f4f6f9;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        /* Container Utama (Responsif) */
        .login-container {
            background: #ffffff;
            width: 100%;
            max-width: 400px; /* Batas maksimal lebar di PC */
            padding: 40px 30px;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        /* Header */
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .login-header h1 {
            font-size: 24px;
            color: #333333;
            margin-bottom: 8px;
        }

        .login-header p {
            font-size: 14px;
            color: #777777;
        }

        /* Form Group */
        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            color: #555555;
            margin-bottom: 8px;
            font-weight: 600;
        }

        /* Input Field (Ramah Sentuhan HP) */
        .form-group input {
            width: 100%;
            padding: 12px 16px; /* Padding longgar agar mudah diklik di HP */
            font-size: 16px; /* Minimal 16px agar iOS tidak auto-zoom saat diketik */
            border: 1px solid #cccccc;
            border-radius: 8px;
            outline: none;
            transition: border-color 0.2s;
        }

        .form-group input:focus {
            border-color: #4a90e2;
            box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.1);
        }

        /* Fitur Tambahan (Remember Me & Forgot Password) */
        .form-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 14px;
            margin-bottom: 25px;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
        }

        .forgot-pass {
            color: #4a90e2;
            text-decoration: none;
        }

        .forgot-pass:hover {
            text-decoration: underline;
        }

        /* Tombol Login */
        .btn-login {
            width: 100%;
            padding: 14px;
            background-color: #4a90e2;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-login:hover {
            background-color: #357abd;
        }

        /* Footer / Registrasi */
        .login-footer {
            text-align: center;
            margin-top: 25px;
            font-size: 14px;
            color: #777777;
        }

        .login-footer a {
            color: #4a90e2;
            text-decoration: none;
            font-weight: 600;
        }

        /* Media Query Khusus Layar Sangat Kecil (HP Jadul/Kecil) */
        @media (max-width: 360px) {
            .login-container {
                padding: 30px 20px;
            }
            .login-header h1 {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>

    <div class="login-container">
        <div class="login-header">
            <h1>Selamat Datang</h1>
            <p>Silakan masuk ke akun Anda</p>
        </div>

        <form action="#" method="POST">
            <div class="form-group">
                <label for="email">Email atau Username</label>
                <input type="email" id="email" name="email" placeholder="nama@email.com" required autocomplete="username">
            </div>

            <div class="form-group">
                <label for="password">Kata Sandi</label>
                <input type="password" id="password" name="password" placeholder="••••••••" required autocomplete="current-password">
            </div>

            <div class="form-actions">
                <label class="remember-me">
                    <input type="checkbox" name="remember"> Ingat Saya
                </label>
                <a href="#" class="forgot-pass">Lupa Sandi?</a>
            </div>

            <button type="submit" class="btn-login">Masuk</button>
        </form>

        <div class="login-footer">
            Belum punya akun? <a href="#">Daftar sekarang</a>
        </div>
    </div>

</body>
</html>