<?php 

session_start();
include 'config/koneksi.php'; 

if (!isset($_SESSION['login'])) {
    header("Location: welcome.php");
    exit;
}

include 'layouts/header.php'; 

$role = $_SESSION['role'];
$id_user = $_SESSION['id_user'];
?>

<div class="container mt-4">

    <div class="p-5 mb-5 rounded-4 shadow text-white position-relative overflow-hidden" 
         style="background: linear-gradient(120deg, #89f7fe 0%, #66a6ff 100%); border:none;">
        <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.2); border-radius: 50%;"></div>
        <div style="position: absolute; bottom: -30px; left: -30px; width: 150px; height: 150px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>

        <div class="container-fluid py-2 position-relative">
            <h1 class="display-5 fw-bold mb-2">Halo, <?= explode(' ', $_SESSION['nama'])[0] ?>! 👋</h1>
            <p class="fs-5 mb-4" style="opacity: 0.9;">Selamat datang di Dashboard Perpustakaan Modern.</p>
            
            <?php if($_SESSION['role'] == 'anggota'): ?>
                <a href="buku/index.php" class="btn btn-light text-primary fw-bold px-4 py-2 rounded-pill shadow-sm">
                    <i class="bi bi-search me-2"></i> Cari Buku Sekarang
                </a>
            <?php else: ?>
                <a href="buku/index.php" class="btn btn-light text-primary fw-bold px-4 py-2 rounded-pill shadow-sm">
                    <i class="bi bi-gear me-2"></i> Kelola Sistem
                </a>
            <?php endif; ?>
        </div>
    </div>

    <?php if ($role == 'anggota') : ?>
        <?php
        $my_pinjam = mysqli_query($koneksi, "SELECT * FROM peminjaman WHERE id_user='$id_user' AND status='dipinjam'");
        $count_pinjam = mysqli_num_rows($my_pinjam);

        $query_riwayat = mysqli_query($koneksi, "SELECT SUM(denda) as total FROM peminjaman WHERE id_user='$id_user' AND status='kembali'");
        $data_riwayat = mysqli_fetch_assoc($query_riwayat);
        $denda_sudah_bayar = $data_riwayat['total'] ? $data_riwayat['total'] : 0;

        $denda_berjalan = 0;
        $query_aktif = mysqli_query($koneksi, "SELECT tgl_kembali_rencana FROM peminjaman WHERE id_user='$id_user' AND status='dipinjam'");
        while($da = mysqli_fetch_assoc($query_aktif)){
            $tgl_now = new DateTime();
            $tgl_max = new DateTime($da['tgl_kembali_rencana']);
            if($tgl_now > $tgl_max){
                $selisih = $tgl_now->diff($tgl_max)->days;
                $denda_berjalan += ($selisih * 1000);
            }
        }
        $total_denda = $denda_sudah_bayar + $denda_berjalan;
        ?>

        <div class="row g-4 mb-5">
            <div class="col-md-6">
                <div class="card bg-white border-0 shadow-sm overflow-hidden h-100">
                    <div class="card-body d-flex align-items-center justify-content-between p-4">
                        <div>
                            <h6 class="text-muted mb-2 text-uppercase fw-bold" style="font-size: 0.8rem;">Sedang Dipinjam</h6>
                            <h2 class="fw-bold text-primary mb-0 display-5"><?= $count_pinjam ?></h2>
                            <span class="text-muted small">Buku</span>
                        </div>
                        <div class="bg-primary bg-opacity-10 rounded-4 p-3 text-primary d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <i class="bi bi-journal-bookmark-fill" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card bg-white border-0 shadow-sm overflow-hidden h-100">
                    <div class="card-body d-flex align-items-center justify-content-between p-4">
                        <div>
                            <h6 class="text-muted mb-2 text-uppercase fw-bold" style="font-size: 0.8rem;">Total Denda</h6>
                            <h2 class="fw-bold <?= $total_denda > 0 ? 'text-danger' : 'text-success' ?> mb-0 display-6">
                                Rp <?= number_format($total_denda) ?>
                            </h2>
                            <span class="text-muted small"><?= $total_denda > 0 ? 'Segera Selesaikan' : 'Aman' ?></span>
                        </div>
                        <div class="bg-danger bg-opacity-10 rounded-4 p-3 text-danger d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <i class="bi bi-wallet2" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <h5 class="fw-bold mb-3 text-secondary"><i class="bi bi-clock-history me-2"></i>Status Peminjaman Aktif</h5>
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="py-3 ps-4">Buku</th>
                            <th class="py-3">Tgl Pinjam</th>
                            <th class="py-3">Wajib Kembali</th>
                            <th class="py-3">Status / Denda</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $query_active = mysqli_query($koneksi, "SELECT * FROM peminjaman JOIN buku ON peminjaman.id_buku = buku.id_buku WHERE id_user='$id_user' AND status='dipinjam' ORDER BY tgl_kembali_rencana ASC");
                        if(mysqli_num_rows($query_active) > 0):
                            while($row = mysqli_fetch_assoc($query_active)):
                                $tgl_sekarang = new DateTime(); 
                                $tgl_kembali  = new DateTime($row['tgl_kembali_rencana']); 
                                $is_telat = ($tgl_sekarang > $tgl_kembali); 
                                $selisih = $tgl_sekarang->diff($tgl_kembali)->days;
                                $estimasi_denda = $is_telat ? ($selisih * 1000) : 0;
                        ?>
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <?php if($row['gambar'] && file_exists("assets/img/" . $row['gambar'])): ?>
                                        <img src="assets/img/<?= $row['gambar'] ?>" class="rounded me-3" width="40" height="60" style="object-fit:cover;">
                                    <?php else: ?>
                                        <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center text-muted" style="width:40px; height:60px; font-size:0.7rem;">No img</div>
                                    <?php endif; ?>
                                    <div><span class="fw-bold d-block text-dark"><?= $row['judul'] ?></span></div>
                                </div>
                            </td>
                            <td><?= date('d M Y', strtotime($row['tgl_pinjam'])) ?></td>
                            <td class="<?= $is_telat ? 'text-danger fw-bold' : '' ?>"><?= date('d M Y', strtotime($row['tgl_kembali_rencana'])) ?></td>
                            <td>
                                <?php if($is_telat): ?>
                                    <span class="badge bg-danger mb-1">Telat <?= $selisih ?> Hari</span><br>
                                    <small class="text-danger fw-bold">Denda: Rp <?= number_format($estimasi_denda) ?></small>
                                <?php else: ?>
                                    <span class="badge bg-success mb-1">Aman</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endwhile; else: ?>
                        <tr><td colspan="4" class="text-center py-5 text-muted">Tidak ada buku dipinjam.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($role == 'admin') : ?>
        <?php
        $buku = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM buku"));
        $user = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM users WHERE role='anggota'"));
        $pinjam = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM peminjaman WHERE status='dipinjam'"));
        ?>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body text-center p-4">
                        <div class="mb-3 text-primary"><i class="bi bi-collection-fill" style="font-size: 3rem;"></i></div>
                        <h2 class="fw-bold"><?= $buku ?></h2>
                        <h6 class="text-muted">Total Judul Buku</h6>
                        <a href="buku/index.php" class="btn btn-outline-primary mt-3 btn-sm rounded-pill px-4">Kelola Buku</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body text-center p-4">
                        <div class="mb-3 text-success"><i class="bi bi-people-fill" style="font-size: 3rem;"></i></div>
                        <h2 class="fw-bold"><?= $user ?></h2>
                        <h6 class="text-muted">Anggota Terdaftar</h6>
                        <a href="anggota/index.php" class="btn btn-outline-success mt-3 btn-sm rounded-pill px-4">Lihat Anggota</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body text-center p-4">
                        <div class="mb-3 text-warning"><i class="bi bi-arrow-left-right" style="font-size: 3rem;"></i></div>
                        <h2 class="fw-bold"><?= $pinjam ?></h2>
                        <h6 class="text-muted">Sedang Dipinjam</h6>
                        <a href="transaksi/index.php" class="btn btn-outline-warning mt-3 btn-sm rounded-pill px-4">Cek Transaksi</a>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

</div>

<?php include 'layouts/footer.php'; ?>
</body>
</html>