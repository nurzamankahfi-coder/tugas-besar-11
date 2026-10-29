<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: dashboard.php");
    exit;
}

// Tambah Guru
if (isset($_POST['tambah_guru'])) {
    $nip = mysqli_real_escape_string($koneksi, $_POST['nip']);
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $jk = $_POST['jenis_kelamin'];

    $query = "INSERT INTO guru (nip, nama, jenis_kelamin) VALUES ('$nip', '$nama', '$jk')";
    mysqli_query($koneksi, $query);
    header("Location: kelola_guru.php");
}

// Hapus Guru
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($koneksi, "DELETE FROM guru WHERE id = '$id'");
    header("Location: kelola_guru.php");
}

$guru = mysqli_query($koneksi, "SELECT * FROM guru ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Kelola Guru - SIPN</title>
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
            <h3 class="fw-bold mb-0"><i class="fa-solid fa-chalkboard-user text-primary me-2"></i> Kelola Guru</h3>
            <a href="dashboard.php" class="btn btn-outline-secondary rounded-3"><i
                    class="fa-solid fa-arrow-left me-1"></i> Dashboard</a>
        </div>

        <!-- Form Tambah Guru -->
        <div class="card border-0 rounded-4 shadow-sm mb-4">
            <div class="card-header bg-primary text-white py-3 fw-semibold rounded-top-4">
                <i class="fa-solid fa-plus-circle me-1"></i> Tambah Guru Baru
            </div>
            <div class="card-body p-4">
                <form method="POST" class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">NIP</label>
                        <input type="text" name="nip" class="form-control" required placeholder="NIP Guru">
                    </div>
                    <div class="col-md-5">
                        <label class="form-label small fw-semibold">Nama Lengkap & Gelar</label>
                        <input type="text" name="nama" class="form-control" required placeholder="Nama lengkap">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="form-select" required>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                    <div class="col-12 mt-3">
                        <button type="submit" name="tambah_guru" class="btn btn-primary px-4 rounded-3"><i
                                class="fa-solid fa-floppy-disk me-1"></i> Simpan Data Guru</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabel Guru -->
        <div class="card border-0 rounded-4 shadow-sm overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="ps-3">No</th>
                            <th>NIP</th>
                            <th>Nama Guru</th>
                            <th>L/P</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1;
                        while ($row = mysqli_fetch_assoc($guru)): ?>
                            <tr>
                                <td class="ps-3">
                                    <?= $no++ ?>
                                </td>
                                <td>
                                    <?= $row['nip'] ?>
                                </td>
                                <td class="fw-semibold">
                                    <?= $row['nama'] ?>
                                </td>
                                <td>
                                    <?= $row['jenis_kelamin'] ?>
                                </td>
                                <td class="text-center">
                                    <a href="kelola_guru.php?hapus=<?= $row['id'] ?>"
                                        class="btn btn-outline-danger btn-sm rounded-2"
                                        onclick="return confirm('Hapus data guru ini?')"><i
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