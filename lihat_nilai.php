<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'siswa') {
    header("Location: dashboard.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$siswa_query = mysqli_query($koneksi, "SELECT * FROM siswa WHERE user_id = '$user_id'");
$data_siswa = mysqli_fetch_assoc($siswa_query);
$siswa_id = $data_siswa['id'] ?? 0;

$nilai_query = mysqli_query($koneksi, "SELECT nr.*, m.nama_mapel 
                                       FROM nilai_rapor nr 
                                       JOIN mapel m ON nr.mapel_id = m.id 
                                       WHERE nr.siswa_id = '$siswa_id'");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Nilai Rapor Saya - SIPN</title>
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
            <h3 class="fw-bold mb-0"><i class="fa-solid fa-file-invoice text-primary me-2"></i> Transkrip Nilai Rapor
            </h3>
            <a href="dashboard.php" class="btn btn-outline-secondary rounded-3"><i
                    class="fa-solid fa-arrow-left me-1"></i> Dashboard</a>
        </div>

        <!-- Info Siswa -->
        <div class="card border-0 rounded-4 shadow-sm bg-white p-4 mb-4">
            <div class="row text-center text-md-start">
                <div class="col-md-4">
                    <small class="text-muted d-block">Nama Lengkap</small>
                    <h5 class="fw-bold text-primary mb-0">
                        <?= $data_siswa['nama'] ?? '-' ?>
                    </h5>
                </div>
                <div class="col-md-4">
                    <small class="text-muted d-block">NIS</small>
                    <h5 class="fw-bold text-dark mb-0">
                        <?= $data_siswa['nis'] ?? '-' ?>
                    </h5>
                </div>
                <div class="col-md-4">
                    <small class="text-muted d-block">Kelas</small>
                    <h5 class="fw-bold text-dark mb-0">
                        <?= $data_siswa['kelas'] ?? '-' ?>
                    </h5>
                </div>
            </div>
        </div>

        <!-- Tabel Nilai -->
        <div class="card border-0 rounded-4 shadow-sm overflow-hidden">
            <div class="table-responsive">
                <table class="table table-striped align-middle mb-0">
                    <thead class="table-primary">
                        <tr>
                            <th class="ps-3">No</th>
                            <th>Mata Pelajaran</th>
                            <th>KKM</th>
                            <th>Nilai Pengetahuan</th>
                            <th>Nilai Keterampilan</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1;
                        while ($row = mysqli_fetch_assoc($nilai_query)): ?>
                            <tr>
                                <td class="ps-3">
                                    <?= $no++ ?>
                                </td>
                                <td class="fw-semibold">
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
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>