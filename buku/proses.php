<?php 
include '../config/koneksi.php';
session_start();

function upload_gambar() {
    $namaFile   = $_FILES['gambar']['name'];
    $ukuranFile = $_FILES['gambar']['size'];
    $error      = $_FILES['gambar']['error'];
    $tmpName    = $_FILES['gambar']['tmp_name'];

    $ekstensiValid = ['jpg', 'jpeg', 'png'];
    $ekstensi = explode('.', $namaFile);
    $ekstensi = strtolower(end($ekstensi));

    if( !in_array($ekstensi, $ekstensiValid) ) {
        $_SESSION['info'] = ['status' => 'error', 'pesan' => 'Format file bukan gambar!'];
        return false;
    }
    if( $ukuranFile > 2000000 ) {
        $_SESSION['info'] = ['status' => 'error', 'pesan' => 'Ukuran gambar terlalu besar (Max 2MB)!'];
        return false;
    }

    $namaFileBaru = uniqid() . '.' . $ekstensi;
    move_uploaded_file($tmpName, '../assets/img/' . $namaFileBaru);
    return $namaFileBaru;
}

if(isset($_POST['tambah_buku'])){
    $judul      = $_POST['judul'];
    $pengarang  = $_POST['pengarang'];
    $penerbit   = $_POST['penerbit'];
    $tahun      = $_POST['tahun_terbit'];
    $stok       = $_POST['stok'];
    $rak        = $_POST['lokasi_rak'];
    $deskripsi  = $_POST['deskripsi'];
    
    $nama_gambar = null;
    if($_FILES['gambar']['error'] !== 4) {
        $nama_gambar = upload_gambar();
        if(!$nama_gambar) { header("Location: index.php"); exit; }
    }

    $query = "INSERT INTO buku (judul, pengarang, penerbit, tahun_terbit, stok, lokasi_rak, deskripsi, gambar) 
              VALUES ('$judul', '$pengarang', '$penerbit', '$tahun', '$stok', '$rak', '$deskripsi', '$nama_gambar')";

    if(mysqli_query($koneksi, $query)){
        $_SESSION['info'] = ['status' => 'success', 'pesan' => 'Buku Berhasil Ditambahkan'];
    } else {
        $_SESSION['info'] = ['status' => 'error', 'pesan' => 'Gagal Menambahkan Data'];
    }
    header("Location: index.php");
    exit;
}

if(isset($_POST['edit_buku'])){
    $id         = $_POST['id_buku'];
    $judul      = $_POST['judul'];
    $pengarang  = $_POST['pengarang'];
    $penerbit   = $_POST['penerbit'];
    $tahun      = $_POST['tahun_terbit'];
    $stok       = $_POST['stok'];
    $rak        = $_POST['lokasi_rak'];
    $deskripsi  = $_POST['deskripsi'];
    $gambar_lama = $_POST['gambar_lama'];
    
    $halaman_redirect = isset($_POST['halaman_redirect']) ? $_POST['halaman_redirect'] : 1;

    if($_FILES['gambar']['error'] === 4) {
        $gambar = $gambar_lama;
    } else {
        $gambar = upload_gambar();
        if(!$gambar) { header("Location: index.php?halaman=$halaman_redirect"); exit; }
        
        if($gambar_lama != null && file_exists('../assets/img/' . $gambar_lama)){
            unlink('../assets/img/' . $gambar_lama);
        }
    }

    $query = "UPDATE buku SET 
                judul = '$judul',
                pengarang = '$pengarang',
                penerbit = '$penerbit',
                tahun_terbit = '$tahun',
                stok = '$stok',
                lokasi_rak = '$rak',
                deskripsi = '$deskripsi',
                gambar = '$gambar'
              WHERE id_buku = '$id'";

    if(mysqli_query($koneksi, $query)){
        $_SESSION['info'] = ['status' => 'success', 'pesan' => 'Data Buku Diupdate'];
    } else {
        $_SESSION['info'] = ['status' => 'error', 'pesan' => 'Gagal Update Data'];
    }
    
    header("Location: index.php?halaman=" . $halaman_redirect);
    exit;
}

if(isset($_GET['hapus_id'])){
    $id = $_GET['hapus_id'];
    
    $cek = mysqli_query($koneksi, "SELECT gambar FROM buku WHERE id_buku='$id'");
    $data = mysqli_fetch_assoc($cek);
    
    if($data['gambar'] != null && file_exists('../assets/img/' . $data['gambar'])){
        unlink('../assets/img/' . $data['gambar']);
    }

    $query = "DELETE FROM buku WHERE id_buku='$id'";
    if(mysqli_query($koneksi, $query)){
        $_SESSION['info'] = ['status' => 'success', 'pesan' => 'Buku Berhasil Dihapus'];
    } else {
        $_SESSION['info'] = ['status' => 'error', 'pesan' => 'Gagal Menghapus Data'];
    }
    header("Location: index.php");
    exit;
}
?>