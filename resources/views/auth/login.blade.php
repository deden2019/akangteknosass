<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>POS Koperasi RSPB - Login</title>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --primary: #03AC0E;        /* Hijau Tokopedia */
            --primary-hover: #028a0b;  /* Hijau Gelap Hover */
            --primary-light: #e8f7e9;  /* Hijau Soft Accent */
            --bg-color: #f7f9fa;
            --card-bg: #ffffff;
            --text-dark: #212121;
            --text-muted: #6c757d;
            --border-color: #e0e0e0;
            --danger-bg: #ffebee;
            --danger-text: #d32f2f;
            --danger-border: #ffcdd2;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            -webkit-tap-highlight-color: transparent;
        }

        body {
            background-color: var(--bg-color);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        /* Container Card Responsive */
        .login-card {
            background: var(--card-bg);
            width: 100%;
            max-width: 420px;
            padding: 40px 32px;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        /* Brand / Header */
        .brand-header {
            text-align: center;
            margin-bottom: 28px;
        }

        .brand-logo {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 64px;
            height: 64px;
            background-color: var(--primary-light);
            color: var(--primary);
            border-radius: 16px;
            font-size: 28px;
            margin-bottom: 16px;
        }

        .brand-header h2 {
            font-size: 22px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 6px;
            letter-spacing: -0.3px;
        }

        .brand-header p {
            font-size: 13px;
            color: var(--text-muted);
            line-height: 1.5;
        }

        /* Alert Session Error */
        .alert-error {
            background-color: var(--danger-bg);
            color: var(--danger-text);
            border: 1px solid var(--danger-border);
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 13px;
            margin-bottom: 20px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
            line-height: 1.4;
        }

        .alert-error i {
            font-size: 16px;
            margin-top: 1px;
        }

        /* Form Group & Inputs */
        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 8px;
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-wrapper i {
            position: absolute;
            left: 14px;
            color: #9e9e9e;
            font-size: 16px;
            transition: color 0.2s;
            pointer-events: none;
        }

        .form-control {
            width: 100%;
            padding: 14px 14px 14px 42px;
            font-size: 14px;
            color: var(--text-dark);
            background-color: #fff;
            border: 1px solid var(--border-color);
            border-radius: 10px;
            outline: none;
            transition: all 0.2s ease-in-out;
        }

        .form-control::placeholder {
            color: #b0b0b0;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(3, 172, 14, 0.15);
        }

        .form-control:focus + i {
            color: var(--primary);
        }

        /* Extra Options (Remember & Forgot) */
        .form-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
            font-size: 13px;
            gap: 10px;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            color: var(--text-muted);
            user-select: none;
        }

        .remember-me input[type="checkbox"] {
            accent-color: var(--primary);
            width: 18px;
            height: 18px;
            cursor: pointer;
            border-radius: 4px;
        }

        .forgot-link {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            white-space: nowrap;
        }

        .forgot-link:hover {
            text-decoration: underline;
        }

        /* Submit Button */
        .btn-submit {
            width: 100%;
            padding: 14px;
            background-color: var(--primary);
            color: #ffffff;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s ease-in-out, transform 0.1s;
            box-shadow: 0 4px 12px rgba(3, 172, 14, 0.25);
        }

        .btn-submit:hover {
            background-color: var(--primary-hover);
        }

        .btn-submit:active {
            transform: scale(0.98);
        }

        /* Footer */
        .login-footer {
            margin-top: 28px;
            text-align: center;
            font-size: 13px;
            color: var(--text-muted);
        }

        .login-footer a {
            color: var(--primary);
            font-weight: 600;
            text-decoration: none;
        }

        .login-footer a:hover {
            text-decoration: underline;
        }

        /* ===================================================
           MEDIA QUERIES (OPTIMASI KHUSUS TAMPILAN HP / MOBILE)
           =================================================== */
        @media (max-width: 480px) {
            body {
                padding: 16px;
                background-color: #ffffff; /* Mengubah background menjadi putih padat di mobile jika diinginkan */
                align-items: flex-start;
            }

            .login-card {
                padding: 24px 16px;
                box-shadow: none;
                border: none;
            }

            .brand-header {
                margin-bottom: 24px;
            }

            .brand-logo {
                width: 56px;
                height: 56px;
                font-size: 24px;
                border-radius: 14px;
                margin-bottom: 12px;
            }

            .brand-header h2 {
                font-size: 20px;
            }

            .brand-header p {
                font-size: 12px;
            }

            .form-control {
                font-size: 15px; /* Mencegah auto-zoom di browser Safari/iOS */
                padding: 13px 14px 13px 40px;
            }

            .btn-submit {
                padding: 15px;
                font-size: 16px;
            }
        }
    </style>
</head>
<body>

    <div class="login-card">
        <!-- Logo & Header -->
        <div class="brand-header">
            <div class="brand-logo">
                <i class="fa-solid fa-cart-shopping"></i>
            </div>
            <h2>akangtekno retail</h2>
            <p>Silakan masuk menggunakan akun Anda untuk memulai transaksi</p>
        </div>

        <!-- Session Alert Error -->
        @if(session('error'))
            <div class="alert-error">
                <i class="fa-solid fa-circle-exclamation"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Form Login -->
        <form action="/login" method="POST">
            @csrf

            <!-- Input Username -->
            <div class="form-group">
                <label for="username">Username</label>
                <div class="input-wrapper">
                    <input type="text" id="username" name="username" class="form-control" placeholder="Masukkan username" required autofocus autocomplete="username">
                    <i class="fa-regular fa-user"></i>
                </div>
            </div>

            <!-- Input Password -->
            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-wrapper">
                    <input type="password" id="password" name="password" class="form-control" placeholder="Masukkan password" required autocomplete="current-password">
                    <i class="fa-solid fa-lock"></i>
                </div>
            </div>

            <div class="form-group">
                <label>Shift</label>

                <select
                    name="shift_id"
                    class="form-control"
                    required>

                    @foreach($shift as $s)
                        <option value="{{ $s->shift_id }}">
                            {{ $s->nama_shift }}
                        </option>
                    @endforeach

                </select>
            </div>

            <div class="form-group">
                <label>Saldo Awal</label>

<input
    type="number"
    name="saldo_awal"
    min="1"
    class="form-control"
    placeholder="Masukkan saldo awal">
            </div>

            <!-- Options -->
            <!-- <div class="form-options">
                <label class="remember-me">
                    <input type="checkbox" name="remember"> Ingat Saya
                </label>
                <a href="#" class="forgot-link">Lupa Password?</a>
            </div> -->

            <!-- Submit Button -->
            <button type="submit" class="btn-submit">
                Masuk
            </button>
        </form>

        <!-- Footer -->
        <div class="login-footer">
            Butuh bantuan? <a href="#">Hubungi Admin</a>
        </div>
    </div>
@if($errors->any())
<script>
Swal.fire({
    icon: 'warning',
    title: 'Peringatan',
    text: '{{ $errors->first() }}'
});


</script>
@endif
<script>
    console.log(
        "%c © 2026 akangtekno. All rights reserved. %c \nInformasi Kontak: support@akangtekno.id | Lisensi Resmi Terdaftar.",
        "background: #1e1e3f; color: #38bdf8; padding: 6px 12px; font-weight: bold; border-radius: 4px 0 0 4px; font-size: 13px;",
        "background: #0f172a; color: #94a3b8; padding: 6px 12px; border-radius: 0 4px 4px 0; font-size: 12px;"
    );
</script>

</body>
</html>
