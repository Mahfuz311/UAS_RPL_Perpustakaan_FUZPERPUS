<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang - FUZPERPUS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
    
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif; 
            background: #f8f9fa;
            
            scrollbar-width: none; 
            -ms-overflow-style: none;  
        }

        html::-webkit-scrollbar, 
        body::-webkit-scrollbar {
            display: none;
        }

        *::-webkit-scrollbar {
            display: none;
        }

        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 140px 0 100px;
            border-bottom-left-radius: 50px;
            border-bottom-right-radius: 50px;
            margin-bottom: 50px;
        }
        .card-feature {
            border: none; border-radius: 15px; transition: 0.3s;
            background: white; padding: 30px; height: 100%;
        }
        .card-feature:hover { transform: translateY(-10px); box-shadow: 0 15px 30px rgba(0,0,0,0.1); }
        .icon-box {
            width: 60px; height: 60px; background: #eef2ff; color: #667eea;
            border-radius: 12px; display: flex; align-items: center; justify-content: center;
            font-size: 1.5rem; margin-bottom: 20px;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark fixed-top" style="background: rgba(0,0,0,0.2); backdrop-filter: blur(5px);">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#"><i class="bi bi-book-half me-2"></i>FUZPERPUS</a>
            <div class="ms-auto">
                <a href="auth/login.php" class="btn btn-light rounded-pill px-4 fw-bold text-primary shadow-sm">Login Area</a>
            </div>
        </div>
    </nav>

    <section class="hero-section text-center">
        <div class="container">
            <h1 class="display-4 fw-bold mb-3">Selamat Datang di FUZPERPUS</h1>
            <h3 class="display-5 fw-bold mb-3">Perpustakaan Masa Depan</h3>
            <p class="lead mb-5 opacity-75">Sistem informasi perpustakaan digital.<br>Pinjam buku, cek denda, dan kelola anggota dengan mudah.</p>
            
            <div class="d-flex justify-content-center gap-3">
                <a href="auth/login.php" class="btn btn-warning btn-lg rounded-pill px-5 fw-bold shadow">
                    <i class="bi bi-box-arrow-in-right me-2"></i> Masuk Sekarang
                </a>
                <a href="auth/register.php" class="btn btn-outline-light btn-lg rounded-pill px-5 fw-bold">
                    Daftar Akun
                </a>
            </div>
        </div>
    </section>

    <section class="container mb-5">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card-feature shadow-sm">
                    <div class="icon-box"><i class="bi bi-search"></i></div>
                    <h5 class="fw-bold">Pencarian Cepat</h5>
                    <p class="text-muted small mb-0">Temukan buku favoritmu dalam hitungan detik dengan fitur pencarian pintar.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card-feature shadow-sm">
                    <div class="icon-box"><i class="bi bi-qr-code-scan"></i></div>
                    <h5 class="fw-bold">Kartu Digital</h5>
                    <p class="text-muted small mb-0">Cukup tunjukkan QR Code pada HP Anda untuk meminjam buku tanpa kartu fisik.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card-feature shadow-sm">
                    <div class="icon-box"><i class="bi bi-bell"></i></div>
                    <h5 class="fw-bold">Cek Denda Realtime</h5>
                    <p class="text-muted small mb-0">Transparansi total. Cek status keterlambatan dan nominal denda langsung dari dashboard.</p>
                </div>
            </div>
        </div>
    </section>

    <footer class="text-center py-4 text-muted small border-top bg-white">
        &copy; <?= date('Y') ?> FUZPERPUS. Developed by Mahfuz Fauzi.
    </footer>

</body>
</html>