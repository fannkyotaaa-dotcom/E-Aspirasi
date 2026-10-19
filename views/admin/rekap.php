<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['level'] !== 'admin') {
    header("Location: ../../login.php"); exit;
}

require_once '../../config/Database.php';
require_once '../../classes/Pengaduan.php';

$database = new Database();
$db = $database->getConnection();
$pengaduan = new Pengaduan($db);
$stmt = $pengaduan->tampilkanSemua();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Laporan | E-Sarpras SMK Sangkuriang 1</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --primary-navy: #002154; --accent-gold: #ffc107; --light-gray: #f4f7fe; }
        body { font-family: 'Poppins', sans-serif; background-color: var(--light-gray); }
        .sidebar { min-height: 100vh; background: var(--primary-navy); color: white; }
        .sidebar .nav-link { color: rgba(255,255,255,0.7); padding: 12px 20px; transition: 0.3s; }
        .sidebar .nav-link.active { color: var(--accent-gold); background: rgba(255,255,255,0.05); border-left: 4px solid var(--accent-gold); }
        .table-container { background: white; border-radius: 20px; padding: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        
        /* PRINT CSS */
        @media print {
            .sidebar, .btn, .no-print, .header-main { display: none !important; }
            .table-container { box-shadow: none; padding: 0; }
            .col-md-9 { width: 100% !important; }
            body { background-color: white !important; }
            .print-only { display: block !important; }
        }
        .print-only { display: none; }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-3 col-lg-2 p-0 sidebar d-none d-md-block sticky-top no-print" style="height: 100vh;">
            <div class="p-4 text-center">
                <i class="bi bi-building-fill-gear text-warning fs-1"></i>
                <h6 class="fw-bold mt-2 mb-0 text-white">E-SARPRAS</h6>
                <small class="text-white-50">SMK SANGKURIANG 1</small>
                <hr class="border-secondary mt-4">
            </div>
            <ul class="nav flex-column mt-2">
                <li class="nav-item"><a href="dashboard.php" class="nav-link"><i class="bi bi-grid-1x2-fill me-2"></i> Dashboard</a></li>
                <li class="nav-item"><a href="kategori.php" class="nav-link"><i class="bi bi-box-seam-fill me-2"></i> Data Kategori</a></li>
                <li class="nav-item"><a href="rekap.php" class="nav-link active"><i class="bi bi-file-earmark-bar-graph-fill me-2"></i> Rekap Laporan</a></li>
                <li class="nav-item mt-5"><a href="../../logout.php" class="nav-link text-danger"><i class="bi bi-power me-2"></i> Logout</a></li>
            </ul>
        </div>

        <div class="col-md-9 col-lg-10 p-md-5 p-4">
            <div class="print-only text-center mb-4">
                <h4>LAPORAN PENGADUAN SARANA PRASARANA</h4>
                <h5>SMK SANGKURIANG 1 CIMAHI</h5>
                <hr style="border: 2px solid black;">
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4 header-main">
                <div>
                    <h3 class="fw-bold mb-0">Rekapitulasi Laporan</h3>
                    <p class="text-muted">Unduh atau cetak riwayat pengaduan siswa.</p>
                </div>
                <button onclick="window.print()" class="btn btn-success rounded-pill px-4 shadow-sm">
                    <i class="bi bi-printer me-2"></i> Cetak Laporan
                </button>
            </div>

            <div class="table-container">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>Tanggal</th>
                                <th>Pelapor</th>
                                <th>Kategori</th>
                                <th>Lokasi</th>
                                <th>Laporan</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                            <tr>
                                <td><?= date('d/m/Y', strtotime($row['tanggal'])); ?></td>
                                <td><?= $row['nama_lengkap']; ?></td>
                                <td><?= htmlspecialchars($row['nama_kategori'] ?? '-', ENT_QUOTES, 'UTF-8', false); ?></td>
                                <td><?= htmlspecialchars($row['lokasi'] ?? '-', ENT_QUOTES, 'UTF-8', false); ?></td>
                                <td><?= htmlspecialchars($row['isi_aspirasi'], ENT_QUOTES, 'UTF-8', false); ?></td>
                                <td class="text-center fw-bold small"><?= strtoupper($row['status']); ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>