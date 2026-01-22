<?php 
if (session_status() == PHP_SESSION_NONE) { session_start(); }

if (!function_exists('base_url')) {
    function base_url($url = null) {
        $base_url = "http://localhost/uas_perpustakaan";
        return $url != null ? $base_url . "/" . $url : $base_url;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FUZPERPUS - Sistem Informasi Perpustakaan</title>
    
    <link rel="icon" href="https://cdn-icons-png.flaticon.com/512/2232/2232688.png" type="image/png">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link href="<?= base_url('assets/css/style.css') ?>" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>

    <style>
        body { 
            font-family: 'Poppins', sans-serif; 
            background-color: #f8f9fa;
            display: flex; flex-direction: column; min-height: 100vh;
        }
        body > .container { flex: 1; }
        footer { margin-top: auto; }
        ::-webkit-scrollbar { display: none; }
        html, body { scrollbar-width: none; -ms-overflow-style: none; }
    </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<?php if(isset($_SESSION['info'])): ?>
    <script>
        Swal.fire({
            icon: '<?= $_SESSION['info']['status'] ?>',
            title: '<?= $_SESSION['info']['pesan'] ?>',
            showConfirmButton: false,
            timer: 2000
        });
    </script>
    <?php unset($_SESSION['info']); ?>
<?php endif; ?>