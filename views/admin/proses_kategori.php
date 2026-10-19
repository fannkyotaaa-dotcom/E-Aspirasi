<?php
session_start();

// 1. Cek Keamanan Sesi
if (!isset($_SESSION['user_id']) || $_SESSION['level'] !== 'admin') {
    header("Location: ../../login.php");
    exit;
}

// 2. Inisialisasi Database
require_once '../../config/Database.php';
$database = new Database();
$db = $database->getConnection();

// 3. LOGIKA TAMBAH DATA
if (isset($_POST['tambah'])) {
    $nama = trim($_POST['nama_kategori']);

    $query = "INSERT INTO kategori (nama_kategori) VALUES (:nama)";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':nama', $nama);

    if ($stmt->execute()) {
        header("Location: kategori.php?status=success_tambah");
    } else {
        header("Location: kategori.php?status=error");
    }
}

// 4. LOGIKA EDIT DATA
if (isset($_POST['edit'])) {
    $id = $_POST['id_kategori'];
    $nama = trim($_POST['nama_kategori']);

    $query = "UPDATE kategori SET nama_kategori = :nama WHERE id_kategori = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':nama', $nama);
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        header("Location: kategori.php?status=success_edit");
    } else {
        header("Location: kategori.php?status=error");
    }
}

exit;