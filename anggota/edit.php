<?php 
include '../config/koneksi.php'; 
include '../layouts/header.php'; 

cek_admin();

$id = $_GET['id'];
$query = mysqli_query($koneksi, "SELECT * FROM users WHERE id_user='$id'");
$data = mysqli_fetch_assoc($query);

if(!$data){
    header("Location: index.php"); exit;
}
?>

<div class="container mt-4 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold text-primary">Edit Data Anggota</h5>
                </div>
                <div class="card-body p-4">
                    <form action="proses.php" method="POST">
                        <input type="hidden" name="id_user" value="<?= $data['id_user'] ?>">
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">NIM / NIP</label>
                            <input type="number" name="nim" class="form-control" value="<?= $data['nim'] ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control" value="<?= $data['nama'] ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">Jurusan</label>
                            <input type="text" name="jurusan" class="form-control" value="<?= $data['jurusan'] ?>">
                            <div class="form-text">Kosongkan jika Admin / Petugas.</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">Username</label>
                            <input type="text" name="username" class="form-control" value="<?= $data['username'] ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">Role (Hak Akses)</label>
                            <select name="role" class="form-select" required>
                                <option value="anggota" <?= $data['role'] == 'anggota' ? 'selected' : '' ?>>Anggota (Peminjam)</option>
                                <option value="admin" <?= $data['role'] == 'admin' ? 'selected' : '' ?>>Admin (Pengelola)</option>
                            </select>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="index.php" class="btn btn-light rounded-pill px-4">Batal</a>
                            <button type="submit" name="edit_user" class="btn btn-primary rounded-pill px-4 fw-bold">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../layouts/footer.php'; ?>