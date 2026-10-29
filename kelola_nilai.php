<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'guru') {
    header("Location: dashboard.php");
    exit;
}

if (isset($_POST['simpan_nilai'])) {
    $siswa_id = $_POST['siswa_id'];
    $mapel_id = $_POST['mapel_id'];
    $kkm = $_POST['nilai_kkm'];
    $pengetahuan = $_POST['nilai_pengetahuan'];
    $keterampilan = $_POST['nilai_keterampilan'];

    $rata = ($pengetahuan + $keterampilan) / 2;
    $ket = ($rata >= $kkm) ? "Tuntas" : "Belum Tuntas";

    $query = "INSERT INTO nilai_rapor (siswa_id, mapel_id, nilai_kkm, nilai_pengetahuan, nilai_keterampilan, keterangan) 
              VALUES ('$siswa_id', '$mapel_id', '$kkm', '$pengetahuan', '$keterampilan', '$ket')";
    mysqli_query($koneksi, $query);
    header("Location: kelola_nilai.php");
}

if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($koneksi, "DELETE FROM nilai_rapor WHERE id = '$id'");
    header("Location: kelola_nilai.php");
}

$nilai_data = mysqli_query($koneksi, "SELECT nr.*, s.nama as nama_siswa, s.kelas, m.nama_mapel 
                                      FROM nilai_rapor nr 
                                      JOIN siswa s ON nr.siswa_id = s.id 
                                      JOIN mapel m ON nr.mapel_id = m.id");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Kelola Nilai - SIPN</title>
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
            <h3 class="fw-bold mb-0"><i class="fa-solid fa-pen-to-square text-success me-2"></i> Kelola Nilai Siswa</h3>
            <a href="dashboard.php" class="btn btn-outline-secondary rounded-3"><i
                    class="fa-solid fa-arrow-left me-1"></i> Dashboard</a>
        </div>

        <!-- Form Input Nilai -->
        <div class="card border-0 rounded-4 shadow-sm mb-4">
            <div class="card-header bg-success text-white py-3 fw-semibold rounded-top-4">
                <i class="fa-solid fa-plus-circle me-1"></i> Form Input / Tambah Nilai
            </div>
            <div class="card-body p-4">
                <form method="POST" class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Pilih Siswa</label>
                        <select name="siswa_id" class="form-select" required>
                            <?php
                            $siswa = mysqli_query($koneksi, "SELECT * FROM siswa");
                            while ($s = mysqli_fetch_assoc($siswa)) {
                                echo "<option value='" . $s['id'] . "'>" . $s['nama'] . " (" . $s['kelas'] . ")</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Mata Pelajaran</label>
                        <select name="mapel_id" class="form-select" required>
                            <?php
                            $mapel = mysqli_query($koneksi, "SELECT * FROM mapel");
                            while ($m = mysqli_fetch_assoc($mapel)) {
                                echo "<option value='" . $m['id'] . "'>" . $m['nama_mapel'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Nilai KKM</label>
                        <input type="number" name="nilai_kkm" class="form-control" value="75" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Nilai Pengetahuan</label>
                        <input type="number" name="nilai_pengetahuan" class="form-control" placeholder="0-100" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Nilai Keterampilan</label>
                        <input type="number" name="nilai_keterampilan" class="form-control" placeholder="0-100"
                            required>
                    </div>
                    <div class="col-12 mt-4">
                        <button type="submit" name="simpan_nilai" class="btn btn-success px-4 py-2 rounded-3"><i
                                class="fa-solid fa-floppy-disk me-1"></i> Simpan Data Nilai</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabel Data Nilai -->
        <div class="card border-0 rounded-4 shadow-sm overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="ps-3">No</th>
                            <th>Siswa</th>
                            <th>Mata Pelajaran</th>
                            <th>KKM</th>
                            <th>Pengetahuan</th>
                            <th>Keterampilan</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1;
                        while ($row = mysqli_fetch_assoc($nilai_data)): ?>
                            <tr>
                                <td class="ps-3">
                                    <?= $no++ ?>
                                </td>
                                <td class="fw-semibold">
                                    <?= $row['nama_siswa'] ?> <small class="text-muted d-block">
                                        <?= $row['kelas'] ?>
                                    </small>
                                </td>
                                <td>
                                    <?= $row['nama_mapel'] ?>
                                </td>
                                <td>
                                    <?= $row['nilai_kkm'] ?>
                                </td>
                                <td><span class="badge bg-info text-dark">
                                        <?= $row['nilai_pengetahuan'] ?>
                                    </span></td>
                                <td><span class="badge bg-warning text-dark">
                                        <?= $row['nilai_keterampilan'] ?>
                                    </span></td>
                                <td>
                                    <span
                                        class="badge rounded-pill <?= $row['keterangan'] === 'Tuntas' ? 'bg-success' : 'bg-danger' ?> px-3">
                                        <?= $row['keterangan'] ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <a href="kelola_nilai.php?hapus=<?= $row['id'] ?>"
                                        class="btn btn-outline-danger btn-sm rounded-2"
                                        onclick="return confirm('Hapus data nilai ini?')"><i
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