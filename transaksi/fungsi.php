<?php
include '../config/koneksi.php';
session_start();

if(isset($_POST['btn_pinjam'])){
    $id_user = $_POST['id_user'];
    $id_buku = $_POST['id_buku'];
    $lama_pinjam = $_POST['lama_pinjam'];

    $cek_stok = mysqli_query($koneksi, "SELECT stok FROM buku WHERE id_buku='$id_buku'");
    $stok = mysqli_fetch_assoc($cek_stok)['stok'];

    if($stok > 0){
        $tgl_pinjam = date('Y-m-d');
        $tgl_kembali_rencana = date('Y-m-d', strtotime("+$lama_pinjam days"));

        $query = "INSERT INTO peminjaman (id_user, id_buku, tgl_pinjam, tgl_kembali_rencana, status) 
                  VALUES ('$id_user', '$id_buku', '$tgl_pinjam', '$tgl_kembali_rencana', 'dipinjam')";
        
        mysqli_query($koneksi, "UPDATE buku SET stok = stok - 1 WHERE id_buku='$id_buku'");
        
        if(mysqli_query($koneksi, $query)){
            $_SESSION['info'] = ['status' => 'success', 'pesan' => 'Berhasil meminjam buku!'];
        } else {
            $_SESSION['info'] = ['status' => 'error', 'pesan' => 'Gagal meminjam buku.'];
        }
    } else {
        $_SESSION['info'] = ['status' => 'error', 'pesan' => 'Stok buku habis!'];
    }
    header("Location: index.php"); exit;
}

if(isset($_GET['aksi']) && $_GET['aksi'] == 'kembali'){
    $id = $_GET['id'];
    $id_buku = $_GET['buku'];
    
    $cek = mysqli_query($koneksi, "SELECT tgl_kembali_rencana FROM peminjaman WHERE id_pinjam='$id'");
    $data = mysqli_fetch_assoc($cek);
    
    $tgl_sekarang = date('Y-m-d');
    $tgl_rencana = $data['tgl_kembali_rencana'];
    
    $denda = 0;
    if($tgl_sekarang > $tgl_rencana){
        $datetime1 = new DateTime($tgl_sekarang);
        $datetime2 = new DateTime($tgl_rencana);
        $interval = $datetime1->diff($datetime2);
        $hari_telat = $interval->days;
        $denda = $hari_telat * 1000;
    }

    $query = "UPDATE peminjaman SET 
              status='kembali', 
              tgl_kembali_aktual='$tgl_sekarang', 
              denda='$denda' 
              WHERE id_pinjam='$id'";

    mysqli_query($koneksi, "UPDATE buku SET stok = stok + 1 WHERE id_buku='$id_buku'");

    if(mysqli_query($koneksi, $query)){
        $pesan = "Buku dikembalikan.";
        if($denda > 0) $pesan .= " User terkena denda Rp " . number_format($denda);
        $_SESSION['info'] = ['status' => 'success', 'pesan' => $pesan];
    } else {
        $_SESSION['info'] = ['status' => 'error', 'pesan' => 'Gagal mengembalikan buku.'];
    }
    header("Location: index.php"); exit;
}

if(isset($_GET['aksi']) && $_GET['aksi'] == 'lunasi'){
    $id = $_GET['id'];
    
    $query = "UPDATE peminjaman SET denda=0 WHERE id_pinjam='$id'";
    
    if(mysqli_query($koneksi, $query)){
        $_SESSION['info'] = ['status' => 'success', 'pesan' => 'Denda berhasil dilunasi! Tagihan user bersih.'];
    } else {
        $_SESSION['info'] = ['status' => 'error', 'pesan' => 'Gagal melunasi denda.'];
    }
    header("Location: index.php"); exit;
}
?>