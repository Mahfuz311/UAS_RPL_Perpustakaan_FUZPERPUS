<?php 
include '../config/koneksi.php'; 
include '../layouts/header.php'; 

if (!isset($_SESSION['login'])) { header("Location: ../auth/login.php"); exit; }

$id = $_SESSION['id_user'];
$query = mysqli_query($koneksi, "SELECT * FROM users WHERE id_user='$id'");
$user = mysqli_fetch_assoc($query);
?>

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            
            <div class="text-center mb-4">
                <h3 class="fw-bold text-primary">Kartu Anggota Digital</h3>
                <p class="text-muted">Tunjukkan kartu ini saat meminjam buku secara offline.</p>
            </div>

            <div class="card border-0 shadow-lg text-white position-relative overflow-hidden" 
                 style="border-radius: 20px; background: linear-gradient(135deg, #0d6efd 0%, #6610f2 100%); height: 300px;">
                
                <div style="position: absolute; top: -50px; right: -50px; width: 150px; height: 150px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
                <div style="position: absolute; bottom: -20px; left: -20px; width: 100px; height: 100px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>

                <div class="card-body p-4 d-flex flex-column justify-content-between h-100 position-relative" style="z-index: 2;">
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-book-half fs-2 me-2"></i>
                            <div>
                                <h5 class="mb-0 fw-bold">FUZPERPUS</h5>
                                <small style="opacity: 0.8; font-size: 0.7rem;">MEMBER CARD</small>
                            </div>
                        </div>
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=<?= $user['nim'] ?>" alt="QR" class="bg-white p-1 rounded" width="60">
                    </div>

                    <div class="mt-4">
                        <small class="text-uppercase" style="opacity: 0.7; letter-spacing: 1px;">Nama Anggota</small>
                        <h4 class="fw-bold mb-3"><?= strtoupper($user['nama']) ?></h4>
                        
                        <div class="row">
                            <div class="col-6">
                                <small class="text-uppercase" style="opacity: 0.7;">NIM / ID</small>
                                <p class="fw-bold mb-0 fs-5"><?= $user['nim'] ?></p>
                            </div>
                            <div class="col-6 text-end">
                                <small class="text-uppercase" style="opacity: 0.7;">Jurusan</small>
                                <p class="fw-bold mb-0"><?= $user['jurusan'] ?></p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="d-grid gap-2 mt-4">
                <button onclick="window.print()" class="btn btn-outline-primary rounded-pill fw-bold">
                    <i class="bi bi-printer me-2"></i> Cetak Kartu
                </button>
            </div>

        </div>
    </div>
</div>

<?php include '../layouts/footer.php'; ?>