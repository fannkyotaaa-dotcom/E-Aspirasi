<?php
require_once 'config/Database.php';
require_once 'classes/Auth.php';
require_once 'classes/User.php';

$database = new Database();
$db = $database->getConnection();
$auth = new Auth($db);
$userObj = new User($db);

$error = "";
$success = "";

if (isset($_POST['login'])) {
    $login = $auth->login($_POST['username'], $_POST['password']);
    if ($login) {
        header("Location: views/" . ($login == 'admin' ? 'admin' : 'siswa') . "/dashboard.php");
        exit;
    } else {
        $error = "Username atau Password salah!";
    }
}

if (isset($_POST['register'])) {
    $result = $userObj->register($_POST['reg_username'], $_POST['reg_password'], $_POST['reg_nama'], $_POST['reg_nis']);
    if ($result === true) {
        $success = "Registrasi berhasil! Silakan login di bawah.";
    } else {
        $error = $result;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - E-Sarpras SMK Sangkuriang 1</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(rgba(145, 146, 147, 0.8), rgba(0, 33, 84, 0.9)), 
                        url('https://yogya.co/wp-content/uploads/kunjungan-industri.webp');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .auth-card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            overflow: hidden;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
        }
        .auth-header {
            background: #002154;
            color: white;
            padding: 30px;
            text-align: center;
        }
        .nav-pills .nav-link {
            color: #002154;
            font-weight: 600;
            border-radius: 10px;
        }
        .nav-pills .nav-link.active {
            background-color: #0d6efd;
        }
        .form-control {
            border-radius: 10px;
            padding: 12px;
            border: 1px solid #ddd;
        }
        .btn-auth {
            padding: 12px;
            border-radius: 10px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .back-to-home {
            position: absolute;
            top: 20px;
            left: 20px;
            text-decoration: none;
            color: white;
            font-weight: 500;
        }
    </style>
</head>
<body>

    <a href="index.php" class="back-to-home"><i class="bi bi-arrow-left me-2"></i>Beranda Utama</a>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-8">
                <div class="card auth-card">
                    <div class="auth-header">
                        <i class="bi bi-building-fill-gear fs-1 mb-2"></i>
                        <h4 class="fw-bold mb-0">E-SARPRAS</h4>
                        <small>SMK SANGKURIANG 1 CIMAHI</small>
                    </div>
                    
                    <div class="card-body p-4 p-md-5">
                        <?php if($error): ?>
                            <div class="alert alert-danger d-flex align-items-center small" role="alert">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= $error; ?>
                            </div>
                        <?php endif; ?>

                        <?php if($success): ?>
                            <div class="alert alert-success d-flex align-items-center small" role="alert">
                                <i class="bi bi-check-circle-fill me-2"></i> <?= $success; ?>
                            </div>
                        <?php endif; ?>

                        <ul class="nav nav-pills nav-justified mb-4" id="pills-tab" role="tablist">
                            <li class="nav-item">
                                <button class="nav-link active" id="pills-login-tab" data-bs-toggle="pill" data-bs-target="#pills-login"><i class="bi bi-box-arrow-in-right me-2"></i>Masuk</button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link" id="pills-register-tab" data-bs-toggle="pill" data-bs-target="#pills-register"><i class="bi bi-person-plus me-2"></i>Daftar</button>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="pills-login">
                                <form action="" method="POST">
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold">Username</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-person"></i></span>
                                            <input type="text" name="username" class="form-control border-start-0" placeholder="Masukkan username" required>
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label small fw-bold">Password</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock"></i></span>
                                            <input type="password" name="password" class="form-control border-start-0" placeholder="Masukkan password" required>
                                        </div>
                                    </div>
                                    <button type="submit" name="login" class="btn btn-primary w-100 btn-auth shadow">Masuk ke Sistem</button>
                                </form>
                            </div>

                            <div class="tab-pane fade" id="pills-register">
                                <form action="" method="POST">
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold">Nama Lengkap Siswa</label>
                                        <input type="text" name="reg_nama" class="form-control" placeholder="Nama sesuai absen" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold">NIS</label>
                                        <input type="text" name="reg_nis" class="form-control" placeholder="Nomor Induk Siswa" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold">Buat Username</label>
                                        <input type="text" name="reg_username" class="form-control" placeholder="Contoh: Dian Ganteng" required>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label small fw-bold">Buat Password</label>
                                        <input type="password" name="reg_password" class="form-control" placeholder="******" required>
                                    </div>
                                    <button type="submit" name="register" class="btn btn-success w-100 btn-auth shadow">Daftar Akun</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-light border-0 py-3 text-center">
                        <small class="text-muted">Masalah Login? Hubungi <span class="text-primary fw-bold">Lab Komputer</span></small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>