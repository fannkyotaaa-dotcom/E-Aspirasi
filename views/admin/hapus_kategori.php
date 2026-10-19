<?php
session_start();

// 1. Proteksi Admin
if (!isset($_SESSION['user_id']) || $_SESSION['level'] !== 'admin') {
    header("Location: ../../login.php");
    exit;
}

require_once '../../config/Database.php';
require_once '../../classes/Kategori.php';

$id = $_GET['id'] ?? '';

if ($id === '' || !ctype_digit((string)$id)) {
    header("Location: kategori.php?status=error");
    exit;
}

$database = new Database();
$db = $database->getConnection();
$kategoriObj = new Kategori($db);

// 2. Pastikan kategori ada sebelum dihapus
if (!$kategoriObj->readOne($id)) {
    header("Location: kategori.php?status=error");
    exit;
}

// 3. Hapus. Laporan yang memakai kategori ini tidak ikut terhapus:
//    kolom id_kategori di tabel aspirasi otomatis menjadi kosong (ON DELETE SET NULL)
if ($kategoriObj->delete($id)) {
    header("Location: kategori.php?status=success_hapus");
} else {
    header("Location: kategori.php?status=error");
}
exit;