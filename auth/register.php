<?php 
session_start();
include '../config/koneksi.php';

if(isset($_POST['daftar'])){
    $nim = htmlspecialchars($_POST['nim']);
    $nama = htmlspecialchars($_POST['nama']);
    $jurusan = htmlspecialchars($_POST['jurusan']);
    $username = htmlspecialchars($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = 'anggota'; 

    $cek_user = mysqli_query($koneksi, "SELECT * FROM users WHERE username = '$username'");
    $cek_nim = mysqli_query($koneksi, "SELECT * FROM users WHERE nim = '$nim'");

    if(mysqli_num_rows($cek_user) > 0){
        $pesan_error = "Username sudah digunakan, silakan pilih yang lain.";
    } elseif(mysqli_num_rows($cek_nim) > 0){
        $pesan_error = "NIM sudah terdaftar sebelumnya.";
    } else {
        $query = "INSERT INTO users (nim, nama, jurusan, username, password, role) 
                  VALUES ('$nim', '$nama', '$jurusan', '$username', '$password', '$role')";
        
        if(mysqli_query($koneksi, $query)){
            $_SESSION['info'] = [
                'status' => 'success',
                'pesan' => 'Pendaftaran berhasil! Silakan login.'
            ];
            header("Location: login.php");
            exit;
        } else {
            $pesan_error = "Gagal mendaftar, coba lagi nanti.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - FUZPERPUS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        body { 
            font-family: 'Poppins', sans-serif; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .register-card { 
            border: none; 
            border-radius: 20px; 
            overflow: hidden; 
            box-shadow: 0 15px 35px rgba(0,0,0,0.2); 
            background: #fff;
            width: 100%;
            max-width: 500px;
            position: relative; 
            z-index: 10;
        }

        .register-header { 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 25px 20px; 
            text-align: center; 
            color: white; 
        }

        .icon-circle {
            width: 60px;
            height: 60px;
            background: rgba(255,255,255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            backdrop-filter: blur(5px);
        }

        .form-control {
            background: #f8f9fa;
            border: 1px solid #eee;
            padding: 10px 15px;
            font-size: 0.9rem;
        }
        .form-control:focus {
            background: #fff;
            box-shadow: none;
            border-color: #667eea;
        }
        .form-label {
            font-size: 0.85rem;
            font-weight: 600;
            color: #6c757d;
            margin-bottom: 5px;
        }

        .btn-primary { 
            background: #667eea; 
            border: none; 
            padding: 10px; 
            font-weight: 600;
            transition: 0.3s;
        }
        .btn-primary:hover { 
            background: #764ba2; 
            transform: translateY(-2px);
        }

        .link-back-wrapper {
            margin-top: 25px;
            text-align: center;
            z-index: 5;
        }
        .link-back {
            color: rgba(255,255,255, 0.8);
            text-decoration: none;
            font-size: 0.9rem;
            transition: 0.3s;
        }
        .link-back:hover {
            color: #fff;
            text-decoration: underline;
        }
        
        .login-link {
            font-size: 0.9rem;
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }
    </style>
</head>
<body>

    <div class="card register-card">
        <div class="register-header">
            <div class="icon-circle">
                <i class="bi bi-person-plus-fill fs-3 text-white"></i>
            </div>
            <h5 class="fw-bold mb-1">Daftar Anggota Baru</h5>
            <p class="mb-0 small opacity-75">Bergabunglah dengan Perpustakaan Kami</p>
        </div>
        
        <div class="card-body p-4">
            
            <?php if(isset($pesan_error)): ?>
                <div class="alert alert-danger py-2 small rounded-3 mb-3">
                    <i class="bi bi-exclamation-circle me-1"></i> <?= $pesan_error ?>
                </div>
            <?php endif; ?>

            <form action="" method="POST">
                
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label">NIM / NIS</label>
                        <input type="number" name="nim" class="form-control" placeholder="Cth: 12345" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control" placeholder="Nama Anda" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Jurusan / Prodi</label>
                    <input type="text" name="jurusan" class="form-control" placeholder="Cth: Teknik Informatika" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-at text-muted"></i></span>
                        <input type="text" name="username" class="form-control border-start-0 ps-0" placeholder="Buat username unik" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-key text-muted"></i></span>
                        <input type="password" name="password" class="form-control border-start-0 ps-0" placeholder="Minimal 6 karakter" required>
                    </div>
                </div>

                <button type="submit" name="daftar" class="btn btn-primary w-100 rounded-pill shadow-sm">
                    DAFTAR SEKARANG
                </button>

                <div class="text-center mt-4">
                    <span class="text-muted small">Sudah punya akun?</span>
                    <a href="login.php" class="login-link ms-1">Login Disini</a>
                </div>

            </form>
        </div>
    </div>

    <div class="link-back-wrapper">
        <a href="../welcome.php" class="link-back">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Halaman Depan
        </a>
    </div>

</body>
</html>