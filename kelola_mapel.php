<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: dashboard.php");
    exit;
}

// Tambah Mapel
if (isset($_POST['tambah_mapel'])) {
    $kode = mysqli_real_escape_string($koneksi, $_POST['kode_mapel']);
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama_mapel']);
    $guru_id = !empty($_POST['guru_id']) ? $_POST['guru_id'] : "NULL";

    $query = "INSERT INTO mapel (kode_mapel, nama_mapel, guru_id) VALUES ('$kode', '$nama', $guru_id)";
    mysqli_query($koneksi, $query);
    header("Location: kelola_mapel.php");
}

// Hapus Mapel
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($koneksi, "DELETE FROM mapel WHERE id = '$id'");
    header("Location: kelola_mapel.php");
}

$mapel = mysqli_query($koneksi, "SELECT m.*, g.nama as nama_guru FROM mapel m LEFT JOIN guru g ON m.guru_id = g.id ORDER BY m.id DESC");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Kelola Mapel - SIPN</title>
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
            <h3 class="fw-bold mb-0"><i class="fa-solid fa-book text-primary me-2"></i> Kelola Mata Pelajaran</h3>
            <a href="dashboard.php" class="btn btn-outline-secondary rounded-3"><i
                    class="fa-solid fa-arrow-left me-1"></i> Dashboard</a>
        </div>

        <!-- Form Tambah Mapel -->
        <div class="card border-0 rounded-4 shadow-sm mb-4">
            <div class="card-header bg-primary text-white py-3 fw-semibold rounded-top-4">
                <i class="fa-solid fa-plus-circle me-1"></i> Tambah Mata Pelajaran Baru
            </div>
            <div class="card-body p-4">
                <form method="POST" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">Kode Mapel</label>
                        <input type="text" name="kode_mapel" class="form-control" required placeholder="Contoh: MP001">
                    </div>
                    <div class="col-md-5">
                        <label class="form-label small fw-semibold">Nama Mata Pelajaran</label>
                        <input type="text" name="nama_mapel" class="form-control" required
                            placeholder="Nama mata pelajaran">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Guru Pengampu</label>
                        <select name="guru_id" class="form-select">
                            <option value="">-- Pilih Guru (Opsional) --</option>
                            <?php
                            $gurus = mysqli_query($koneksi, "SELECT * FROM guru");
                            while ($g = mysqli_fetch_assoc($gurus)) {
                                echo "<option value='" . $g['id'] . "'>" . $g['nama'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-12 mt-3">
                        <button type="submit" name="tambah_mapel" class="btn btn-primary px-4 rounded-3"><i
                                class="fa-solid fa-floppy-disk me-1"></i> Simpan Mapel</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabel Mapel -->
        <div class="card border-0 rounded-4 shadow-sm overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="ps-3">No</th>
                            <th>Kode</th>
                            <th>Nama Mata Pelajaran</th>
                            <th>Guru Pengampu</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1;
                        while ($row = mysqli_fetch_assoc($mapel)): ?>
                            <tr>
                                <td class="ps-3">
                                    <?= $no++ ?>
                                </td>
                                <td><span class="badge bg-secondary">
                                        <?= $row['kode_mapel'] ?>
                                    </span></td>
                                <td class="fw-semibold">
                                    <?= $row['nama_mapel'] ?>
                                </td>
                                <td>
                                    <?= $row['nama_guru'] ?? '<span class="text-muted fs-7">Belum diatur</span>' ?>
                                </td>
                                <td class="text-center">
                                    <a href="kelola_mapel.php?hapus=<?= $row['id'] ?>"
                                        class="btn btn-outline-danger btn-sm rounded-2"
                                        onclick="return confirm('Hapus mapel ini?')"><i class="fa-solid fa-trash"></i></a>
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