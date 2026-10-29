<?php
include 'koneksi.php';
$msg = "";

if (isset($_POST['register'])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $nis = mysqli_real_escape_string($koneksi, $_POST['nis']);
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $kelas = mysqli_real_escape_string($koneksi, $_POST['kelas']);
    $jk = $_POST['jenis_kelamin'];

    $query_user = "INSERT INTO users (username, pasword, role) VALUES ('$username', '$password', 'siswa')";
    if (mysqli_query($koneksi, $query_user)) {
        $user_id = mysqli_insert_id($koneksi);
        $query_siswa = "INSERT INTO siswa (nis, nama, kelas, jenis_kelamin, user_id) 
                        VALUES ('$nis', '$nama', '$kelas', '$jk', '$user_id')";
        mysqli_query($koneksi, $query_siswa);

        $msg = "<div class='alert alert-success border-0 rounded-3 shadow-sm'><i class='fa-solid fa-circle-check me-2'></i>Registrasi berhasil! Silakan <a href='index.php' class='fw-bold text-success'>Login di sini</a></div>";
    } else {
        $msg = "<div class='alert alert-danger border-0 rounded-3 shadow-sm'><i class='fa-solid fa-circle-exclamation me-2'></i>Registrasi gagal! " . mysqli_error($koneksi) . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Siswa - SIPN</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #10b981 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px 15px;
        }

        .card-register {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 550px;
            overflow: hidden;
        }

        .register-header {
            background: linear-gradient(135deg, #059669, #10b981);
            color: white;
            padding: 25px;
            text-align: center;
        }

        .form-control,
        .form-select {
            border-radius: 10px;
            padding: 10px 15px;
            border: 1px solid #cbd5e1;
        }

        .btn-register {
            background: linear-gradient(135deg, #059669, #10b981);
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: 600;
        }

        .btn-register:hover {
            background: linear-gradient(135deg, #047857, #059669);
        }
    </style>
</head>

<body>
    <div class="card-register">
        <div class="register-header">
            <i class="fa-solid fa-user-plus fa-2x mb-2"></i>
            <h4 class="fw-bold mb-0">Pendaftaran Akun Siswa</h4>
            <small class="text-white-50">Sistem Informasi Pengelolaan Nilai</small>
        </div>
        <div class="p-4">
            <?= $msg ?>
            <form method="POST">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold text-secondary">Username</label>
                        <input type="text" name="username" class="form-control" required placeholder="Buat username">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold text-secondary">Password</label>
                        <input type="password" name="password" class="form-control" required
                            placeholder="Buat password">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold text-secondary">NIS</label>
                        <input type="text" name="nis" class="form-control" required placeholder="Nomor Induk Siswa">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold text-secondary">Kelas</label>
                        <input type="text" name="kelas" class="form-control" required placeholder="Contoh: XI RPL 1">
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-semibold text-secondary">Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control" required placeholder="Nama lengkap siswa">
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-semibold text-secondary">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="form-select" required>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                </div>
                <button type="submit" name="register" class="btn btn-primary btn-register w-100 mt-4 text-white">
                    <i class="fa-solid fa-paper-plane me-2"></i> Daftar Akun Baru
                </button>
                <div class="text-center mt-3">
                    <a href="index.php" class="text-decoration-none small text-secondary"><i
                            class="fa-solid fa-arrow-left me-1"></i> Kembali ke Halaman Login</a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>