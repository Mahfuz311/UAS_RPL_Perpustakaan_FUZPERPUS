<?php

include '../config/koneksi.php';

session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php"); exit;
}

$tgl_awal = isset($_GET['tgl_awal']) ? $_GET['tgl_awal'] : date('Y-m-01');
$tgl_akhir = isset($_GET['tgl_akhir']) ? $_GET['tgl_akhir'] : date('Y-m-d');

$filename = "Laporan_Perpustakaan_" . $tgl_awal . "_sd_" . $tgl_akhir . ".xls";

header("Content-Type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=\"$filename\"");

$query = mysqli_query($koneksi, "SELECT peminjaman.*, users.nama, buku.judul 
        FROM peminjaman 
        JOIN users ON peminjaman.id_user = users.id_user 
        JOIN buku ON peminjaman.id_buku = buku.id_buku 
        WHERE tgl_pinjam BETWEEN '$tgl_awal' AND '$tgl_akhir'
        ORDER BY id_pinjam DESC");
?>

<h3>LAPORAN PERPUSTAKAAN FUZPERPUS</h3>
<p>Periode: <?= date('d M Y', strtotime($tgl_awal)) ?> s/d <?= date('d M Y', strtotime($tgl_akhir)) ?></p>

<table border="1">
    <thead>
        <tr style="background-color: #f2f2f2; font-weight:bold;">
            <th>No</th>
            <th>Nama Peminjam</th>
            <th>Judul Buku</th>
            <th>Tgl Pinjam</th>
            <th>Tgl Kembali (Aktual)</th>
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
            <td align="center"><?= $row['tgl_kembali_aktual'] ? $row['tgl_kembali_aktual'] : '-' ?></td>
            <td align="center"><?= $row['status'] ?></td>
            <td align="right"><?= $row['denda'] ?></td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>