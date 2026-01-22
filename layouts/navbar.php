<?php

$uri = $_SERVER['REQUEST_URI'];

$is_buku      = strpos($uri, '/buku/') !== false;
$is_anggota   = strpos($uri, '/anggota/') !== false;
$is_transaksi = strpos($uri, '/transaksi/') !== false;
$is_laporan   = strpos($uri, '/laporan/') !== false;
$is_profil    = strpos($uri, 'profil.php') !== false;

$is_home      = !$is_buku && !$is_anggota && !$is_transaksi && !$is_laporan && !$is_profil;
?>

<nav class="navbar navbar-expand-lg navbar-dark navbar-custom mb-4 sticky-top">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center" href="<?= base_url() ?>">
        <i class="bi bi-book-half me-2"></i> FUZPERPUS
    </a>
    
    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto align-items-center gap-2">
        
        <li class="nav-item">
            <a class="nav-link <?= $is_home ? 'active fw-bold' : '' ?>" href="<?= base_url() ?>">Home</a>
        </li>

        <?php if ($_SESSION['role'] == 'admin') : ?>
            <li class="nav-item">
                <a class="nav-link <?= $is_buku ? 'active fw-bold' : '' ?>" href="<?= base_url('buku/index.php') ?>">Master Buku</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $is_anggota ? 'active fw-bold' : '' ?>" href="<?= base_url('anggota/index.php') ?>">Master User</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $is_transaksi ? 'active fw-bold' : '' ?>" href="<?= base_url('transaksi/index.php') ?>">Transaksi</a>
            </li>
            
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle <?= $is_laporan ? 'active fw-bold' : '' ?>" href="#" role="button" data-bs-toggle="dropdown">
                    Laporan
                </a>
                <ul class="dropdown-menu border-0 shadow mt-2 rounded-3">
                    <li>
                        <a class="dropdown-item py-2" href="<?= base_url('laporan/index.php') ?>">
                            <i class="bi bi-file-earmark-pdf me-2 text-danger"></i> Laporan PDF
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item py-2" href="<?= base_url('laporan/statistik.php') ?>">
                            <i class="bi bi-bar-chart-line me-2 text-primary"></i> Statistik Grafik
                        </a>
                    </li>
                </ul>
            </li>
        <?php endif; ?>

        <?php if ($_SESSION['role'] == 'anggota') : ?>
             <li class="nav-item">
                <a class="nav-link <?= $is_buku ? 'active fw-bold' : '' ?>" href="<?= base_url('buku/index.php') ?>">
                    Katalog Buku
                </a>
             </li>
             
             <li class="nav-item">
                <a class="nav-link <?= strpos($uri, 'riwayat.php') !== false ? 'active fw-bold' : '' ?>" href="<?= base_url('transaksi/riwayat.php') ?>">
                    Riwayat Peminjaman
                </a>
             </li>

             <li class="nav-item">
                <a class="nav-link <?= strpos($uri, 'kartu.php') !== false ? 'active fw-bold' : '' ?>" href="<?= base_url('anggota/kartu.php') ?>">
                    Kartu Anggota
                </a>
             </li>
        <?php endif; ?>

        <li class="nav-item dropdown ms-lg-3">
            <a class="nav-link dropdown-toggle d-flex align-items-center bg-white bg-opacity-10 rounded-pill px-3 py-1 text-white border border-white border-opacity-25" 
               href="#" role="button" data-bs-toggle="dropdown">
                <img src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['nama']) ?>&background=random&size=32" 
                     class="rounded-circle me-2" width="25" height="25">
                <span class="small me-1">Hi, <?= explode(' ', $_SESSION['nama'])[0] ?></span>
            </a>
            
            <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-4 mt-2">
                <li>
                    <a class="dropdown-item py-2 <?= $is_profil ? 'active bg-primary text-white' : '' ?>" href="<?= base_url('profil.php') ?>">
                        <i class="bi bi-person-circle me-2 <?= $is_profil ? 'text-white' : 'text-primary' ?>"></i> Profil Saya
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item py-2 text-danger" href="#" onclick="konfirmasiLogout()">
                        <i class="bi bi-box-arrow-right me-2"></i> Logout
                    </a>

                    <script>
                    function konfirmasiLogout() {
                        Swal.fire({
                            title: 'Yakin ingin keluar?',
                            text: "Anda harus login kembali untuk mengakses sistem.",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#3085d6',
                            confirmButtonText: 'Ya, Logout!',
                            cancelButtonText: 'Batal'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = "<?= base_url('auth/proses_auth.php?logout=true') ?>";
                            }
                        })
                    }
                    </script>
                </li>
            </ul>
        </li>

      </ul>
    </div>
  </div>
</nav>