<form action="proses.php" method="POST" enctype="multipart/form-data">
    
    <div class="mb-4">
        <h6 class="text-primary border-bottom pb-2">Informasi Umum</h6>
        
        <div class="mb-3">
            <label class="form-label fw-bold">Cover Buku</label>
            <input type="file" name="foto" class="form-control" accept=".jpg, .jpeg, .png">
            <small class="text-muted">Format: JPG/PNG, Maks 2MB</small>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Judul Buku</label>
            <input type="text" name="judul" class="form-control form-control-lg" placeholder="Masukkan Judul Lengkap..." required>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">ISBN</label>
                <input type="text" name="isbn" class="form-control" placeholder="Contoh: 978-602-xxxxx">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Pengarang</label>
                <input type="text" name="pengarang" class="form-control" required>
            </div>
        </div>
    </div>

    <div class="mb-4">
        <h6 class="text-primary border-bottom pb-2">Detail Penerbitan & Lokasi</h6>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Penerbit</label>
                <input type="text" name="penerbit" class="form-control">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Tahun Terbit</label>
                <select name="tahun_terbit" class="form-select">
                    <?php
                    $tahun_sekarang = date('Y');
                    for ($t = $tahun_sekarang; $t >= 1990; $t--) {
                        echo "<option value='$t'>$t</option>";
                    }
                    ?>
                </select>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Stok Buku</label>
                <input type="number" name="stok" class="form-control" value="1" min="0" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Lokasi Rak</label>
                <input type="text" name="lokasi_rak" class="form-control" placeholder="Contoh: Rak A1">
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end gap-2 mt-4">
        <a href="index.php" class="btn btn-light border px-4">Batal</a>
        <button type="submit" name="simpan_buku" class="btn btn-primary px-4 fw-bold">
            Simpan Data
        </button>
    </div>

</form>