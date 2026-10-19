<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['level'] !== 'admin') {
    header("Location: ../../login.php");
    exit;
}

require_once '../../config/Database.php';
require_once '../../classes/Pengaduan.php';

$database = new Database();
$db = $database->getConnection();
$pengaduan = new Pengaduan($db);

// Menangkap data dari URL (GET)
$id = isset($_GET['id']) ? $_GET['id'] : die('Error: ID tidak ditemukan.');
$status = isset($_GET['s']) ? $_GET['s'] : die('Error: Status tidak ditemukan.');

// Eksekusi update menggunakan method yang sudah diperbaiki tadi
if($pengaduan->updateStatus($id, $status)) {
    header("Location: dashboard.php?notif=success");
} else {
    header("Location: dashboard.php?notif=failed");
}
?>