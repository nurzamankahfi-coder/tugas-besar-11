<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: dashboard.php");
    exit;
}

// Tambah Siswa
if (isset($_POST['tambah_siswa'])) {
    $nis = mysqli_real_escape_string($koneksi, $_POST['nis']);
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $kelas = mysqli_real_escape_string($koneksi, $_POST['kelas']);
    $jk = $_POST['jenis_kelamin'];

    $query = "INSERT INTO siswa (nis, nama, kelas, jenis_kelamin) VALUES ('$nis', '$nama', '$kelas', '$jk')";
    mysqli_query($koneksi, $query);
    header("Location: kelola_siswa.php");
}

// Hapus Siswa
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($koneksi, "DELETE FROM siswa WHERE id = '$id'");
    header("Location: kelola_siswa.php");
}

$siswa = mysqli_query($koneksi, "SELECT * FROM siswa ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Kelola Siswa - SIPN</title>
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
            <h3 class="fw-bold mb-0"><i class="fa-solid fa-user-graduate text-primary me-2"></i> Kelola Siswa</h3>
            <a href="dashboard.php" class="btn btn-outline-secondary rounded-3"><i
                    class="fa-solid fa-arrow-left me-1"></i> Dashboard</a>
        </div>

        <!-- Form Tambah Siswa -->
        <div class="card border-0 rounded-4 shadow-sm mb-4">
            <div class="card-header bg-primary text-white py-3 fw-semibold rounded-top-4">
                <i class="fa-solid fa-plus-circle me-1"></i> Tambah Siswa Baru
            </div>
            <div class="card-body p-4">
                <form method="POST" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">NIS</label>
                        <input type="text" name="nis" class="form-control" required placeholder="NIS Siswa">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control" required placeholder="Nama lengkap">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">Kelas</label>
                        <input type="text" name="kelas" class="form-control" required placeholder="Contoh: XI RPL 1">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-semibold">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="form-select" required>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                    <div class="col-12 mt-3">
                        <button type="submit" name="tambah_siswa" class="btn btn-primary px-4 rounded-3"><i
                                class="fa-solid fa-floppy-disk me-1"></i> Simpan Data Siswa</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabel Siswa -->
        <div class="card border-0 rounded-4 shadow-sm overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="ps-3">No</th>
                            <th>NIS</th>
                            <th>Nama</th>
                            <th>Kelas</th>
                            <th>L/P</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1;
                        while ($row = mysqli_fetch_assoc($siswa)): ?>
                            <tr>
                                <td class="ps-3">
                                    <?= $no++ ?>
                                </td>
                                <td>
                                    <?= $row['nis'] ?>
                                </td>
                                <td class="fw-semibold">
                                    <?= $row['nama'] ?>
                                </td>
                                <td><span class="badge bg-secondary">
                                        <?= $row['kelas'] ?>
                                    </span></td>
                                <td>
                                    <?= $row['jenis_kelamin'] ?>
                                </td>
                                <td class="text-center">
                                    <a href="kelola_siswa.php?hapus=<?= $row['id'] ?>"
                                        class="btn btn-outline-danger btn-sm rounded-2"
                                        onclick="return confirm('Hapus data siswa ini?')"><i
                                            class="fa-solid fa-trash"></i></a>
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