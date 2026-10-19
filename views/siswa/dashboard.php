<?php
session_start();
// Proteksi halaman: Jika belum login atau bukan siswa, tendang ke login.php
if (!isset($_SESSION['user_id']) || $_SESSION['level'] !== 'siswa') {
    header("Location: ../../login.php");
    exit;
}

require_once '../../config/Database.php';
require_once '../../classes/Pengaduan.php';

$database = new Database();
$db = $database->getConnection();
$pengaduan = new Pengaduan($db);

$pesan = "";

// Ambil data kategori untuk dropdown select
$queryKategori = "SELECT * FROM kategori ORDER BY nama_kategori ASC";
$stmtKategori = $db->prepare($queryKategori);
$stmtKategori->execute();

// Logika ketika tombol "Kirim Laporan" ditekan
if ($_POST) {
    $pengaduan->nis = $_SESSION['nis'];
    $pengaduan->id_kategori = $_POST['id_kategori'];
    $pengaduan->lokasi = $_POST['lokasi'];
    $pengaduan->isi_aspirasi = $_POST['isi'];
    $pengaduan->tanggal = date('Y-m-d H:i:s');

    // Proses Upload Foto
    $target_dir = "../../public/uploads/";
    
    // Buat folder jika belum ada
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $nama_file = time() . "_" . basename($_FILES["foto"]["name"]);
    $target_file = $target_dir . $nama_file;
    
    if(move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
        $pengaduan->foto_bukti = $nama_file;
        if($pengaduan->buatLaporan()) {
            $pesan = "<div class='alert alert-success border-0 shadow-sm'><i class='bi bi-check-circle me-2'></i>Laporan berhasil terkirim!</div>";
        } else {
            $pesan = "<div class='alert alert-danger border-0 shadow-sm'>Gagal menyimpan data ke database.</div>";
        }
    } else {
        $pesan = "<div class='alert alert-danger border-0 shadow-sm'>Gagal upload foto. Pastikan folder uploads tersedia.</div>";
    }
}

// Ambil Riwayat Pengaduan Siswa Ini (setelah proses simpan, supaya laporan baru langsung tampil)
$stmtRiwayat = $pengaduan->tampilkanByUser($_SESSION['nis']);

// Ambil Statistik Khusus Siswa Ini untuk mempermanis tampilan
$stats = $pengaduan->getStatistikByUser($_SESSION['nis']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Siswa Dashboard | E-Sarpras SMK Sangkuriang 1</title>
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

        .sidebar { 
            min-height: 100vh; 
            background: var(--primary-navy); 
            color: white; 
            box-shadow: 4px 0 10px rgba(0,0,0,0.1);
        }
        .sidebar .nav-link { 
            color: rgba(255,255,255,0.7); 
            padding: 12px 20px;
            transition: 0.3s;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { 
            color: var(--accent-gold); 
            background: rgba(255,255,255,0.05);
            border-left: 4px solid var(--accent-gold);
        }

        .content-card {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            border: none;
        }

        .btn-navy {
            background-color: var(--primary-navy);
            color: white;
            border-radius: 12px;
            font-weight: 600;
        }
        .btn-navy:hover {
            background-color: #00163a;
            color: var(--accent-gold);
        }
        
        .form-control, .form-select {
            border-radius: 10px;
            padding: 12px;
            border: 1px solid #e0e0e0;
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
                <small class="text-white-50">PORTAL SISWA</small>
                <hr class="border-secondary mt-4">
            </div>
            <ul class="nav flex-column mt-2">
                <li class="nav-item">
                    <a href="dashboard.php" class="nav-link active">
                        <i class="bi bi-grid-1x2-fill me-2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="profile.php" class="nav-link">
                        <i class="bi bi-person-bounding-box me-2"></i> Profil Saya
                    </a>
                </li>
                <li class="nav-item mt-5">
                    <a href="../../logout.php" class="nav-link text-danger">
                        <i class="bi bi-power me-2"></i> Logout
                    </a>
                </li>
            </ul>
        </div>

        <div class="col-md-9 col-lg-10 p-md-5 p-4">
            <div class="d-flex justify-content-between align-items-center mb-5">
                <div>
                    <h3 class="fw-bold mb-0 text-dark">Halo, <?= explode(' ', $_SESSION['nama'])[0]; ?>! 👋</h3>
                    <p class="text-muted small">Silakan laporkan kendala fasilitas sekolah di sini.</p>
                </div>
                <div class="text-muted d-none d-md-block">
                    <i class="bi bi-calendar3 me-2"></i> <?= date('d M Y'); ?>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-lg-7">
                    <div class="content-card">
                        <div class="d-flex align-items-center mb-4">
                            <div class="bg-primary bg-opacity-10 p-2 rounded-3 me-3">
                                <i class="bi bi-megaphone-fill text-primary"></i>
                            </div>
                            <h5 class="fw-bold mb-0">Buat Laporan Pengaduan</h5>
                        </div>
                        
                        <?= $pesan; ?>

                        <form action="" method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Kategori Laporan</label>
                                <select name="id_kategori" class="form-select" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    <?php while ($row = $stmtKategori->fetch(PDO::FETCH_ASSOC)): ?>
                                        <option value="<?= $row['id_kategori']; ?>"><?= $row['nama_kategori']; ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Lokasi</label>
                                <input type="text" name="lokasi" class="form-control" placeholder="Contoh: Kelas XII RPL 1 / Lab Komputer 1" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Detail Laporan</label>
                                <textarea name="isi" class="form-control" rows="4" placeholder="Jelaskan detail kerusakannya..." required></textarea>
                            </div>
                            <div class="mb-4">
                                <label class="form-label small fw-bold">Foto Bukti</label>
                                <input type="file" name="foto" class="form-control" accept="image/*" required>
                                <small class="text-muted">Gunakan foto asli untuk mempercepat proses pengecekan.</small>
                            </div>
                            <button type="submit" class="btn btn-navy w-100 py-3 shadow-sm">
                                <i class="bi bi-send-fill me-2"></i> Kirim Pengaduan Sekarang
                            </button>
                        </form>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="content-card mb-4 bg-primary text-white">
                        <h5 class="fw-bold mb-3"><i class="bi bi-info-circle me-2"></i>Tips Melapor</h5>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-3 d-flex align-items-start small">
                                <i class="bi bi-1-circle me-2 text-warning"></i>
                                <span>Pastikan foto bukti terlihat jelas dan fokus pada area yang rusak.</span>
                            </li>
                            <li class="mb-3 d-flex align-items-start small">
                                <i class="bi bi-2-circle me-2 text-warning"></i>
                                <span>Tulis lokasi dengan jelas (contoh: Kelas XII RPL 1, Lab Komputer 1).</span>
                            </li>
                            <li class="mb-0 d-flex align-items-start small">
                                <i class="bi bi-3-circle me-2 text-warning"></i>
                                <span>Admin akan memvalidasi laporan Anda maksimal dalam 1x24 jam kerja.</span>
                            </li>
                        </ul>
                    </div>
                    
                    <div class="row mt-5">
                        <div class="col-12">
                            <div class="content-card shadow-sm">
                                <div class="d-flex align-items-center mb-4">
                                    <div class="bg-primary bg-opacity-10 p-2 rounded-3 me-3">
                                        <i class="bi bi-clock-history text-primary"></i>
                                    </div>
                                    <h5 class="fw-bold mb-0">Riwayat Laporan Saya</h5>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead class="table-light">
                                            <tr class="small text-muted">
                                                <th class="ps-3">TANGGAL</th>
                                                <th>FOTO</th>
                                                <th>KATEGORI</th>
                                                <th>LAPORAN</th>
                                                <th>STATUS</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if ($stmtRiwayat->rowCount() > 0): ?>
                                                <?php while ($row = $stmtRiwayat->fetch(PDO::FETCH_ASSOC)): ?>
                                                <tr>
                                                    <td class="ps-3 small"><?= date('d M Y', strtotime($row['tanggal'])); ?></td>
                                                    <td>
                                                        <?php if (!empty($row['foto_bukti'])): ?>
                                                        <a href="../../public/uploads/<?= rawurlencode($row['foto_bukti']); ?>" target="_blank">
                                                            <img src="../../public/uploads/<?= rawurlencode($row['foto_bukti']); ?>" class="rounded shadow-sm" style="width: 50px; height: 50px; object-fit: cover;" alt="Bukti">
                                                        </a>
                                                        <?php else: ?>
                                                            <span class="text-muted small">-</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><span class="fw-bold text-navy"><?= htmlspecialchars($row['nama_kategori'] ?? '-', ENT_QUOTES, 'UTF-8', false); ?></span></td>
                                                    <td>
                                                        <p class="mb-0 small fw-bold"><i class="bi bi-geo-alt-fill text-danger me-1"></i><?= htmlspecialchars($row['lokasi'] ?? '-', ENT_QUOTES, 'UTF-8', false); ?></p>
                                                        <p class="mb-0 small text-muted text-truncate" style="max-width: 250px;"><?= htmlspecialchars($row['isi_aspirasi'], ENT_QUOTES, 'UTF-8', false); ?></p>
                                                    </td>
                                                    <td>
                                                        <?php 
                                                            $badge = "bg-secondary";
                                                            if($row['status'] == 'pending') $badge = "bg-warning text-dark";
                                                            if($row['status'] == 'proses') $badge = "bg-primary text-white";
                                                            if($row['status'] == 'selesai') $badge = "bg-success text-white";
                                                        ?>
                                                        <span class="badge <?= $badge; ?> rounded-pill px-3 py-2" style="font-size: 0.7rem;">
                                                            <?= strtoupper($row['status']); ?>
                                                        </span>
                                                    </td>
                                                </tr>
                                                <?php endwhile; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="5" class="text-center py-5 text-muted">
                                                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                                        Belum ada riwayat laporan.
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>