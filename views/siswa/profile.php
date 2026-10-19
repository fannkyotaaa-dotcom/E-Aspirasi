<?php
session_start();

// 1. Proteksi Sesi
if (!isset($_SESSION['user_id']) || $_SESSION['level'] !== 'siswa') {
    header("Location: ../../login.php");
    exit;
}

require_once '../../config/Database.php';

$database = new Database();
$db = $database->getConnection();

// 2. Ambil data detail user
$query = "SELECT * FROM users WHERE id_user = :id_user";
$stmt = $db->prepare($query);
$stmt->bindParam(':id_user', $_SESSION['user_id']);
$stmt->execute();
$userData = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya | E-Sarpras SMK Sangkuriang 1</title>
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

        /* Sidebar Styling (Konsisten dengan Dashboard) */
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

        /* Profile Card */
        .profile-header {
            background: linear-gradient(45deg, var(--primary-navy), #004085);
            border-radius: 20px 20px 0 0;
            height: 120px;
        }
        .profile-avatar {
            width: 120px;
            height: 120px;
            background: white;
            border: 5px solid white;
            border-radius: 50%;
            margin-top: -60px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: var(--primary-navy);
        }
        .content-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            overflow: hidden;
        }
        
        .info-label { font-size: 0.8rem; color: #6c757d; font-weight: 600; text-transform: uppercase; }
        .info-value { font-size: 1rem; color: var(--primary-navy); font-weight: 500; }

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
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-3 col-lg-2 p-0 sidebar d-none d-md-block sticky-top" style="height: 100vh;">
            <div class="p-4 text-center">
                <i class="bi bi-building-fill-gear text-warning fs-1"></i>
                <h6 class="fw-bold mt-2 mb-0 text-white">E-SARPRAS</h6>
                <small class="text-white-50">PORTAL SISWA</small>
                <hr class="border-secondary mt-4">
            </div>
            <ul class="nav flex-column mt-2">
                <li class="nav-item">
                    <a href="dashboard.php" class="nav-link">
                        <i class="bi bi-grid-1x2-fill me-2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="profile.php" class="nav-link active">
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
            <div class="mb-4">
                <h3 class="fw-bold mb-0 text-dark">Pengaturan Profil</h3>
                <p class="text-muted small">Kelola informasi data diri Anda di sistem.</p>
            </div>

            <?php
            $daftar_pesan = [
                'success' => ['success', 'Kata sandi berhasil diperbarui!'],
                'kosong'  => ['danger',  'Semua kolom kata sandi wajib diisi.'],
                'pendek'  => ['danger',  'Kata sandi baru minimal 6 karakter.'],
                'beda'    => ['danger',  'Konfirmasi kata sandi baru tidak sama.'],
                'salah'   => ['danger',  'Kata sandi saat ini salah.'],
                'gagal'   => ['danger',  'Gagal menyimpan perubahan, silakan coba lagi.'],
            ];
            $kode_pesan = $_GET['msg'] ?? '';
            if (isset($daftar_pesan[$kode_pesan])):
            ?>
                <div class="alert alert-<?= $daftar_pesan[$kode_pesan][0]; ?> alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <?= $daftar_pesan[$kode_pesan][1]; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="row">
                <div class="col-lg-4 mb-4">
                    <div class="content-card shadow-sm text-center pb-4">
                        <div class="profile-header"></div>
                        <div class="d-flex justify-content-center">
                            <div class="profile-avatar">
                                <i class="bi bi-person-fill"></i>
                            </div>
                        </div>
                        <div class="mt-3 px-3">
                            <h5 class="fw-bold mb-0"><?= $userData['nama_lengkap']; ?></h5>
                            <span class="badge bg-warning text-dark rounded-pill mt-2">Siswa Aktif</span>
                        </div>
                        <hr class="mx-4 my-4 opacity-50">
                        <div class="px-4 text-start">
                            <div class="mb-3">
                                <label class="info-label">NIS</label>
                                <p class="info-value"><?= htmlspecialchars($userData['nis'] ?? '-'); ?></p>
                            </div>
                            <div class="mb-3">
                                <label class="info-label">Username</label>
                                <p class="info-value"><?= $userData['username']; ?></p>
                            </div>
                            <div class="mb-0">
                                <label class="info-label">Status Akun</label>
                                <p class="info-value text-success"><i class="bi bi-patch-check-fill me-1"></i> Terverifikasi</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">
                    <div class="content-card p-4 shadow-sm">
                        <h5 class="fw-bold mb-4"><i class="bi bi-shield-lock me-2 text-primary"></i>Keamanan Akun</h5>
                        
                        <form action="update_profile.php" method="POST">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label small fw-bold">Nama Lengkap</label>
                                    <input type="text" class="form-control rounded-3 bg-light" value="<?= $userData['nama_lengkap']; ?>" readonly>
                                    <!-- <small class="text-muted" style="font-size: 0.7rem;">*Hubungi admin untuk ubah nama</small> -->
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label small fw-bold">Username</label>
                                    <input type="text" class="form-control rounded-3 bg-light" value="<?= $userData['username']; ?>" readonly>
                                </div>
                            </div>

                            <hr class="my-4">
                            
                            <h6 class="fw-bold mb-3">Ganti Kata Sandi</h6>
                            <div class="mb-3">
                                <label class="form-label small">Kata Sandi Saat Ini</label>
                                <input type="password" name="current_password" class="form-control rounded-3" placeholder="Masukkan kata sandi saat ini" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small">Kata Sandi Baru</label>
                                <input type="password" name="new_password" class="form-control rounded-3" placeholder="Minimal 6 karakter" minlength="6" required>
                            </div>
                            <div class="mb-4">
                                <label class="form-label small">Konfirmasi Kata Sandi Baru</label>
                                <input type="password" name="confirm_password" class="form-control rounded-3" placeholder="Ulangi password baru" minlength="6" required>
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="submit" name="update" class="btn btn-navy px-5 py-2">
                                    Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>