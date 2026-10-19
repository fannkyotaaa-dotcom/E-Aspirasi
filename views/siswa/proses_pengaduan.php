<?php
session_start();
require_once '../../config/Database.php';

if (isset($_POST['submit_pengaduan'])) {
    $database = new Database();
    $db = $database->getConnection();

    $nis = $_SESSION['nis'];
    $id_kategori = $_POST['id_kategori'];
    $lokasi = $_POST['lokasi'];
    $isi = $_POST['deskripsi_laporan']; // Ini dari nama 'name' di form modal
    $tgl = date('Y-m-d H:i:s');

    $query = "INSERT INTO aspirasi (nis, id_kategori, lokasi, isi_aspirasi, tanggal, status)
              VALUES (:nis, :id_kategori, :lokasi, :isi, :tgl, 'pending')";

    $stmt = $db->prepare($query);
    $stmt->bindParam(':nis', $nis);
    $stmt->bindParam(':id_kategori', $id_kategori);
    $stmt->bindParam(':lokasi', $lokasi);
    $stmt->bindParam(':isi', $isi);
    $stmt->bindParam(':tgl', $tgl);

    if ($stmt->execute()) {
        header("Location: dashboard.php?msg=success");
    } else {
        header("Location: dashboard.php?msg=error");
    }
}