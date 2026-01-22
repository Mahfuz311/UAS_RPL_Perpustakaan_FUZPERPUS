<?php
include 'config/koneksi.php';
include 'layouts/header.php';

if (!isset($_SESSION['login'])) {
    header("Location: auth/login.php");
    exit;
}

$id = $_SESSION['id_user'];

if (isset($_POST['update_profil'])) {
    $nama = $_POST['nama'];
    $jurusan = $_POST['jurusan'];
    
    $password_baru = $_POST['password_baru'];
    $query_update = "";

    if (!empty($password_baru)) {
        if (strlen($password_baru) < 6) {
            $_SESSION['info'] = ['status' => 'error', 'pesan' => 'Password minimal 6 karakter!'];
            header("Location: profil.php");
            exit;
        }
        $hash = password_hash($password_baru, PASSWORD_DEFAULT);
        $query_update = "UPDATE users SET nama='$nama', jurusan='$jurusan', password='$hash' WHERE id_user='$id'";
        $pesan_sukses = "Profil & Password Berhasil Diupdate!";
    } else {
        $query_update = "UPDATE users SET nama='$nama', jurusan='$jurusan' WHERE id_user='$id'";
        $pesan_sukses = "Data Profil Berhasil Diupdate!";
    }

    if (mysqli_query($koneksi, $query_update)) {
        $_SESSION['nama'] = $nama;
        $_SESSION['info'] = ['status' => 'success', 'pesan' => $pesan_sukses];
    } else {
        $_SESSION['info'] = ['status' => 'error', 'pesan' => 'Gagal Update Profil'];
    }
    header("Location: profil.php");
    exit;
}

$query = mysqli_query($koneksi, "SELECT * FROM users WHERE id_user='$id'");
$data = mysqli_fetch_assoc($query);
?>

<div class="container mt-4 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="card-header bg-primary text-white text-center py-4 position-relative">
                    <div class="bg-white rounded-circle d-inline-flex align-items-center justify-content-center shadow-sm" style="width: 80px; height: 80px; border: 4px solid rgba(255,255,255,0.3);">
                        <span class="fs-1 fw-bold text-primary"><?= substr($data['nama'], 0, 1) ?></span>
                    </div>
                    <h5 class="mt-2 mb-0 fw-bold"><?= $data['nama'] ?></h5>
                    <p class="mb-0 small opacity-75"><?= $data['role'] == 'admin' ? 'Administrator' : 'Anggota Perpustakaan' ?></p>
                </div>
                
                <div class="card-body p-4">
                    <form action="" method="POST">
                        
                        <h6 class="fw-bold text-primary mb-3"><i class="bi bi-person-lines-fill me-2"></i>Identitas Diri</h6>
                        
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">NIM / Username (Tidak bisa diubah)</label>
                            <input type="text" class="form-control bg-light" value="<?= $data['username'] ?> / <?= $data['nim'] ?>" disabled>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control" value="<?= $data['nama'] ?>" required>
                        </div>

                        <?php if ($data['role'] == 'anggota') : ?>
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">Jurusan</label>
                            <input type="text" name="jurusan" class="form-control" value="<?= $data['jurusan'] ?>" required>
                        </div>
                        <?php else: ?>
                            <input type="hidden" name="jurusan" value="-">
                        <?php endif; ?>

                        <hr class="my-4">
                        <h6 class="fw-bold text-danger mb-3"><i class="bi bi-key-fill me-2"></i>Ganti Password</h6>
                        <div class="alert alert-warning small py-2">
                            <i class="bi bi-exclamation-circle me-1"></i> Kosongkan jika tidak ingin mengganti password.
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">Password Baru</label>
                            <input type="password" name="password_baru" class="form-control" placeholder="Buat password baru..." minlength="6">
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" name="update_profil" class="btn btn-primary rounded-pill fw-bold py-2">
                                <i class="bi bi-save me-2"></i> Simpan Perubahan
                            </button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<?php include 'layouts/footer.php'; ?>