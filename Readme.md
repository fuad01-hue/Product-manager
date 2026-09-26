# Tugas Mini Project 2 - Pemrograman Web

Repository ini berisi kode sumber untuk Tugas Mini Project mata kuliah Pemrograman Web. Aplikasi ini adalah sistem manajemen produk sederhana (CRUD) yang dibangun menggunakan PHP Native dan MySQL, tanpa menggunakan framework.

Tugas ini berfokus pada integrasi sisi server (PHP), database (MySQL), desain UI responsif, serta penerapan standar keamanan dasar aplikasi web.

## Fitur Utama
* **Create:** Tambah data produk baru dengan validasi form (nama minimal 3 huruf, harga lebih dari 0, stok tidak boleh minus).
* **Read:** Menampilkan daftar produk dalam bentuk *card* yang responsif.
* **Update:** Mengubah data produk yang sudah ada berdasarkan ID.
* **Delete:** Menghapus produk. Menggunakan form dengan method POST (bukan GET) demi keamanan.
* **Search:** Fitur pencarian produk berdasarkan nama atau kategori.

## Konsep & Keamanan yang Dipakai
Sesuai dengan materi perkuliahan, project ini sudah menerapkan beberapa standar keamanan dan alur kerja:
* **PDO & Prepared Statements:** Mencegah serangan *SQL Injection* saat berinteraksi dengan database.
* **Anti-XSS (Cross-Site Scripting):** Menggunakan fungsi `htmlspecialchars` setiap kali mencetak output dari user ke layar.
* **CSRF Token:** Menggunakan session token pada form hapus data untuk memastikan *request* benar-benar berasal dari dalam aplikasi.
* **PRG (Post-Redirect-Get) Pattern:** Setelah data berhasil disimpan/diupdate, halaman akan otomatis di-redirect agar tidak terjadi *double input* saat user menekan tombol *refresh* (F5).
* **UI/UX Dasar:** Menggunakan CSS Box Model dan Flexbox agar tampilan *card* produk tetap rapi meski dibuka di layar kecil.

## Prasyarat Server
* XAMPP / Laragon (Atau web server lokal lainnya)
* PHP versi 7.4 ke atas (Disarankan versi 8)
* MySQL / MariaDB

## Cara Menjalankan Project
1. Clone repository ini ke dalam folder *document root* web server kamu (misalnya di `C:\xampp\htdocs\` jika memakai XAMPP).
   
```bash
git clone [https://github.com/fuad01-hue/product-manager.git](https://github.com/fuad01-hue/product-manager.git)
```
# Struktur Proyek
```
product-manager/
├── config/
│   └── db.php           # File koneksi PDO ke database
├── database/
│   └── store_db.sql     # Skema tabel database untuk di-import
├── public/
│   ├── assets/
│   │   └── style.css    # File styling CSS (Flexbox & tampilan UI)
│   ├── create.php       # Halaman form tambah data (Create + PRG)
│   ├── delete.php       # Proses hapus data + verifikasi token CSRF (Delete)
│   ├── edit.php         # Halaman form ubah data (Update)
│   └── index.php        # Halaman utama daftar produk (Read & Search)
└── index.php            # File pancingan untuk redirect otomatis ke folder /public/
