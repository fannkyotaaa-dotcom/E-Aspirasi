<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Sarpras | SMK Sangkuriang 1 Cimahi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #002154; /* Navy SMK */
            --accent-color: #ffc107;  /* Gold/Yellow */
            --light-bg: #f8f9fa;
        }

        body { 
            font-family: 'Poppins', sans-serif; 
            color: #333;
            scroll-behavior: smooth;
        }

        /* Navbar Styling */
        .navbar {
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
        }
        .navbar-brand { font-weight: 700; color: var(--primary-color) !important; }

        /* Hero Section */
        .hero-section {
            background: linear-gradient(rgba(0, 33, 84, 0.8), rgba(0, 33, 84, 0.6)), 
                        url('https://images.unsplash.com/photo-1523050853063-9158946122a2?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
            min-height: 100vh;
            display: flex;
            align-items: center;
            color: white;
            padding-top: 80px;
        }

        .hero-title { font-weight: 700; font-size: 3.5rem; line-height: 1.2; }
        .hero-subtitle { font-weight: 300; font-size: 1.2rem; opacity: 0.9; }

        /* Card Steps */
        .step-card {
            border: none;
            border-radius: 20px;
            background: #fff;
            transition: all 0.3s ease;
            height: 100%;
        }
        .step-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
        }
        .icon-circle {
            width: 80px;
            height: 80px;
            background: var(--light-bg);
            color: var(--primary-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin: 0 auto 20px;
            border: 2px dashed var(--primary-color);
        }

        /* Section Title */
        .section-title {
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 15px;
            position: relative;
            padding-bottom: 15px;
        }
        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 4px;
            background: var(--accent-color);
            border-radius: 2px;
        }

        footer { background: var(--primary-color); }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg fixed-top py-3">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <i class="bi bi-building-fill-gear fs-3 me-2"></i>
                <span>E-SARPRAS <small class="d-block fs-6 fw-light">SMK Sangkuriang 1</small></span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link fw-semibold px-3" href="#home">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link fw-semibold px-3" href="#alur">Alur Lapor</a></li>
                    <li class="nav-item ms-lg-3">
                        <!-- <a class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold" href="login.php">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Login Siswa
                        </a> -->
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <header id="home" class="hero-section">
        <div class="container text-center text-lg-start">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <span class="badge bg-warning text-dark mb-3 px-3 py-2 rounded-pill fw-bold">Pusat Layanan Aspirasi Sarana</span>
                    <h1 class="hero-title mb-4">Fasilitas Rusak?<br><span class="text-warning">Laporkan</span> Sekarang.</h1>
                    <p class="hero-subtitle mb-5">Sistem Informasi Pengaduan Sarana dan Prasarana SMK Sangkuriang 1 Cimahi. Memastikan setiap fasilitas mendukung kenyamanan belajar Anda.</p>
                    <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center justify-content-lg-start">
                        <a href="login.php" class="btn btn-warning btn-lg px-5 py-3 rounded-pill fw-bold shadow">
                            Buat Laporan <i class="bi bi-arrow-right ms-2"></i>
                        </a>
                        <a href="#alur" class="btn btn-outline-light btn-lg px-5 py-3 rounded-pill">
                            Lihat Cara Kerja
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <section id="alur" class="py-5 bg-light">
        <div class="container py-5">
            <div class="text-center mb-5">
                <h2 class="section-title">Alur Pengaduan</h2>
                <p class="text-muted">Prosedur sederhana untuk melaporkan kendala sarana sekolah</p>
            </div>
            
            <div class="row g-4 text-center">
                <div class="col-md-3">
                    <div class="card step-card p-4 shadow-sm">
                        <div class="icon-circle shadow-sm"><i class="bi bi-person-plus-fill"></i></div>
                        <h5 class="fw-bold">1. Akun</h5>
                        <p class="small text-muted mb-0">Lakukan pendaftaran akun siswa atau langsung login ke sistem.</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card step-card p-4 shadow-sm">
                        <div class="icon-circle shadow-sm"><i class="bi bi-megaphone-fill"></i></div>
                        <h5 class="fw-bold">2. Lapor</h5>
                        <p class="small text-muted mb-0">Isi form pengaduan, pilih kategori, dan lampirkan foto bukti.</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card step-card p-4 shadow-sm">
                        <div class="icon-circle shadow-sm"><i class="bi bi-gear-fill"></i></div>
                        <h5 class="fw-bold">3. Tindakan</h5>
                        <p class="small text-muted mb-0">Tim Sarpras akan memverifikasi dan memperbaiki fasilitas tersebut.</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card step-card p-4 shadow-sm">
                        <div class="icon-circle shadow-sm"><i class="bi bi-check-circle-fill text-success"></i></div>
                        <h5 class="fw-bold">4. Selesai</h5>
                        <p class="small text-muted mb-0">Status laporan akan diperbarui dan fasilitas siap digunakan kembali.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="text-white pt-5 pb-3">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-6">
                    <h5 class="fw-bold mb-3">SMK Sangkuriang 1 Cimahi</h5>
                    <p class="small text-white-50">Menghasilkan lulusan yang kompeten, berkarakter, dan siap bersaing di dunia kerja maupun industri global.</p>
                    <div class="d-flex gap-3 mt-4">
                        <a href="#" class="text-white fs-4"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="text-white fs-4"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="text-white fs-4"><i class="bi bi-globe"></i></a>
                    </div>
                </div>
                <div class="col-md-3">
                    <h5 class="fw-bold mb-3">Navigasi</h5>
                    <ul class="list-unstyled small">
                        <li class="mb-2"><a href="#home" class="text-white-50 text-decoration-none">Beranda</a></li>
                        <li class="mb-2"><a href="login.php" class="text-white-50 text-decoration-none">Masuk Sistem</a></li>
                        <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Profil Sekolah</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5 class="fw-bold mb-3">Lokasi</h5>
                    <p class="small text-white-50">
                        <i class="bi bi-geo-alt-fill me-2 text-warning"></i> Jl. Sangkuriang No.76, Cipageran, Kec. Cimahi Utara, Kota Cimahi, Jawa Barat 40511.
                    </p>
                </div>
            </div>
            <hr class="my-4 border-secondary">
            <div class="text-center small text-white-50">
                &copy; 2026 SMK Sangkuriang 1 Cimahi.
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>