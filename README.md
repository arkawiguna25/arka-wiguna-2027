# RuangTA — Portal Manajemen Tugas Akhir

Aplikasi PHP dan MySQL responsif untuk membantu admin, dosen, dan mahasiswa mengelola pengajuan serta progres tugas akhir.

## Fitur

- Login dengan sesi aman dan kata sandi yang di-hash.
- Hak akses berdasarkan peran: administrator, dosen, dan mahasiswa.
- CRUD akun pengguna (admin), pengajuan tugas akhir, dan catatan bimbingan.
- Pelacakan status: diajukan, review, proposal, penelitian, sidang, selesai, dan revisi.
- Unggah, unduh, dan hapus dokumen PDF/DOC/DOCX/JPG/PNG (maksimal 10 MB).
- Dashboard ringkasan, pencarian data per peran, desain modern responsif.
- Perlindungan CSRF dan query database berparameter.

## Persyaratan

- XAMPP dengan PHP 8.1+ (ekstensi PDO MySQL dan Fileinfo aktif).
- MySQL/MariaDB.
- Browser modern.

## Instalasi di XAMPP

1. Unduh repository sebagai ZIP dari GitHub, ekstrak folder `arka-wiguna-2027` ke `C:\xampp\htdocs\` (Windows) atau folder web server XAMPP pada sistem Anda.
2. Buka XAMPP Control Panel dan jalankan **Apache** serta **MySQL**.
3. Jika kredensial MySQL bukan `root` tanpa kata sandi, sesuaikan `config/database.php`.
4. Buka `http://localhost/arka-wiguna-2027/setup.php`.
5. Isi nama, email, dan kata sandi admin (minimal 10 karakter), lalu tekan **Buat database & akun**.
6. Setelah instalasi, hapus atau ubah nama `setup.php`.
7. Masuk dari `http://localhost/arka-wiguna-2027/`.
8. Admin dapat membuat akun dosen dan mahasiswa dari menu **Pengguna**.

## Instalasi di VS Code

Buka folder proyek melalui **File → Open Folder**, edit `config/database.php` bila perlu, lalu jalankan XAMPP. VS Code adalah editor; aplikasi PHP tetap perlu dijalankan melalui Apache/XAMPP atau server PHP yang terhubung ke MySQL.

## Struktur

```text
app/bootstrap.php       Koneksi, sesi, helper, dan pemeriksaan akses
assets/style.css        Tampilan responsif
config/database.php     Konfigurasi MySQL dan unggahan
database/schema.sql     Skema database
storage/uploads/        Dokumen yang diunggah
index.php               Halaman, CRUD, autentikasi, dan alur aplikasi
setup.php               Instalasi database dan admin pertama
```

## Catatan keamanan & operasional

- Untuk penggunaan produksi, gunakan HTTPS, ganti kredensial database, batasi akses folder `storage`, dan pastikan `setup.php` sudah dihapus.
- Sesuaikan batas unggah pada konfigurasi PHP (`upload_max_filesize` dan `post_max_size`) bila diperlukan.
- Pastikan folder `storage/uploads` dapat ditulis oleh proses PHP.
- Dosen dan admin dapat melihat semua pengajuan. Mahasiswa hanya dapat melihat pengajuan miliknya.
- Tidak ada akun contoh dengan kata sandi tetap: admin pertama dibuat saat proses setup.
