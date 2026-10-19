<?php
session_start();

// 1. Proteksi Admin
if (!isset($_SESSION['user_id']) || $_SESSION['level'] !== 'admin') {
    header("Location: ../../login.php");
    exit;
}

require_once '../../config/Database.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $database = new Database();
    $db = $database->getConnection();

    // 2. Ambil nama file foto sebelum data dihapus (untuk dihapus dari folder)
    $queryGetFoto = "SELECT foto_bukti FROM aspirasi WHERE id_aspirasi = :id";
    $stmtFoto = $db->prepare($queryGetFoto);
    $stmtFoto->bindParam(':id', $id);
    $stmtFoto->execute();
    $data = $stmtFoto->fetch(PDO::FETCH_ASSOC);

    if ($data) {
        $pathFoto = "../../public/uploads/" . basename($data['foto_bukti'] ?? '');
        
        // Hapus file fisik jika ada
        if (!empty($data['foto_bukti']) && is_file($pathFoto)) {
            unlink($pathFoto);
        }

        // 3. Hapus data dari database
        $queryDelete = "DELETE FROM aspirasi WHERE id_aspirasi = :id";
        $stmtDelete = $db->prepare($queryDelete);
        $stmtDelete->bindParam(':id', $id);

        if ($stmtDelete->execute()) {
            header("Location: dashboard.php?status=deleted");
        } else {
            header("Location: dashboard.php?status=error");
        }
    } else {
        header("Location: dashboard.php");
    }
} else {
    header("Location: dashboard.php");
}
exit;