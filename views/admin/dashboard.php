<?php
session_start();

// 1. Proteksi Sesi
if (!isset($_SESSION['user_id']) || $_SESSION['level'] !== 'admin') {
    header("Location: ../../login.php");
    exit;
}

require_once '../../config/Database.php';
require_once '../../classes/Pengaduan.php';

$database = new Database();
$db = $database->getConnection();
$pengaduan = new Pengaduan($db);

// 2. Ambil Statistik & Notifikasi
$stats = $pengaduan->getStatistik();
$unreadCount = $pengaduan->countUnreadAdmin($_SESSION['last_seen_aspirasi'] ?? 0);

// 3. Ambil Daftar Pengaduan
$stmt = $pengaduan->tampilkanSemua();

// 4. Tandai sebagai terbaca (untuk sesi login ini)
if ($unreadCount > 0) {
    $_SESSION['last_seen_aspirasi'] = $pengaduan->getMaxId();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | E-Sarpras SMK Sangkuriang 1</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-navy: #002154;
            --accent-gold: #ffc107;
            --light-gray: #f4f7fe;
        }

        body { font-family: 'Poppins', sans-serif; background-color: var(--light-gray); }

        /* Sidebar Styling */
        .sidebar { 
            min-height: 100vh; 
            background: var(--primary-navy); 
            color: white; 
            box-shadow: 4px 0 10px rgba(0,0,0,0.1);
        }
        .sidebar .nav-link { 
            color: rgba(255,255,255,0.7); 
            font-weight: 500;
            padding: 12px 20px;
            transition: 0.3s;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { 
            color: var(--accent-gold); 
            background: rgba(255,255,255,0.05);
            border-left: 4px solid var(--accent-gold);
        }

        /* Card Statistics */
        .card-stat {
            border: none;
            border-radius: 15px;
            transition: transform 0.3s;
            overflow: hidden;
        }
        .card-stat:hover { transform: translateY(-5px); }
        .icon-shape {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
        }

        /* Table Styling */
        .table-container {
            background: white;
            border-radius: 20px;
            padding: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }
        .table thead th {
            background-color: #f8f9fa;
            color: var(--primary-navy);
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 1px;
            border: none;
        }
        .status-badge {
            font-size: 0.7rem;
            padding: 6px 12px;
            border-radius: 30px;
            font-weight: 700;
        }
        .img-preview {
            width: 45px;
            height: 45px;
            object-fit: cover;
            border-radius: 10px;
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-3 col-lg-2 p-0 sidebar d-none d-md-block sticky-top" style="height: 100vh;">
            <div class="p-4 text-center">
                <i class="bi bi-building-fill-gear text-warning fs-1"></i>
                <h6 class="fw-bold mt-2 mb-0">E-SARPRAS</h6>
                <small class="text-white-50">SMK SANGKURIANG 1</small>
                <hr class="border-secondary mt-4">
            </div>
            <ul class="nav flex-column mt-2">
                <li class="nav-item">
                    <a href="dashboard.php" class="nav-link active d-flex justify-content-between">
                        <span><i class="bi bi-grid-1x2-fill me-2"></i> Dashboard</span>
                        <?php if($unreadCount > 0): ?>
                            <span class="badge bg-danger rounded-pill"><?= $unreadCount ?></span>
                        <?php endif; ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="kategori.php" class="nav-link"><i class="bi bi-box-seam-fill me-2"></i> Data Kategori</a>
                </li>
                <li class="nav-item">
                    <a href="rekap.php" class="nav-link"><i class="bi bi-file-earmark-bar-graph-fill me-2"></i> Rekap Laporan</a>
                </li>
                <li class="nav-item mt-5">
                    <a href="../../logout.php" class="nav-link text-danger"><i class="bi bi-power me-2"></i> Logout</a>
                </li>
            </ul>
        </div>

        <div class="col-md-9 col-lg-10 p-md-5 p-4">
            <div class="d-flex justify-content-between align-items-center mb-5">
                <div>
                    <h3 class="fw-bold mb-0">Selamat Datang, Admin!</h3>
                    <p class="text-muted">Pantau dan kelola laporan fasilitas sekolah hari ini.</p>
                </div>
                <div class="d-flex align-items-center">
                    <div class="text-end me-3 d-none d-sm-block">
                        <p class="mb-0 fw-bold small"><?= $_SESSION['nama']; ?></p>
                        <p class="mb-0 text-muted small">Administrator</p>
                    </div>
                    <div class="bg-white p-2 rounded-circle shadow-sm">
                        <i class="bi bi-person-circle fs-3 text-primary"></i>
                    </div>
                </div>
            </div>

            <div class="row g-4 mb-5">
                <div class="col-xl-3 col-sm-6">
                    <div class="card card-stat shadow-sm">
                        <div class="card-body d-flex align-items-center">
                            <div class="icon-shape bg-primary bg-opacity-10 text-primary me-3">
                                <i class="bi bi-journal-text fs-4"></i>
                            </div>
                            <div>
                                <p class="text-muted small mb-1">Total Laporan</p>
                                <h4 class="fw-bold mb-0"><?= $stats['total']; ?></h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6">
                    <div class="card card-stat shadow-sm border-start border-warning border-5">
                        <div class="card-body d-flex align-items-center">
                            <div class="icon-shape bg-warning bg-opacity-10 text-warning me-3">
                                <i class="bi bi-clock-history fs-4"></i>
                            </div>
                            <div>
                                <p class="text-muted small mb-1">Menunggu</p>
                                <h4 class="fw-bold mb-0"><?= $stats['jml_pending']; ?></h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6">
                    <div class="card card-stat shadow-sm border-start border-info border-5">
                        <div class="card-body d-flex align-items-center">
                            <div class="icon-shape bg-info bg-opacity-10 text-info me-3">
                                <i class="bi bi-arrow-repeat fs-4"></i>
                            </div>
                            <div>
                                <p class="text-muted small mb-1">Diproses</p>
                                <h4 class="fw-bold mb-0"><?= $stats['jml_proses']; ?></h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6">
                    <div class="card card-stat shadow-sm border-start border-success border-5">
                        <div class="card-body d-flex align-items-center">
                            <div class="icon-shape bg-success bg-opacity-10 text-success me-3">
                                <i class="bi bi-check-circle fs-4"></i>
                            </div>
                            <div>
                                <p class="text-muted small mb-1">Selesai</p>
                                <h4 class="fw-bold mb-0"><?= $stats['jml_selesai']; ?></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-container shadow-sm">
                <div class="d-flex justify-content-between align-items-center mb-4 px-2">
                    <h5 class="fw-bold mb-0"><i class="bi bi-list-ul me-2 text-primary"></i>Daftar Pengaduan Masuk</h5>
                    <a href="rekap.php" class="btn btn-sm btn-outline-primary rounded-pill px-3">Lihat Semua</a>
                </div>
                <div class="table-responsive">
                    <?php if (isset($_GET['status']) && $_GET['status'] == 'deleted'): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle me-2"></i> Laporan pengaduan berhasil dihapus secara permanen.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th class="ps-3">Pelapor</th>
                                <th>Kategori</th>
                                <th>Laporan</th>
                                <th>Foto</th>
                                <th>Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                            <tr>
                                <td class="ps-3">
                                    <span class="fw-bold d-block"><?= $row['nama_lengkap']; ?></span>
                                    <small class="text-muted"><?= date('d M Y', strtotime($row['tanggal'])); ?></small>
                                </td>
                                <td><span class="badge bg-light text-primary border"><?= htmlspecialchars($row['nama_kategori'] ?? 'Tanpa Kategori', ENT_QUOTES, 'UTF-8', false); ?></span></td>
                                <td style="max-width: 200px;">
                                    <p class="mb-0 small fw-bold text-truncate"><i class="bi bi-geo-alt-fill text-danger me-1"></i><?= htmlspecialchars($row['lokasi'] ?? '-', ENT_QUOTES, 'UTF-8', false); ?></p>
                                    <p class="mb-0 small text-muted text-truncate"><?= htmlspecialchars($row['isi_aspirasi'], ENT_QUOTES, 'UTF-8', false); ?></p>
                                </td>
                                <td>
                                    <?php if (!empty($row['foto_bukti'])): ?>
                                    <a href="../../public/uploads/<?= rawurlencode($row['foto_bukti']); ?>" target="_blank">
                                        <img src="../../public/uploads/<?= rawurlencode($row['foto_bukti']); ?>" class="img-preview shadow-sm border">
                                    </a>
                                    <?php else: ?>
                                        <span class="text-muted small">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php 
                                        $c = "bg-secondary";
                                        if($row['status'] == 'pending') $c = "bg-warning text-dark";
                                        if($row['status'] == 'proses') $c = "bg-info text-white";
                                        if($row['status'] == 'selesai') $c = "bg-success text-white";
                                    ?>
                                    
                                    <span class="status-badge <?= $c; ?>"><?= strtoupper($row['status']); ?></span>
                                </td>
                                <td class="text-center">
                                    <div class="dropdown">
                                        <button class="btn btn-light btn-sm rounded-circle" data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                            <li><a class="dropdown-item small" href="update_status.php?id=<?= $row['id_aspirasi']; ?>&s=proses"><i class="bi bi-play-circle me-2 text-info"></i>Proses</a></li>
                                            <li><a class="dropdown-item small" href="update_status.php?id=<?= $row['id_aspirasi']; ?>&s=selesai"><i class="bi bi-check-circle me-2 text-success"></i>Selesai</a></li>
                                            <li><a class="dropdown-item small" href="hapus_pengaduan.php?id=<?= $row['id_aspirasi']; ?>&s=hapus"><i class="bi bi-cross-circle me-2 text-success"></i>Hapus</a></li>
                                        </ul>
                                        <!-- <a href="hapus_pengaduan.php?id=<?= $row['id_aspirasi']; ?>" 
                                        class="btn btn-sm btn-outline-danger rounded-pill"
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus laporan ini? Tindakan ini tidak dapat dibatalkan.')">
                                            <i class="bi bi-trash"></i>
                                        </a> -->
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if($unreadCount > 0): ?>
<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div class="toast show border-0 shadow-lg rounded-4" role="alert">
        <div class="toast-header bg-warning text-dark rounded-top-4 border-0">
            <i class="bi bi-bell-fill me-2"></i>
            <strong class="me-auto">Laporan Baru!</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
        </div>
        <div class="toast-body">
            Ada <b><?= $unreadCount ?></b> pengaduan fasilitas baru yang perlu Anda tinjau segera.
        </div>
    </div>
</div>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>