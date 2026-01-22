<?php 

if ($_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['HTTP_HOST'] == '127.0.0.1') {

    $host = "localhost";
    $user = "root";
    $pass = "";
    $db   = "db_perpustakaan";
} else {
    $host = "localhost"; 
    $user = "u123456_user"; 
    $pass = "password_hosting"; 
    $db   = "u123456_db"; 
}

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die("Koneksi Database Gagal: " . mysqli_connect_error());
}

function base_url($url = null) {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https://" : "http://";
    $domain   = $_SERVER['HTTP_HOST'];
    $path     = ($_SERVER['HTTP_HOST'] == 'localhost') ? '/uas_perpustakaan' : '';
    
    $base_url = $protocol . $domain . $path;
    return $url != null ? $base_url . "/" . $url : $base_url;
}

function cek_admin() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
        echo "<script>alert('Akses Ditolak! Anda bukan Admin.'); window.location='".base_url('index.php')."';</script>";
        exit;
    }
}
?>