<?php 
include '../config/koneksi.php'; 
include '../layouts/header.php'; 

cek_admin(); 

$query = mysqli_query($koneksi, "SELECT * FROM users ORDER BY id_user DESC");
?>

<div class="container mt-4 mb-5">
    <div class="card border-0 shadow-sm rounded-4">
        
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold text-primary"><i class="bi bi-people-fill me-2"></i>Data Anggota</h5>
            <a href="tambah.php" class="btn btn-primary rounded-pill px-4 shadow-sm">
                <i class="bi bi-plus-lg me-2"></i>Tambah Anggota
            </a>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 text-center">
                    <thead class="bg-light">
                        <tr>
                            <th class="py-3">No</th>
                            <th class="text-start">Nama Lengkap</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no=1; while($row = mysqli_fetch_assoc($query)): 
                            $is_me    = ($row['id_user'] == $_SESSION['id_user']);
                            $is_admin = ($row['role'] == 'admin');
                        ?>
                        <tr class="<?= $is_me ? 'table-warning' : '' ?>"> 
                            <td><?= $no++ ?></td>
                            
                            <td class="text-start">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                        <span class="fw-bold"><?= substr($row['nama'], 0, 1) ?></span>
                                    </div>
                                    <div>
                                        <span class="d-block fw-bold">
                                            <?= $row['nama'] ?> 
                                            <?= $is_me ? '<span class="badge bg-primary ms-1" style="font-size:0.6rem">Saya</span>' : '' ?>
                                        </span>
                                        <small class="text-muted"><?= $row['nim'] ? $row['nim'] : '-' ?></small>
                                    </div>
                                </div>
                            </td>
                            
                            <td><span class="badge bg-light text-dark border"><?= $row['username'] ?></span></td>
                            
                            <td>
                                <span class="badge bg-<?= $is_admin ? 'danger' : 'success' ?>"><?= ucfirst($row['role']) ?></span>
                            </td>

                            <td>
                                <?php if($is_me || !$is_admin): ?>
                                    <a href="proses.php?reset_id=<?= $row['id_user'] ?>" class="btn btn-outline-dark btn-sm" title="Reset Password" onclick="return confirm('Reset password user ini menjadi 123456?')">
                                        <i class="bi bi-key"></i>
                                    </a>
                                <?php endif; ?>

                                <?php if($is_me || !$is_admin): ?>
                                    <a href="edit.php?id=<?= $row['id_user'] ?>" class="btn btn-warning btn-sm text-white" title="Edit Data">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                <?php endif; ?>
                                
                                <?php if(!$is_admin): ?>
                                    <a href="proses.php?hapus_id=<?= $row['id_user'] ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Yakin hapus anggota ini?')">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                <?php endif; ?>

                                <?php if(!$is_me && $is_admin): ?>
                                    <span class="text-muted small fst-italic"><i class="bi bi-shield-lock"></i> Terkunci</span>
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