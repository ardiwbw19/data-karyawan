Di aplikasi ini, admin bisa login lalu mengelola data karyawan dari satu halaman utama.
Data yang dikelola meliputi nama, jabatan, alamat, status aktif/tidak aktif, dan foto karyawan.

## Fitur Utama

- Login admin dan sesi login/logout.
- Tambah data karyawan baru.
- Edit data karyawan.
- Hapus data karyawan (dengan konfirmasi).
- Upload foto karyawan.
- Validasi input form (nama, jabatan, alamat, status, ukuran/format foto).
- Pencarian data berdasarkan nama atau jabatan.
- Filter data berdasarkan jabatan.
- Pagination daftar karyawan.
- Export data ke Excel (`.xls`).
- Export tampilan siap cetak untuk PDF.

## Cara Instalasi (XAMPP)

### 1) Siapkan folder project

Pastikan project ada di:

`C:\xampp\htdocs\data-karyawan`

### 2) Aktifkan Apache dan MySQL

Buka XAMPP Control Panel, lalu start:

- Apache
- MySQL

### 3) Buat database

Masuk ke phpMyAdmin, lalu buat database baru dengan nama:

`db_karyawan`

### 4) Import database dari file dump SQL (ZIP)

Gunakan file dump SQL yang ada pada file zip.

Langkahnya:

- Ekstrak ZIP sampai mendapatkan file dump dengan ekstensi `.sql`.
- Masuk ke phpMyAdmin dan pilih database `db_karyawan`.
- Buka menu **Import**.
- Pilih file dump `.sql` hasil ekstrak ZIP.
- Klik **Go / Import** dan tunggu sampai proses selesai.

Setelah import berhasil, struktur tabel dan data awal akan otomatis terpasang sesuai isi dump.

### 5) Cek konfigurasi koneksi database

File konfigurasi ada di:

`config/koneksi.php`

Nilai default saat ini:

- Host: `127.0.0.1`
- Port: `3306`
- Database: `db_karyawan`
- User: `root`
- Password: kosong

Kalau konfigurasi MySQL berbeda, silakan sesuaikan file tersebut.

### 6) Jalankan aplikasi

Buka browser:

`http://localhost/data-karyawan/login.php`

Akun login default:

- Username: `admin`
- Password: `admin123`

## Catatan Penggunaan

- Foto karyawan disimpan di folder: `uploads/karyawan/`.
- Batas ukuran foto: maksimal 2MB.
- Format foto yang didukung: JPG, PNG, WEBP.
- Jika data kosong, halaman daftar akan menampilkan pesan bahwa belum ada data.

## Struktur Singkat Folder Penting

- `index.php` → halaman utama daftar dan proses CRUD karyawan.
- `login.php` / `logout.php` → autentikasi.
- `export.php` → export Excel dan tampilan cetak PDF.
- `config/koneksi.php` → pengaturan koneksi database.
- `includes/fungsi.php` → fungsi utama aplikasi.
- `views/` → komponen tampilan (form, list, login).
- `uploads/karyawan/` → penyimpanan foto.
