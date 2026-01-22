<?php 
include '../config/koneksi.php'; 
include '../layouts/header.php'; 

if (!isset($_SESSION['login'])) {
    echo "<script>window.location='../auth/login.php';</script>";
    exit;
}

$id_user = $_SESSION['id_user'];
?>

<div class="container mt-4 mb-5">
    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <h3 class="fw-bold text-primary"><i class="bi bi-clock-history me-2"></i>Riwayat Peminjaman</h3>
            <p class="text-muted">Daftar buku yang sedang dan pernah Anda pinjam.</p>
        </div>
        <div class="col-md-6 text-md-end">
            <a href="../buku/index.php" class="btn btn-primary rounded-pill px-4 shadow-sm">
                <i class="bi bi-plus-lg me-2"></i>Pinjam Buku Lagi
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white py-3">
            <h6 class="mb-0 fw-bold">Data Transaksi Saya</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 text-center">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 text-start">Buku</th>
                            <th>Tgl Pinjam</th>
                            <th>Tgl Kembali / Jatuh Tempo</th>
                            <th>Status</th>
                            <th>Denda</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $query = mysqli_query($koneksi, "SELECT * FROM peminjaman 
                                JOIN buku ON peminjaman.id_buku = buku.id_buku 
                                WHERE id_user = '$id_user' 
                                ORDER BY id_pinjam DESC");
                        
                        if(mysqli_num_rows($query) > 0):
                            while($row = mysqli_fetch_assoc($query)):
                                $status = $row['status'];
                                $is_kembali = ($status == 'kembali');
                        ?>
                        <tr>
                            <td class="ps-4 text-start">
                                <span class="d-block fw-bold text-dark"><?= $row['judul'] ?></span>
                                <span class="small text-muted"><?= $row['pengarang'] ?></span>
                            </td>
                            <td><?= date('d M Y', strtotime($row['tgl_pinjam'])) ?></td>
                            <td>
                                <?php if($is_kembali): ?>
                                    <span class="text-muted"><?= date('d M Y', strtotime($row['tgl_kembali_aktual'])) ?></span>
                                    <br><small class="text-success fst-italic">(Dikembalikan)</small>
                                <?php else: ?>
                                    <span class="fw-bold text-primary"><?= date('d M Y', strtotime($row['tgl_kembali_rencana'])) ?></span>
                                    <br><small class="text-muted">(Batas Waktu)</small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($status == 'dipinjam'): ?>
                                    <span class="badge bg-warning text-dark">Sedang Dipinjam</span>
                                <?php else: ?>
                                    <span class="badge bg-success">Selesai</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($row['denda'] > 0): ?>
                                    <span class="text-danger fw-bold">Rp <?= number_format($row['denda']) ?></span>
                                <?php elseif(!$is_kembali): ?>
                                    <span class="text-muted">-</span>
                                <?php else: ?>
                                    <span class="text-success"><i class="bi bi-check-circle-fill"></i> Aman</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endwhile; else: ?>
                        <tr>
                            <td colspan="5" class="py-5 text-muted">
                                <i class="bi bi-journal-x fs-1 d-block mb-2"></i>
                                Belum ada riwayat peminjaman.
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include '../layouts/footer.php'; ?>