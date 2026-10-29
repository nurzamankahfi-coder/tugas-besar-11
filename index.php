<?php
session_start();
include 'koneksi.php';

if (isset($_SESSION['login'])) {
    header("Location: dashboard.php");
    exit;
}

$error = "";

if (isset($_POST['login'])) {
    // Bersihkan spasi di awal/akhir input
    $username = trim(mysqli_real_escape_string($koneksi, $_POST['username']));
    $password = trim($_POST['password']);

    // Cari user berdasarkan username
    $result = mysqli_query($koneksi, "SELECT * FROM users WHERE LOWER(username) = LOWER('$username')");

    if ($result && mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);

        // Memeriksa nama kolom password di database (pasword atau password)
        $db_password = isset($row['pasword']) ? $row['pasword'] : (isset($row['password']) ? $row['password'] : '');

        $is_valid = false;

        // Cek 1: Pencocokan langsung (plain text)
        if ($password === $db_password) {
            $is_valid = true;
        }
        // Cek 2: Pencocokan password terenkripsi (hash)
        elseif (password_verify($password, $db_password)) {
            $is_valid = true;
        }

        if ($is_valid) {
            $_SESSION['login'] = true;
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['role'] = $row['role'];

            header("Location: dashboard.php");
            exit;
        }
    }
    $error = "Username atau password yang Anda masukkan salah!";
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPN - SMK Sangkuriang 1 Cimahi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #3b82f6 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            width: 100%;
            max-width: 420px;
        }

        .login-header {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            padding: 30px 20px;
            text-align: center;
            color: white;
        }

        .login-header .icon-box {
            width: 65px;
            height: 65px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-size: 28px;
        }

        .form-control {
            border-radius: 10px;
            padding: 12px 15px 12px 45px;
            border: 1px solid #cbd5e1;
            font-size: 14px;
        }

        .input-group-text {
            background: transparent;
            border: none;
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            z-index: 10;
            color: #64748b;
        }

        .toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            z-index: 10;
            cursor: pointer;
            color: #64748b;
        }

        .btn-login {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: 600;
        }
    </style>
</head>

<body>

    <div class="login-card">
        <div class="login-header">
            <div class="icon-box">
                <i class="fa-solid fa-graduation-cap"></i>
            </div>
            <h4 class="fw-bold mb-1">SIPN LOGIN</h4>
            <p class="mb-0 text-white-50">SMK Sangkuriang 1 Cimahi</p>
        </div>

        <div class="card-body p-4">
            <?php if ($error): ?>
                <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center rounded-3 mb-4"
                    role="alert">
                    <i class="fa-solid fa-triangle-exclamation me-2"></i>
                    <div class="small">
                        <?= $error ?>
                    </div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3 position-relative">
                    <label class="form-label fw-medium text-secondary small">Username</label>
                    <div class="position-relative">
                        <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                        <input type="text" name="username" class="form-control" placeholder="Masukkan username" required
                            autocomplete="off">
                    </div>
                </div>

                <div class="mb-4 position-relative">
                    <label class="form-label fw-medium text-secondary small">Password</label>
                    <div class="position-relative">
                        <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                        <input type="password" name="password" id="passwordInput" class="form-control"
                            placeholder="Masukkan password" required>
                        <i class="fa-solid fa-eye toggle-password" id="togglePassword"></i>
                    </div>
                </div>

                <button type="submit" name="login" class="btn btn-primary btn-login w-100 mb-3">
                    <i class="fa-solid fa-right-to-bracket me-2"></i> Masuk Sekarang
                </button>
            </form>

            <div class="text-center mt-3 pt-3 border-top">
                <span class="text-muted small">Belum punya akun?</span>
                <a href="register.php" class="text-primary fw-bold small ms-1 text-decoration-none">Daftar Akun
                    Siswa</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const passwordInput = document.querySelector('#passwordInput');

        togglePassword.addEventListener('click', function () {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    </script>

</body>

</html>