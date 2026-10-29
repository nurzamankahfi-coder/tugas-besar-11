<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: dashboard.php");
    exit;
}

// Tambah User Baru
if (isset($_POST['tambah_user'])) {
    $username = mysqli_real_escape_string($koneksi, trim($_POST['username']));
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $query = "INSERT INTO users (username, pasword, role) VALUES ('$username', '$password', '$role')";
    mysqli_query($koneksi, $query);
    header("Location: kelola_users.php");
}

// Hapus User
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($koneksi, "DELETE FROM users WHERE id = '$id'");
    header("Location: kelola_users.php");
}

$users = mysqli_query($koneksi, "SELECT * FROM users ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Users - SIPN</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: #f8fafc;
        }
    </style>
</head>

<body class="p-4">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold mb-0"><i class="fa-solid fa-users-gear text-primary me-2"></i> Kelola Users</h3>
            <a href="dashboard.php" class="btn btn-outline-secondary rounded-3"><i
                    class="fa-solid fa-arrow-left me-1"></i> Dashboard</a>
        </div>

        <!-- Form Tambah User -->
        <div class="card border-0 rounded-4 shadow-sm mb-4">
            <div class="card-header bg-primary text-white py-3 fw-semibold rounded-top-4">
                <i class="fa-solid fa-user-plus me-1"></i> Tambah User Baru
            </div>
            <div class="card-body p-4">
                <form method="POST" class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Username</label>
                        <input type="text" name="username" class="form-control" required
                            placeholder="Masukkan username">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Password</label>
                        <input type="password" name="password" class="form-control" required
                            placeholder="Masukkan password">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Role</label>
                        <select name="role" class="form-select" required>
                            <option value="admin">Admin</option>
                            <option value="guru">Guru</option>
                            <option value="siswa">Siswa</option>
                        </select>
                    </div>
                    <div class="col-12 mt-3">
                        <button type="submit" name="tambah_user" class="btn btn-primary px-4 rounded-3"><i
                                class="fa-solid fa-floppy-disk me-1"></i> Simpan User</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabel Data User -->
        <div class="card border-0 rounded-4 shadow-sm overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="ps-3">No</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Tanggal Dibuat</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1;
                        while ($row = mysqli_fetch_assoc($users)): ?>
                            <tr>
                                <td class="ps-3">
                                    <?= $no++ ?>
                                </td>
                                <td class="fw-semibold">
                                    <?= $row['username'] ?>
                                </td>
                                <td><span class="badge bg-info text-dark text-uppercase">
                                        <?= $row['role'] ?>
                                    </span></td>
                                <td>
                                    <?= $row['created_at'] ?>
                                </td>
                                <td class="text-center">
                                    <a href="kelola_users.php?hapus=<?= $row['id'] ?>"
                                        class="btn btn-outline-danger btn-sm rounded-2"
                                        onclick="return confirm('Hapus user ini?')"><i class="fa-solid fa-trash"></i></a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>