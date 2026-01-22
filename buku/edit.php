<?php
include '../config/koneksi.php';
include '../layouts/header.php';

cek_admin();
$id = $_GET['id'];

$halaman = isset($_GET['halaman']) ? $_GET['halaman'] : 1;

$query = mysqli_query($koneksi, "SELECT * FROM buku WHERE id_buku='$id'");
$data  = mysqli_fetch_assoc($query);
?>

<div class="container mt-4 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-warning text-dark py-3 rounded-top-4">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-pencil-square me-2"></i>Edit Data Buku</h5>
                </div>
                <div class="card-body p-4">
                    
                    <form action="proses.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id_buku" value="<?= $data['id_buku'] ?>">
                        <input type="hidden" name="gambar_lama" value="<?= $data['gambar'] ?>">

                        <input type="hidden" name="halaman_redirect" value="<?= $halaman ?>">
                        <h6 class="fw-bold text-primary mb-3">Informasi Umum</h6>
                        <div class="mb-3">
                            <label class="form-label">Judul Buku</label>
                            <input type="text" name="judul" class="form-control" value="<?= $data['judul'] ?>" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Pengarang</label>
                                <input type="text" name="pengarang" class="form-control" value="<?= $data['pengarang'] ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Penerbit</label>
                                <input type="text" name="penerbit" class="form-control" value="<?= $data['penerbit'] ?>" required>
                            </div>
                        </div>

                        <hr class="my-4">
                        <h6 class="fw-bold text-primary mb-3">Detail & Lokasi</h6>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tahun Terbit</label>
                                <select name="tahun_terbit" class="form-select" required>
                                    <?php for($i = date('Y'); $i >= 1900; $i--) : ?>
                                        <option value="<?= $i ?>" <?= ($i == $data['tahun_terbit']) ? 'selected' : '' ?>><?= $i ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Stok Buku</label>
                                <input type="number" name="stok" class="form-control" min="0" value="<?= $data['stok'] ?>" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Lokasi Rak</label>
                            <select name="lokasi_rak" class="form-select" required>
                                <?php
                                $rak = ['Rak IT-01', 'Rak IT-02', 'Rak Sastra', 'Rak Sejarah', 'Rak Bisnis', 'Rak Psikologi', 'Rak Umum'];
                                foreach($rak as $r) : ?>
                                    <option value="<?= $r ?>" <?= ($r == $data['lokasi_rak']) ? 'selected' : '' ?>><?= $r ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Deskripsi / Sinopsis</label>
                            <textarea name="deskripsi" class="form-control" rows="4"><?= isset($data['deskripsi']) ? $data['deskripsi'] : '' ?></textarea>
                        </div>

                        <hr class="my-4">
                        <h6 class="fw-bold text-primary mb-3">Cover Buku</h6>
                        
                        <div class="row align-items-center">
                            <div class="col-md-4 text-center mb-3 mb-md-0">
                                <label class="form-label d-block">Preview Cover Saat Ini</label>
                                <?php if ($data['gambar'] && file_exists("../assets/img/" . $data['gambar'])) : ?>
                                    <img src="../assets/img/<?= $data['gambar'] ?>" alt="Cover Lama" class="img-thumbnail shadow-sm rounded" style="height: 180px; object-fit: cover;">
                                <?php else : ?>
                                    <div class="bg-light rounded border d-flex align-items-center justify-content-center text-muted" style="height: 180px; width: 130px; margin: auto;">
                                        <div class="text-center">
                                            <i class="bi bi-image fs-1"></i><br><small>No Cover</small>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-8">
                                <label class="form-label">Ganti Cover (Opsional)</label>
                                <input type="file" name="gambar" class="form-control" accept="image/png, image/jpeg, image/jpg">
                                <div class="form-text text-muted">Format: JPG, JPEG, PNG. Maksimal 2MB.</div>
                            </div>
                        </div>

                        <div class="text-end mt-4">
                            <a href="index.php?halaman=<?= $halaman ?>" class="btn btn-light px-4 me-2 rounded-pill">Batal</a>
                            <button type="submit" name="edit_buku" class="btn btn-warning px-4 rounded-pill fw-bold">Update Data</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../layouts/footer.php'; ?>
</body>
</html>