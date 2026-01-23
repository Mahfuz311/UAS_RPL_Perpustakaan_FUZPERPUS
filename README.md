# UAS Rekayasa Perangkat Lunak (RPL)

**Nama:** Mahfuz Fauzi  
**Kelas:** TI.24.A.3  
**NIM:** 312410412  
**Mata Kuliah:** Rekayasa Perangkat Lunak  
**Dosen Pengampu:** Karina Imelda, S. Kom., M.Kom.  
**Universitas Pelita Bangsa**

---

# 📚 FUZPERPUS - Sistem Informasi Perpustakaan Digital

**FUZPERPUS** adalah aplikasi berbasis web yang dirancang untuk mempermudah pengelolaan data perpustakaan, mulai dari manajemen buku, anggota, hingga sirkulasi peminjaman dan pengembalian.

Project ini dibuat untuk memenuhi Tugas Akhir Mata Kuliah **Rekayasa Perangkat Lunak (RPL)**.

🌐 **Live Demo:** [https://fuzperpus.my.id](https://fuzperpus.my.id)

---

## 🚀 Fitur Utama

Aplikasi ini memiliki dua hak akses (role): **Admin** dan **Anggota**.

### 👨‍💼 Administrator
* **Dashboard Statistik:** Melihat ringkasan jumlah buku, anggota, dan transaksi aktif.
* **Master Data Buku:** Tambah, Edit, Hapus data buku & upload cover.
* **Master Data Anggota:** Kelola data pengguna perpustakaan.
* **Transaksi:** Mencatat peminjaman dan pengembalian buku.
* **Denda Otomatis:** Sistem otomatis menghitung denda keterlambatan (Rp 1.000/hari).
* **Laporan:** Cetak laporan transaksi ke PDF dan melihat grafik statistik.
* **Reset Database:** Fitur "Danger Zone" untuk membersihkan data transaksi (untuk testing).

### 👨‍🎓 Anggota (Mahasiswa)
* **Katalog Buku:** Mencari dan melihat detail buku yang tersedia.
* **Riwayat Peminjaman:** Melihat status buku yang sedang dipinjam atau sudah dikembalikan.
* **Cek Denda:** Melihat apakah ada tagihan denda yang harus dibayar.
* **Kartu Anggota:** Mencetak kartu anggota perpustakaan digital.
* **Profil:** Mengupdate foto profil dan data diri.

---

## 🛠️ Teknologi yang Digunakan

* **Bahasa Pemrograman:** PHP Native (Versi 8.x)
* **Database:** MySQL / MariaDB
* **Frontend Framework:** Bootstrap 5.3
* **Icon Pack:** Bootstrap Icons & FontAwesome 6
* **Alerts:** SweetAlert2
* **Server Environment:** XAMPP (Local) / cPanel (Hosting)

---

## 📂 Struktur Folder

```text
uas_perpustakaan/
├── assets/          # File CSS, JS, dan Gambar (Cover Buku)
├── config/          # Koneksi Database
├── layouts/         # Header, Footer, Navbar (Template)
├── auth/            # Login & Logout System
├── buku/            # CRUD Data Buku
├── anggota/         # CRUD Data Anggota
├── transaksi/       # Proses Pinjam & Kembali
├── laporan/         # PDF & Grafik
├── index.php        # Dashboard Utama
└── README.md        # File Dokumentasi
