<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['login'])) {
    header("Location: index.php");
    exit;
}

$role = $_SESSION['role'];
$username = $_SESSION['username'];

// Hitung Statistik
$total_siswa = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM siswa"))['total'];
$total_guru = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM guru"))['total'];
$total_mapel = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM mapel"))['total'];
$total_nilai = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM nilai_rapor"))['total'];
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - SIPN SMK Sangkuriang 1</title>
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

        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
            color: white;
        }

        .nav-link {
            color: #94a3b8;
            border-radius: 10px;
            padding: 12px 20px;
            margin-bottom: 5px;
            transition: all 0.3s;
        }

        .nav-link:hover,
        .nav-link.active {
            color: white;
            background: rgba(255, 255, 255, 0.1);
        }

        .stat-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .bg-gradient-blue {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        }

        .bg-gradient-green {
            background: linear-gradient(135deg, #10b981, #047857);
        }

        .bg-gradient-purple {
            background: linear-gradient(135deg, #8b5cf6, #6d28d9);
        }

        .bg-gradient-orange {
            background: linear-gradient(135deg, #f59e0b, #d97706);
        }
    </style>
</head>

<body>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar Navigation -->
            <div class="col-md-3 col-lg-2 sidebar p-3 d-none d-md-block">
                <div class="d-flex align-items-center mb-4 px-2">
                    <i class="fa-solid fa-graduation-cap fa-2x text-primary me-2"></i>
                    <h5 class="fw-bold mb-0">SIPN Portal</h5>
                </div>
                <hr class="text-secondary">
                <ul class="nav nav-pills flex-column mb-auto">
                    <li class="nav-item">
                        <a href="dashboard.php" class="nav-link active"><i class="fa-solid fa-chart-pie me-2"></i>
                            Dashboard</a>
                    </li>
                    <?php if ($role === 'admin'): ?>
                        <li><a href="kelola_user.php" class="nav-link"><i class="fa-solid fa-users-gear me-2"></i> Kelola
                                User</a></li>
                        <li><a href="kelola_siswa.php" class="nav-link"><i class="fa-solid fa-user-graduate me-2"></i>
                                Kelola Siswa</a></li>
                        <li><a href="kelola_guru.php" class="nav-link"><i class="fa-solid fa-chalkboard-user me-2"></i>
                                Kelola Guru</a></li>
                        <li><a href="kelola_mapel.php" class="nav-link"><i class="fa-solid fa-book me-2"></i> Kelola
                                Mapel</a></li>
                    <?php elseif ($role === 'guru'): ?>
                        <li><a href="kelola_nilai.php" class="nav-link"><i class="fa-solid fa-pen-to-square me-2"></i>
                                Kelola Nilai</a></li>
                    <?php elseif ($role === 'siswa'): ?>
                        <li><a href="lihat_nilai.php" class="nav-link"><i class="fa-solid fa-file-invoice me-2"></i> Nilai
                                Rapor</a></li>
                    <?php endif; ?>
                    <li class="mt-4"><a href="logout.php" class="nav-link text-danger"><i
                                class="fa-solid fa-right-from-bracket me-2"></i> Logout</a></li>
                </ul>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 ms-sm-auto px-md-4 py-4">
                <!-- Top Header Bar -->
                <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                    <div>
                        <h3 class="fw-bold mb-0">Selamat Datang,
                            <?= htmlspecialchars($username) ?>!
                        </h3>
                        <span class="badge bg-primary text-uppercase px-3 py-2 mt-1">Role:
                            <?= $role ?>
                        </span>
                    </div>
                    <div class="d-flex align-items-center">
                        <img src="https://ui-avatars.com/api/?name=<?= urlencode($username) ?>&background=0D8ABC&color=fff"
                            class="rounded-circle shadow-sm me-2" width="45" alt="Avatar">
                    </div>
                </div>

                <!-- Ringkasan Kartu Statistik -->
                <div class="row g-4 mb-4">
                    <div class="col-md-3">
                        <div class="card stat-card bg-gradient-blue text-white p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="text-white-50">Total Siswa</small>
                                    <h2 class="fw-bold mb-0">
                                        <?= $total_siswa ?>
                                    </h2>
                                </div>
                                <i class="fa-solid fa-user-graduate fa-2x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stat-card bg-gradient-green text-white p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="text-white-50">Total Guru</small>
                                    <h2 class="fw-bold mb-0">
                                        <?= $total_guru ?>
                                    </h2>
                                </div>
                                <i class="fa-solid fa-chalkboard-user fa-2x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stat-card bg-gradient-purple text-white p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="text-white-50">Mata Pelajaran</small>
                                    <h2 class="fw-bold mb-0">
                                        <?= $total_mapel ?>
                                    </h2>
                                </div>
                                <i class="fa-solid fa-book-open fa-2x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stat-card bg-gradient-orange text-white p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="text-white-50">Entry Nilai</small>
                                    <h2 class="fw-bold mb-0">
                                        <?= $total_nilai ?>
                                    </h2>
                                </div>
                                <i class="fa-solid fa-award fa-2x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Banner Informasi -->
                <div class="card border-0 rounded-4 shadow-sm bg-white p-4">
                    <h5 class="fw-bold text-dark"><i class="fa-solid fa-bullhorn text-warning me-2"></i> Informasi
                        Sistem</h5>
                    <p class="text-muted mb-0">Sistem Informasi Pengelolaan Nilai (SIPN) SMK Sangkuriang 1 Cimahi
                        digunakan untuk mencatat, mengelola, serta menyajikan laporan nilai akademik siswa secara akurat
                        dan terstruktur.</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>