<?php 
include '../config/koneksi.php'; 
include '../layouts/header.php'; 

cek_admin();
?>

<div class="container mt-4 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-primary text-white py-3 rounded-top-4">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-person-plus-fill me-2"></i>Tambah Anggota Baru</h5>
                </div>
                <div class="card-body p-4">
                    
                    <form action="proses.php" method="POST">
                        
                        <h6 class="fw-bold text-primary mb-3">Data Pribadi</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">NIM / NIP</label>
                                <input type="number" name="nim" class="form-control" placeholder="Contoh: 3124..." required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" name="nama" class="form-control" placeholder="Masukkan Nama Lengkap" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Jurusan / Divisi</label>
                            <input type="text" name="jurusan" class="form-control" placeholder="Contoh: Teknik Sipil, Administrasi, Dosen, dll" required>
                        </div>

                        <hr class="my-4">
                        <h6 class="fw-bold text-primary mb-3">Akun Login</h6>

                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" class="form-control" placeholder="Buat username unik" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter" minlength="6" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Role (Hak Akses)</label>
                            <select name="role" class="form-select" required>
                                <option value="anggota">Anggota (Peminjam)</option>
                                <option value="admin">Admin (Pengelola)</option>
                            </select>
                            <div class="form-text text-muted">Hati-hati memberikan akses Admin.</div>
                        </div>

                        <div class="text-end mt-4">
                            <a href="index.php" class="btn btn-light px-4 me-2 rounded-pill">Batal</a>
                            <button type="submit" name="tambah_user" class="btn btn-primary px-4 rounded-pill fw-bold">Simpan Data</button>
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