<?php
include '../config/koneksi.php';
cek_admin();

$tgl_awal = $_GET['tgl_awal'];
$tgl_akhir = $_GET['tgl_akhir'];

$query = mysqli_query($koneksi, "SELECT * FROM peminjaman 
        JOIN users ON peminjaman.id_user = users.id_user
        JOIN buku ON peminjaman.id_buku = buku.id_buku
        WHERE tgl_pinjam BETWEEN '$tgl_awal' AND '$tgl_akhir'");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Laporan Perpustakaan</title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #333; padding: 8px; font-size: 12px; }
        th { background: #eee; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h2 { margin: 0; }
    </style>
</head>
<body onload="window.print()"> <div class="header">
        <h2>LAPORAN PEMINJAMAN BUKU</h2>
        <p>FUZPERPUS - Sistem Informasi Perpustakaan</p>
        <small>Periode: <?= date('d/m/Y', strtotime($tgl_awal)) ?> s/d <?= date('d/m/Y', strtotime($tgl_akhir)) ?></small>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Peminjam</th>
                <th>Buku</th>
                <th>Tgl Pinjam</th>
                <th>Tgl Kembali</th>
                <th>Status</th>
                <th>Denda</th>
            </tr>
        </thead>
        <tbody>
            <?php $no=1; while($row = mysqli_fetch_assoc($query)): ?>
            <tr>
                <td align="center"><?= $no++ ?></td>
                <td><?= $row['nama'] ?></td>
                <td><?= $row['judul'] ?></td>
                <td align="center"><?= $row['tgl_pinjam'] ?></td>
                <td align="center"><?= $row['tgl_kembali_rencana'] ?></td>
                <td align="center"><?= ucfirst($row['status']) ?></td>
                <td align="right">Rp <?= number_format($row['denda']) ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <div style="float: right; margin-top: 30px; text-align: center; width: 200px;">
        <p>Mengetahui,<br>Kepala Perpustakaan</p>
        <br><br><br>
        <p><b>( ........................... )</b></p>
    </div>

</body>
</html>