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

$unreadCount = $pengaduan->countUnreadAdmin($_SESSION['last_seen_aspirasi'] ?? 0);

// Ambil Data Kategori
$query = "SELECT * FROM kategori ORDER BY id_kategori DESC";
$stmt = $db->prepare($query);
$stmt->execute();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Kategori | E-Sarpras SMK Sangkuriang 1</title>
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
        .sidebar { min-height: 100vh; background: var(--primary-navy); color: white; box-shadow: 4px 0 10px rgba(0,0,0,0.1); }
        .sidebar .nav-link { color: rgba(255,255,255,0.7); padding: 12px 20px; transition: 0.3s; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: var(--accent-gold); background: rgba(255,255,255,0.05); border-left: 4px solid var(--accent-gold); }
        .table-container { background: white; border-radius: 20px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .btn-navy { background-color: var(--primary-navy); color: white; border-radius: 10px; }
        .btn-navy:hover { background-color: #00163a; color: var(--accent-gold); }
        .action-btn { width: 35px; height: 35px; display: inline-flex; align-items: center; justify-content: center; border-radius: 10px; transition: 0.2s; border: none; }
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
                    <a href="dashboard.php" class="nav-link d-flex justify-content-between">
                        <span><i class="bi bi-grid-1x2-fill me-2"></i> Dashboard</span>
                        <?php if($unreadCount > 0): ?>
                            <span class="badge bg-danger rounded-pill"><?= $unreadCount ?></span>
                        <?php endif; ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="kategori.php" class="nav-link active"><i class="bi bi-box-seam-fill me-2"></i> Data Kategori</a>
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
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold mb-0 text-dark">Manajemen Kategori</h3>
                    <p class="text-muted">Kelola kategori pengaduan (contoh: Fasilitas, Pembelajaran).</p>
                </div>
                <button class="btn btn-navy px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
                    <i class="bi bi-plus-circle me-2"></i> Tambah Kategori
                </button>
            </div>

            <div class="table-container">
                <div class="table-responsive">
                    <?php if (isset($_GET['status'])): ?>
    <?php if ($_GET['status'] == 'success_edit'): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i> Data kategori berhasil diperbarui!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php elseif ($_GET['status'] == 'success_tambah'): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i> Kategori baru berhasil ditambahkan!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php elseif ($_GET['status'] == 'success_hapus'): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i> Kategori berhasil dihapus. Laporan yang memakai kategori ini kini tampil tanpa kategori.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php elseif ($_GET['status'] == 'error'): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i> Terjadi kesalahan, data kategori tidak berubah.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
<?php endif; ?>
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr class="text-muted small">
                                <th class="ps-3">NAMA KATEGORI</th>
                                <th class="text-center">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                            <tr>
                                <td class="ps-3">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-light p-2 rounded-3 me-3 text-primary">
                                            <i class="bi bi-tools"></i>
                                        </div>
                                        <span class="fw-bold"><?= $row['nama_kategori']; ?></span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <button type="button" 
                                            class="action-btn bg-info bg-opacity-10 text-info me-1" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#modalEdit"
                                            data-id="<?= $row['id_kategori']; ?>"
                                            data-nama="<?= htmlspecialchars($row['nama_kategori'], ENT_QUOTES, 'UTF-8'); ?>"
                                            title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>

                                    <a href="hapus_kategori.php?id=<?= $row['id_kategori']; ?>" 
                                       class="action-btn bg-danger bg-opacity-10 text-danger" 
                                       onclick="return confirm('Hapus kategori ini?')" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </a>
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

<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold"><i class="bi bi-plus-circle me-2 text-primary"></i>Tambah Kategori Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="proses_kategori.php" method="POST">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nama Kategori</label>
                        <input type="text" name="nama_kategori" class="form-control rounded-3" placeholder="Contoh: Fasilitas" required>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="tambah" class="btn btn-navy rounded-pill px-4">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEdit" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold"><i class="bi bi-pencil-square me-2 text-info"></i>Edit Data Kategori</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="proses_kategori.php" method="POST">
                <div class="modal-body p-4">
                    <input type="hidden" name="id_kategori" id="edit-id">
                    
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nama Kategori</label>
                        <input type="text" name="nama_kategori" id="edit-nama" class="form-control rounded-3" required>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="edit" class="btn btn-info text-white rounded-pill px-4">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    const modalEdit = document.getElementById('modalEdit');
    modalEdit.addEventListener('show.bs.modal', function (event) {
        // Tombol yang memicu modal
        const button = event.relatedTarget;
        
        // Ekstrak info dari atribut data-*
        const id = button.getAttribute('data-id');
        const nama = button.getAttribute('data-nama');

        // Update isi input modal
        modalEdit.querySelector('#edit-id').value = id;
        modalEdit.querySelector('#edit-nama').value = nama;
    });
</script>

</body>
</html>