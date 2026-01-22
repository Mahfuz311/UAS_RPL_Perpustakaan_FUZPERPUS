<?php 
include '../config/koneksi.php'; 
include '../layouts/header.php'; 

cek_admin();

$selected_user_id = "";
$pesan_scan = "";
$filter_user = ""; 

if(isset($_GET['reset'])){
    $selected_user_id = "";
    $filter_user = "";
}

if(isset($_POST['scan_nim'])) {
    $nim_scan = mysqli_real_escape_string($koneksi, $_POST['scan_nim']);
    $cek_user = mysqli_query($koneksi, "SELECT * FROM users WHERE nim = '$nim_scan' AND role='anggota'");
    
    if(mysqli_num_rows($cek_user) > 0) {
        $data_scan = mysqli_fetch_assoc($cek_user);
        $selected_user_id = $data_scan['id_user'];
        $filter_user = " AND peminjaman.id_user = '$selected_user_id' "; 
        
        $pesan_scan = "
        <div class='alert alert-success alert-dismissible fade show py-2 mb-3 small' role='alert'>
            <div class='d-flex align-items-center justify-content-between'>
                <div><i class='bi bi-check-circle-fill me-2'></i>Anggota Ditemukan: <b>".$data_scan['nama']."</b></div>
                <a href='index.php?reset=true' class='btn btn-sm btn-outline-success ms-3'>Reset Filter</a>
            </div>
        </div>";
    } else {
        $pesan_scan = "<div class='alert alert-danger py-2 mb-3 small'><i class='bi bi-x-circle-fill me-2'></i>Anggota dengan NIM <b>$nim_scan</b> tidak ditemukan!</div>";
    }
}

$buku = mysqli_query($koneksi, "SELECT * FROM buku WHERE stok > 0 ORDER BY judul ASC");
$anggota = mysqli_query($koneksi, "SELECT * FROM users WHERE role='anggota' ORDER BY nama ASC");
?>

<div class="container mt-4 mb-5">
    
    <div class="row g-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 mb-3 bg-primary text-white">
                <div class="card-body p-3">
                    <label class="fw-bold small mb-2"><i class="bi bi-qr-code-scan me-1"></i> Scan Kartu / Ketik NIM</label>
                    <form action="" method="POST">
                        <div class="input-group">
                            <input type="number" name="scan_nim" class="form-control rounded-start-pill border-0" placeholder="Scan disini..." autofocus autocomplete="off">
                            <button class="btn btn-light rounded-end-pill px-3" type="submit"><i class="bi bi-search"></i></button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold text-primary">Form Peminjaman Baru</h6>
                </div>
                <div class="card-body p-4">
                    <?= $pesan_scan ?>
                    <form action="fungsi.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">Nama Peminjam</label>
                            <select name="id_user" class="form-select" required>
                                <option value="">-- Pilih Anggota --</option>
                                <?php while($u = mysqli_fetch_assoc($anggota)): ?>
                                    <option value="<?= $u['id_user'] ?>" <?= ($u['id_user'] == $selected_user_id) ? 'selected' : '' ?>>
                                        <?= $u['nama'] ?> (<?= $u['nim'] ?>)
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">Pilih Buku</label>
                            <select name="id_buku" class="form-select" required>
                                <option value="">-- Pilih Buku --</option>
                                <?php while($b = mysqli_fetch_assoc($buku)): ?>
                                    <option value="<?= $b['id_buku'] ?>"><?= $b['judul'] ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">Lama Pinjam</label>
                            <select name="lama_pinjam" class="form-select" required>
                                <?php for($i = 1; $i <= 7; $i++) : ?>
                                    <option value="<?= $i ?>" <?= ($i == 7) ? 'selected' : '' ?>><?= $i ?> Hari</option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <button type="submit" name="btn_pinjam" class="btn btn-success w-100 rounded-pill fw-bold">Proses Pinjam</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold text-primary">Peminjaman Aktif (Sedang Dipinjam)</h6>
                    <?php if(!empty($selected_user_id)): ?><span class="badge bg-warning text-dark">Filter Aktif</span><?php endif; ?>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 text-center">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-3 text-start">Peminjam / Buku</th>
                                    <th>Jatuh Tempo</th>
                                    <th>Status / Denda</th>
                                    <th class="pe-3">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $query_str = "SELECT peminjaman.*, users.nama, buku.judul, buku.gambar 
                                        FROM peminjaman 
                                        JOIN users ON peminjaman.id_user = users.id_user 
                                        JOIN buku ON peminjaman.id_buku = buku.id_buku 
                                        WHERE peminjaman.status = 'dipinjam' $filter_user 
                                        ORDER BY peminjaman.tgl_pinjam DESC";
                                $query = mysqli_query($koneksi, $query_str) or die(mysqli_error($koneksi));
                                
                                if(mysqli_num_rows($query) > 0):
                                    while($row = mysqli_fetch_assoc($query)): 
                                        $tgl_now = new DateTime(); 
                                        $tgl_kem = new DateTime($row['tgl_kembali_rencana']);
                                        $is_telat = $tgl_now > $tgl_kem;
                                        $selisih  = $tgl_now->diff($tgl_kem)->days;
                                        $denda_est = $is_telat ? ($selisih * 1000) : 0;
                                ?>
                                <tr>
                                    <td class="ps-3 text-start">
                                        <div class="fw-bold"><?= $row['nama'] ?></div>
                                        <div class="small text-muted"><?= $row['judul'] ?></div>
                                    </td>
                                    <td class="<?= $is_telat ? 'text-danger fw-bold' : '' ?>">
                                        <?= date('d/m/y', strtotime($row['tgl_kembali_rencana'])) ?>
                                    </td>
                                    <td>
                                        <?php if($is_telat): ?>
                                            <span class="badge bg-danger">Telat <?= $selisih ?> Hari</span>
                                            <div class="text-danger small fw-bold">+ Rp <?= number_format($denda_est) ?></div>
                                        <?php else: ?>
                                            <span class="badge bg-success">Aman</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="pe-3">
                                        <a href="fungsi.php?aksi=kembali&id=<?= $row['id_pinjam'] ?>&buku=<?= $row['id_buku'] ?>" 
                                           class="btn btn-warning btn-sm rounded-pill px-3 fw-bold shadow-sm"
                                           onclick="return confirm('Kembalikan buku ini?')">Kembalikan</a>
                                    </td>
                                </tr>
                                <?php endwhile; else: ?>
                                <tr><td colspan="4" class="py-5 text-muted">Tidak ada transaksi aktif.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card border-0 shadow-sm rounded-4 mt-5">
        <div class="card-header bg-white py-3">
            <h6 class="mb-0 fw-bold text-secondary">Riwayat Pengembalian & Pelunasan Denda</h6>
        </div>
        <div class="card-body p-0">
             <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 text-center">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 text-start">Peminjam / Buku</th>
                            <th>Tgl Kembali</th>
                            <th>Info Denda</th>
                            <th>Status Tagihan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $riwayat = mysqli_query($koneksi, "SELECT peminjaman.*, users.nama, buku.judul 
                                FROM peminjaman 
                                JOIN users ON peminjaman.id_user = users.id_user 
                                JOIN buku ON peminjaman.id_buku = buku.id_buku 
                                WHERE peminjaman.status = 'kembali' 
                                ORDER BY tgl_kembali_aktual DESC LIMIT 10");
                        
                        while($r = mysqli_fetch_assoc($riwayat)):
                        ?>
                        <tr>
                            <td class="ps-4 text-start">
                                <span class="fw-bold d-block"><?= $r['nama'] ?></span>
                                <span class="small text-muted"><?= $r['judul'] ?></span>
                            </td>
                            <td><?= date('d M Y', strtotime($r['tgl_kembali_aktual'])) ?></td>
                            <td>
                                <?php if($r['denda'] > 0): ?>
                                    <span class="text-danger fw-bold">Rp <?= number_format($r['denda']) ?></span>
                                <?php else: ?>
                                    <span class="text-muted small">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($r['denda'] > 0): ?>
                                    <a href="fungsi.php?aksi=lunasi&id=<?= $r['id_pinjam'] ?>" 
                                       class="btn btn-outline-success btn-sm rounded-pill px-3"
                                       onclick="return confirm('Yakin ingin melunasi/menghapus denda user ini?')">
                                        <i class="bi bi-cash-stack me-1"></i> Lunasi Sekarang
                                    </a>
                                <?php else: ?>
                                    <span class="badge bg-success bg-opacity-10 text-success"><i class="bi bi-check-circle me-1"></i> Lunas / Bersih</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
             </div>
        </div>
    </div>
</div>
<?php include '../layouts/footer.php'; ?>