<?php 
session_start(); 
include '../config/koneksi.php';

if(isset($_SESSION['login'])){
    header("Location: ../index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - FUZPERPUS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        body { 
            font-family: 'Poppins', sans-serif; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-card { 
            border: none; 
            border-radius: 20px; 
            overflow: hidden; 
            box-shadow: 0 15px 35px rgba(0,0,0,0.2); 
            background: #fff;
            width: 100%;
            max-width: 400px; 
        }

        .login-header { 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 30px 20px; 
            text-align: center; 
            color: white; 
        }

        .icon-circle {
            width: 70px;
            height: 70px;
            background: rgba(255,255,255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            backdrop-filter: blur(5px);
        }

        .form-control {
            background: #f8f9fa;
            border-left: none;
            padding: 12px;
            font-size: 0.95rem;
        }
        .form-control:focus {
            background: #fff;
            box-shadow: none;
            border-color: #667eea;
        }
        .input-group-text {
            background: #f8f9fa;
            border-right: none;
            color: #6c757d;
        }

        .btn-primary { 
            background: #667eea; 
            border: none; 
            padding: 12px; 
            font-weight: 600;
            transition: 0.3s;
        }
        .btn-primary:hover { 
            background: #764ba2; 
            transform: translateY(-2px);
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
        
        .register-link {
            font-size: 0.9rem;
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }
        .register-link:hover {
            color: #764ba2;
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="card login-card">
        <div class="login-header">
            <div class="icon-circle">
                <i class="bi bi-book-half fs-2 text-white"></i>
            </div>
            <h4 class="fw-bold mb-1">FUZPERPUS</h4>
            <p class="mb-0 small opacity-75">Silakan login akun Anda</p>
        </div>
        
        <div class="card-body p-4 pt-4">
            <form action="proses_auth.php" method="POST">
                
                <div class="mb-3">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="text" name="username" class="form-control" placeholder="Username" required autofocus>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" name="password" class="form-control" placeholder="Password" required>
                    </div>
                </div>

                <button type="submit" name="login" class="btn btn-primary w-100 rounded-pill mb-3 shadow-sm">
                    MASUK SEKARANG
                </button>

                <div class="text-center">
                    <span class="text-muted small">Belum punya akun?</span>
                    <a href="register.php" class="register-link ms-1">Daftar Disini</a>
                </div>

            </form>
        </div>
    </div>

    <div class="position-absolute bottom-0 mb-4">
        <a href="../welcome.php" class="link-back">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Halaman Depan
        </a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php if(isset($_SESSION['info'])): ?>
        <script>
            Swal.fire({
                icon: '<?= $_SESSION['info']['status'] ?>', 
                title: '<?= $_SESSION['info']['status'] == 'success' ? 'Berhasil!' : 'Gagal Masuk' ?>',
                text: '<?= $_SESSION['info']['pesan'] ?>',
                confirmButtonColor: '#667eea',
                confirmButtonText: 'OK'
            });
        </script>
        <?php unset($_SESSION['info']); ?>
    <?php endif; ?>

</body>
</html>