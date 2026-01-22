<?php
include '../config/koneksi.php';
include '../layouts/header.php';
cek_admin();

$tahun_ini = date('Y');
$data_tahunan = [];
$label_tahunan = [];

for ($i = 1; $i <= 12; $i++) {
    $query = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM peminjaman WHERE MONTH(tgl_pinjam) = '$i' AND YEAR(tgl_pinjam) = '$tahun_ini'");
    $row = mysqli_fetch_assoc($query);
    $data_tahunan[] = $row['total'];
    $label_tahunan[] = date('F', mktime(0, 0, 0, $i, 10));
}

$label_buku = [];
$data_buku = [];
$query_populer = mysqli_query($koneksi, "SELECT b.judul, COUNT(p.id_buku) as total 
                                         FROM peminjaman p 
                                         JOIN buku b ON p.id_buku = b.id_buku 
                                         GROUP BY p.id_buku 
                                         ORDER BY total DESC LIMIT 5");

while($row = mysqli_fetch_assoc($query_populer)){
    $label_buku[] = substr($row['judul'], 0, 20) . '...';
    $data_buku[] = $row['total'];
}

$bulan_ini = date('m');
$jumlah_hari = date('t');
$data_harian = [];
$label_harian = [];

for ($i = 1; $i <= $jumlah_hari; $i++) {
    $query = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM peminjaman WHERE DAY(tgl_pinjam) = '$i' AND MONTH(tgl_pinjam) = '$bulan_ini' AND YEAR(tgl_pinjam) = '$tahun_ini'");
    $row = mysqli_fetch_assoc($query);
    $data_harian[] = $row['total'];
    $label_harian[] = $i;
}
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="container mt-4 mb-5">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-primary"><i class="bi bi-bar-chart-line-fill me-2"></i>Statistik Perpustakaan</h3>
            <p class="text-muted">Analisa data peminjaman secara visual.</p>
        </div>
        <button class="btn btn-outline-primary rounded-pill" onclick="window.print()"><i class="bi bi-printer me-2"></i>Cetak Grafik</button>
    </div>

    <div class="row g-4 mb-4">
        
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold">📅 Peminjaman Tahun <?= $tahun_ini ?></h6>
                </div>
                <div class="card-body">
                    <canvas id="chartTahunan"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold">🔥 5 Buku Terpopuler</h6>
                </div>
                <div class="card-body">
                    <canvas id="chartPopuler"></canvas>
                    <?php if(count($data_buku) == 0): ?>
                        <p class="text-center text-muted mt-5">Belum ada data peminjaman.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white py-3">
            <h6 class="mb-0 fw-bold">📈 Grafik Harian (Bulan <?= date('F') ?>)</h6>
        </div>
        <div class="card-body">
            <canvas id="chartHarian" style="max-height: 350px;"></canvas>
        </div>
    </div>

</div>

<script>

    const ctxTahunan = document.getElementById('chartTahunan');
    new Chart(ctxTahunan, {
        type: 'bar',
        data: {
            labels: <?= json_encode($label_tahunan) ?>,
            datasets: [{
                label: 'Jumlah Peminjaman',
                data: <?= json_encode($data_tahunan) ?>,
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1,
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
        }
    });

    const ctxPopuler = document.getElementById('chartPopuler');
    new Chart(ctxPopuler, {
        type: 'doughnut',
        data: {
            labels: <?= json_encode($label_buku) ?>,
            datasets: [{
                data: <?= json_encode($data_buku) ?>,
                backgroundColor: [
                    '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'
                ],
                hoverOffset: 4
            }]
        }
    });

    const ctxHarian = document.getElementById('chartHarian');
    new Chart(ctxHarian, {
        type: 'line',
        data: {
            labels: <?= json_encode($label_harian) ?>,
            datasets: [{
                label: 'Transaksi Harian',
                data: <?= json_encode($data_harian) ?>,
                fill: true,
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
        }
    });
</script>

<?php include '../layouts/footer.php'; ?>