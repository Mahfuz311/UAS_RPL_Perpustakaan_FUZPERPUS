<?php 
include '../config/koneksi.php'; 
include '../layouts/header.php'; 

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "<script>window.location='../index.php';</script>";
    exit;
}

$tgl_awal  = isset($_GET['tgl_awal']) ? $_GET['tgl_awal'] : date('Y-m-01');
$tgl_akhir = isset($_GET['tgl_akhir']) ? $_GET['tgl_akhir'] : date('Y-m-d');
?>

<div class="container mt-4 mb-5">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-primary"><i class="bi bi-file-earmark-bar-graph-fill me-2"></i>Laporan Perpustakaan</h3>
            <p class="text-muted mb-0">Rekapitulasi data peminjaman dan pengembalian buku.</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <form action="" method="GET">
                <div class="row align-items-end g-3">
                    
                    <div class="col-md-3">
                        <label class="form-label fw-bold small text-muted">Dari Tanggal</label>
                        <input type="date" name="tgl_awal" class="form-control" value="<?= $tgl_awal ?>" required>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-bold small text-muted">Sampai Tanggal</label>
                        <input type="date" name="tgl_akhir" class="form-control" value="<?= $tgl_akhir ?>" required>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label d-block">&nbsp;</label>
                        <button type="submit" class="btn btn-primary w-100 rounded-pill shadow-sm">
                            <i class="bi bi-filter me-2"></i> Tampilkan
                        </button>
                    </div>

                    <div class="col-md-4 text-md-end">
                        <label class="form-label d-block">&nbsp;</label>
                        <div class="d-flex gap-2 justify-content-md-end">
                            <button type="submit" formaction="cetak.php" formtarget="_blank" class="btn btn-outline-danger rounded-pill px-3">
                                <i class="bi bi-printer-fill me-2"></i> PDF / Print
                            </button>
                            
                            <button type="submit" formaction="excel.php" formtarget="_blank" class="btn btn-success rounded-pill px-3 shadow-sm">
                                <i class="bi bi-file-earmark-spreadsheet-fill me-2"></i> Excel
                            </button>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white py-3">
            <h6 class="mb-0 fw-bold text-secondary">
                <i class="bi bi-table me-2"></i>Data Periode: <?= date('d M Y', strtotime($tgl_awal)) ?> s/d <?= date('d M Y', strtotime($tgl_akhir)) ?>
            </h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 text-center">
                    <thead class="bg-light">
                        <tr>
                            <th class="py-3">No</th>
                            <th class="text-start ps-4">Peminjam</th>
                            <th class="text-start">Judul Buku</th>
                            <th>Tgl Pinjam</th>
                            <th>Tgl Kembali</th>
                            <th>Status</th>
                            <th>Denda</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;

                        $query = mysqli_query($koneksi, "SELECT peminjaman.*, users.nama, buku.judul 
                                FROM peminjaman 
                                JOIN users ON peminjaman.id_user = users.id_user 
                                JOIN buku ON peminjaman.id_buku = buku.id_buku 
                                WHERE tgl_pinjam BETWEEN '$tgl_awal' AND '$tgl_akhir'
                                ORDER BY id_pinjam DESC");
                        
                        if(mysqli_num_rows($query) > 0):
                            while($row = mysqli_fetch_assoc($query)):
                        ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td class="text-start ps-4 fw-bold"><?= $row['nama'] ?></td>
                            <td class="text-start text-muted"><?= $row['judul'] ?></td>
                            <td><?= date('d/m/y', strtotime($row['tgl_pinjam'])) ?></td>
                            <td>
                                <?= $row['tgl_kembali_aktual'] ? date('d/m/y', strtotime($row['tgl_kembali_aktual'])) : '<small class="text-muted">-</small>' ?>
                            </td>
                            <td>
                                <?php if($row['status'] == 'dipinjam'): ?>
                                    <span class="badge bg-warning text-dark">Dipinjam</span>
                                <?php else: ?>
                                    <span class="badge bg-success">Kembali</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($row['denda'] > 0): ?>
                                    <span class="text-danger fw-bold">Rp <?= number_format($row['denda']) ?></span>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endwhile; else: ?>
                            <tr>
                                <td colspan="7" class="py-5 text-muted">
                                    <i class="bi bi-calendar-x fs-1 d-block mb-2"></i>
                                    Tidak ada data pada periode tanggal ini.
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
</body>
</html>