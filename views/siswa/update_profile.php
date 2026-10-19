<?php
session_start();

// 1. Proteksi: hanya siswa yang sudah login
if (!isset($_SESSION['user_id']) || $_SESSION['level'] !== 'siswa') {
    header("Location: ../../login.php");
    exit;
}

// 2. Hanya terima kiriman dari form ganti kata sandi
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['update'])) {
    header("Location: profile.php");
    exit;
}

require_once '../../config/Database.php';
require_once '../../classes/User.php';

$database = new Database();
$db = $database->getConnection();
$userObj = new User($db);

$password_lama  = $_POST['current_password'] ?? '';
$password_baru  = $_POST['new_password'] ?? '';
$password_ulang = $_POST['confirm_password'] ?? '';

// 3. Validasi input
if ($password_lama === '' || $password_baru === '' || $password_ulang === '') {
    header("Location: profile.php?msg=kosong");
    exit;
}
if (strlen($password_baru) < 6) {
    header("Location: profile.php?msg=pendek");
    exit;
}
if ($password_baru !== $password_ulang) {
    header("Location: profile.php?msg=beda");
    exit;
}

// 4. Cek kata sandi saat ini (bcrypt untuk akun baru, MD5 untuk akun lama)
$user = $userObj->getUserById($_SESSION['user_id']);
if (!$user) {
    header("Location: ../../logout.php");
    exit;
}
$password_lama_benar = password_verify($password_lama, $user['password']) || (md5($password_lama) === $user['password']);
if (!$password_lama_benar) {
    header("Location: profile.php?msg=salah");
    exit;
}

// 5. Simpan kata sandi baru (di-hash dengan password_hash)
if ($userObj->updateProfile($_SESSION['user_id'], $user['nama_lengkap'], $password_baru)) {
    header("Location: profile.php?msg=success");
} else {
    header("Location: profile.php?msg=gagal");
}
exit;