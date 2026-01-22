<?php 
include '../config/koneksi.php';
session_start();

if(isset($_POST['tambah_user'])){

    $nim = $_POST['nim'];
    $nama = $_POST['nama'];
    $jurusan = $_POST['jurusan'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    $cek = mysqli_query($koneksi, "SELECT username FROM users WHERE username='$username'");
    if(mysqli_num_rows($cek) > 0){
        $_SESSION['info'] = ['status' => 'error', 'pesan' => 'Username sudah dipakai!'];
        header("Location: tambah.php"); exit;
    }

    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    $query = "INSERT INTO users (nim, nama, jurusan, username, password, role) VALUES ('$nim', '$nama', '$jurusan', '$username', '$password_hash', '$role')";

    if(mysqli_query($koneksi, $query)){
        $_SESSION['info'] = ['status' => 'success', 'pesan' => 'Anggota Berhasil Ditambahkan!'];
    } else {
        $_SESSION['info'] = ['status' => 'error', 'pesan' => 'Gagal menambah data'];
    }
    header("Location: index.php"); exit;
}

if(isset($_GET['hapus_id'])){
    $id = $_GET['hapus_id'];
    
    $cek_pinjam = mysqli_query($koneksi, "SELECT * FROM peminjaman WHERE id_user='$id' AND status='dipinjam'");
    if(mysqli_num_rows($cek_pinjam) > 0){
        $_SESSION['info'] = ['status' => 'error', 'pesan' => 'Gagal! User ini masih meminjam buku.'];
        header("Location: index.php"); exit;
    }

    $query = "DELETE FROM users WHERE id_user='$id'";
    if(mysqli_query($koneksi, $query)){
        $_SESSION['info'] = ['status' => 'success', 'pesan' => 'Data Anggota Dihapus'];
    } else {
        $_SESSION['info'] = ['status' => 'error', 'pesan' => 'Gagal hapus data'];
    }
    header("Location: index.php"); exit;
}

if(isset($_POST['hapus_admin_secure'])){
    $id_target = $_POST['id_target'];
    $pass_input = $_POST['password_konfirmasi'];
    $id_saya = $_SESSION['id_user']; 

    $query_saya = mysqli_query($koneksi, "SELECT password FROM users WHERE id_user='$id_saya'");
    $data_saya = mysqli_fetch_assoc($query_saya);

    if(password_verify($pass_input, $data_saya['password'])){
        
        $hapus = mysqli_query($koneksi, "DELETE FROM users WHERE id_user='$id_target'");
        
        if($hapus){
            $_SESSION['info'] = ['status' => 'success', 'pesan' => 'Admin berhasil dihapus!'];
        } else {
            $_SESSION['info'] = ['status' => 'error', 'pesan' => 'Gagal menghapus database.'];
        }

    } else {
        $_SESSION['info'] = ['status' => 'error', 'pesan' => 'Password Salah! Penghapusan dibatalkan.'];
    }
    
    header("Location: index.php"); exit;
}

if(isset($_GET['reset_id'])){
    $id = $_GET['reset_id'];
    $pass_default = password_hash("123456", PASSWORD_DEFAULT);
    $query = "UPDATE users SET password='$pass_default' WHERE id_user='$id'";
    
    if(mysqli_query($koneksi, $query)){
        $_SESSION['info'] = ['status' => 'success', 'pesan' => 'Password direset jadi: 123456'];
    } else {
        $_SESSION['info'] = ['status' => 'error', 'pesan' => 'Gagal reset password'];
    }
    header("Location: index.php"); exit;
}

if(isset($_POST['edit_user'])){
    $id = $_POST['id_user'];
    $nim = $_POST['nim'];
    $nama = $_POST['nama'];
    $jurusan = $_POST['jurusan'];
    $username = $_POST['username'];
    $role = $_POST['role'];

    $cek = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username' AND id_user != '$id'");
    
    if(mysqli_num_rows($cek) > 0){
        $_SESSION['info'] = ['status' => 'error', 'pesan' => 'Username sudah digunakan orang lain!'];
        header("Location: edit.php?id=$id"); 
        exit;
    }

    $query = "UPDATE users SET 
                nim='$nim', 
                nama='$nama', 
                jurusan='$jurusan', 
                username='$username', 
                role='$role' 
              WHERE id_user='$id'";

    if(mysqli_query($koneksi, $query)){
        if(isset($_SESSION['id_user']) && $_SESSION['id_user'] == $id){
            $_SESSION['nama'] = $nama;
            $_SESSION['role'] = $role;
        }
        $_SESSION['info'] = ['status' => 'success', 'pesan' => 'Data User Berhasil Diupdate!'];
    } else {
        $_SESSION['info'] = ['status' => 'error', 'pesan' => 'Gagal Update Database'];
    }
    
    header("Location: index.php");
    exit;
}
?>