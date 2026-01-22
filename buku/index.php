<?php 
include '../config/koneksi.php'; 
include '../layouts/header.php'; 

$batas = 8;
$halaman = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;
$halaman_awal = ($halaman > 1) ? ($halaman * $batas) - $batas : 0;

$where = "";
$cari_url = "";
$keyword = "";

if (isset($_GET['cari'])) {
    $keyword = $_GET['cari'];
    $where = " WHERE judul LIKE '%$keyword%' OR pengarang LIKE '%$keyword%' OR penerbit LIKE '%$keyword%'";
    $cari_url = "&cari=" . $keyword;
}

$query_jml = mysqli_query($koneksi, "SELECT id_buku FROM buku $where");
$total_data = mysqli_num_rows($query_jml);
$total_halaman = ceil($total_data / $batas);

$query = mysqli_query($koneksi, "SELECT * FROM buku $where ORDER BY id_buku DESC LIMIT $halaman_awal, $batas");
?>

<style>
    .card-book { transition: transform 0.3s, box-shadow 0.3s; border: none; border-radius: 15px; overflow: hidden; }
    .card-book:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.15) !important; }
    
    .book-cover-container { cursor: pointer; position: relative; overflow: hidden; }
    
    .book-cover { 
        height: 320px; 
        object-fit: contain; 
        width: 100%; 
        border-bottom: 1px solid #eee; 
        transition: transform 0.3s; 
        background-color: #f8f9fa;
    }
    
    .book-cover-container:hover .book-cover { transform: scale(1.05); }
    
    .hover-overlay {
        position: absolute; top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0,0,0,0.3); opacity: 0; transition: opacity 0.3s;
        display: flex; align-items: center; justify-content: center;
    }
    .book-cover-container:hover .hover-overlay { opacity: 1; }

    .badge-stock { position: absolute; top: 15px; right: 15px; font-size: 0.85rem; padding: 8px 12px; border-radius: 30px; box-shadow: 0 2px 5px rgba(0,0,0,0.2); z-index: 2;}
    .book-title { font-size: 1.1rem; font-weight: 700; color: #333; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; height: 2.8em; }
</style>

<div class="container mt-4 mb-5">
    
    <div class="row align-items-center mb-4 g-3">
        <div class="col-md-6">
            <h3 class="fw-bold text-primary mb-0">📚 Katalog Buku</h3>
            <p class="text-muted mb-0">Menampilkan <?= mysqli_num_rows($query) ?> dari total <?= $total_data ?> buku.</p>
        </div>
        <div class="col-md-6">
            <div class="d-flex gap-2 justify-content-md-end">
                <form action="" method="GET" class="d-flex w-100" style="max-width: 400px;">
                    <input type="text" name="cari" class="form-control rounded-start-pill ps-4" placeholder="Cari Judul..." value="<?= $keyword ?>" autocomplete="off">
                    <button type="submit" class="btn btn-primary rounded-end-pill px-4"><i class="bi bi-search"></i></button>
                </form>
                <?php if(isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
                    <a href="tambah.php" class="btn btn-success rounded-pill px-3 shadow-sm d-flex align-items-center" title="Tambah Buku"><i class="bi bi-plus-lg"></i></a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <?php if(mysqli_num_rows($query) > 0): while ($row = mysqli_fetch_assoc($query)) : ?>
            
            <div class="col-6 col-md-4 col-lg-3">
                <div class="card card-book shadow-sm h-100">
                    
                    <?php if($row['stok'] > 0): ?>
                        <span class="badge bg-success badge-stock">Stok: <?= $row['stok'] ?></span>
                    <?php else: ?>
                        <span class="badge bg-secondary badge-stock">Habis</span>
                    <?php endif; ?>

                    <div class="book-cover-container bg-light" data-bs-toggle="modal" data-bs-target="#modalDetail<?= $row['id_buku'] ?>">
                        <?php if($row['gambar'] && file_exists("../assets/img/" . $row['gambar'])): ?>
                            <img src="../assets/img/<?= $row['gambar'] ?>" class="book-cover" alt="Cover">
                        <?php else: ?>
                            <div class="book-cover d-flex align-items-center justify-content-center text-muted bg-light">
                                <div class="text-center"><i class="bi bi-book fs-1"></i><br><small>No Cover</small></div>
                            </div>
                        <?php endif; ?>
                        
                        <div class="hover-overlay text-white">
                            <span class="badge bg-dark bg-opacity-75 px-3 py-2 rounded-pill"><i class="bi bi-eye-fill me-1"></i> Lihat Detail</span>
                        </div>
                    </div>

                    <div class="card-body d-flex flex-column">
                        <small class="text-muted text-uppercase fw-bold mb-1" style="font-size: 0.7rem;"><?= $row['lokasi_rak'] ?></small>
                        
                        <a href="#" class="text-decoration-none" data-bs-toggle="modal" data-bs-target="#modalDetail<?= $row['id_buku'] ?>">
                            <h5 class="book-title mb-2"><?= $row['judul'] ?></h5>
                        </a>

                        <p class="text-muted small mb-3">
                            <i class="bi bi-person me-1"></i> <?= $row['pengarang'] ?><br>
                            <i class="bi bi-calendar me-1"></i> <?= $row['tahun_terbit'] ?>
                        </p>

                        <div class="mt-auto">
                            <?php if($_SESSION['role'] == 'admin'): ?>
                                <div class="d-grid gap-2">
                                    <a href="edit.php?id=<?= $row['id_buku'] ?>&halaman=<?= $halaman ?>" class="btn btn-outline-warning btn-sm">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <a href="proses.php?hapus_id=<?= $row['id_buku'] ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Hapus?')">
                                        <i class="bi bi-trash"></i> Hapus
                                    </a>
                                </div>
                            <?php elseif($_SESSION['role'] == 'anggota'): ?>
                                <?php if($row['stok'] > 0): ?>
                                    <button type="button" class="btn btn-primary w-100 rounded-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#modalPinjam<?= $row['id_buku'] ?>">Pinjam</button>
                                <?php else: ?>
                                    <button class="btn btn-secondary w-100 rounded-pill" disabled>Stok Habis</button>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="modalDetail<?= $row['id_buku'] ?>" tabindex="-1">
                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content border-0 rounded-4 shadow overflow-hidden">
                        <div class="modal-header border-0 bg-light">
                            <h5 class="modal-title fw-bold text-primary"><i class="bi bi-info-circle-fill me-2"></i>Detail Buku</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body p-0">
                            <div class="row g-0">
                                <div class="col-md-5 bg-light d-flex align-items-center justify-content-center p-4">
                                    <?php if($row['gambar'] && file_exists("../assets/img/" . $row['gambar'])): ?>
                                        <img src="../assets/img/<?= $row['gambar'] ?>" class="img-fluid rounded shadow" style="max-height: 350px;">
                                    <?php else: ?>
                                        <div class="text-center text-muted py-5"><i class="bi bi-book" style="font-size: 5rem;"></i><br>No Cover</div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-7 p-4">
                                    <h4 class="fw-bold text-dark mb-1"><?= $row['judul'] ?></h4>
                                    <p class="text-muted mb-3"><span class="badge bg-warning text-dark"><?= $row['lokasi_rak'] ?></span></p>
                                    
                                    <table class="table table-sm table-borderless small mb-3">
                                        <tr><td class="text-muted" width="100">Pengarang</td><td class="fw-bold">: <?= $row['pengarang'] ?></td></tr>
                                        <tr><td class="text-muted">Penerbit</td><td class="fw-bold">: <?= $row['penerbit'] ?></td></tr>
                                        <tr><td class="text-muted">Tahun</td><td class="fw-bold">: <?= $row['tahun_terbit'] ?></td></tr>
                                        <tr><td class="text-muted">Sisa Stok</td><td class="fw-bold text-primary">: <?= $row['stok'] ?> pcs</td></tr>
                                    </table>

                                    <hr>
                                    <h6 class="fw-bold text-secondary">Sinopsis / Deskripsi:</h6>
                                    <p class="text-muted small" style="text-align: justify; line-height: 1.6;">
                                        <?= $row['deskripsi'] ? nl2br($row['deskripsi']) : '<i>Belum ada deskripsi untuk buku ini.</i>' ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer border-0 bg-light">
                            <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Tutup</button>
                            <?php if($_SESSION['role'] == 'anggota' && $row['stok'] > 0): ?>
                                <button class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#modalPinjam<?= $row['id_buku'] ?>">Pinjam Sekarang</button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <?php if(isset($_SESSION['role']) && $_SESSION['role'] == 'anggota'): ?>
            <div class="modal fade" id="modalPinjam<?= $row['id_buku'] ?>" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title">Konfirmasi Peminjaman</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="../transaksi/fungsi.php" method="POST">
                            <div class="modal-body text-center p-4">
                                <?php if($row['gambar']): ?>
                                    <img src="../assets/img/<?= $row['gambar'] ?>" class="rounded mb-3 shadow-sm" style="height: 120px;">
                                <?php endif; ?>
                                <h5 class="fw-bold"><?= $row['judul'] ?></h5>
                                <div class="form-group text-start bg-light p-3 rounded-3 border mt-3">
                                    <label class="fw-bold mb-2 small text-muted">Mau pinjam berapa lama?</label>
                                    <select name="lama_pinjam" class="form-select border-primary" required>
                                        <?php for($i = 1; $i <= 7; $i++) : ?>
                                            <option value="<?= $i ?>" <?= ($i == 7) ? 'selected' : '' ?>>
                                                <?= $i ?> Hari <?= ($i == 7) ? '(Maksimal)' : '' ?>
                                            </option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                                <input type="hidden" name="id_buku" value="<?= $row['id_buku'] ?>">
                                <input type="hidden" name="id_user" value="<?= $_SESSION['id_user'] ?>">
                            </div>
                            <div class="modal-footer justify-content-center border-0 pb-4">
                                <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" name="btn_pinjam_user" class="btn btn-primary px-4">Pinjam Sekarang</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <?php endif; ?>

        <?php endwhile; else: ?>
            <div class="col-12 text-center py-5">
                <h5 class="text-muted">Buku tidak ditemukan.</h5>
                <a href="index.php" class="btn btn-outline-primary btn-sm mt-2">Reset</a>
            </div>
        <?php endif; ?>
    </div>

    <?php if($total_halaman > 1): ?>
    <nav class="mt-5 d-flex justify-content-center">
        <ul class="pagination">
            <li class="page-item <?= ($halaman <= 1) ? 'disabled' : '' ?>">
                <a class="page-link" href="?halaman=<?= $halaman - 1 ?><?= $cari_url ?>">Previous</a>
            </li>
            <?php for($x = 1; $x <= $total_halaman; $x++): ?>
                <li class="page-item <?= ($halaman == $x) ? 'active' : '' ?>">
                    <a class="page-link" href="?halaman=<?= $x ?><?= $cari_url ?>"><?= $x ?></a>
                </li>
            <?php endfor; ?>
            <li class="page-item <?= ($halaman >= $total_halaman) ? 'disabled' : '' ?>">
                <a class="page-link" href="?halaman=<?= $halaman + 1 ?><?= $cari_url ?>">Next</a>
            </li>
        </ul>
    </nav>
    <?php endif; ?>
</div>

<?php include '../layouts/footer.php'; ?>
</body>
</html>