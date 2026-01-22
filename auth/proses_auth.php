<?php 

session_start();
include '../config/koneksi.php';

if(isset($_POST['login'])){
    $username = $_POST['username'];
    $password = $_POST['password'];

    $cek = mysqli_query($koneksi, "SELECT * FROM users WHERE username = '$username'");
    
    if(mysqli_num_rows($cek) > 0){
        $data = mysqli_fetch_assoc($cek);

        if(password_verify($password, $data['password'])){
            
            $_SESSION['login']   = true;
            $_SESSION['id_user'] = $data['id_user'];
            $_SESSION['nama']    = $data['nama'];
            $_SESSION['role']    = $data['role'];

            header("Location: ../index.php"); 
            exit;

        } else {
            $_SESSION['info'] = ['status' => 'error', 'pesan' => 'Password Salah!'];
            header("Location: login.php");
            exit;
        }
    } else {
        $_SESSION['info'] = ['status' => 'error', 'pesan' => 'Username tidak terdaftar!'];
        header("Location: login.php");
        exit;
    }
}

if(isset($_GET['logout'])){
    session_destroy();
    session_unset();
    header("Location: ../welcome.php"); 
    exit;
}
?>