**Bahasa:** [English](README.md) | Bahasa Indonesia

# LinkVault

LinkVault adalah aplikasi web manajemen bookmark berbasis Laravel yang memungkinkan pengguna menyimpan, mengorganisasi, dan membagikan tautan (link) favoritnya. Setiap pengguna memiliki "vault" pribadi yang dapat diisi bookmark dengan kategori, deskripsi, status favorit, serta pengaturan visibilitas (publik/privat). Bookmark publik dapat dijelajahi oleh seluruh pengguna melalui halaman Explore dan disimpan ke koleksi pribadi.

## Konteks Akademik

Project ini merupakan Ujian Tengah Semester (UTS) pada mata kuliah Pemrograman Web Berbasis Framework (PWBF).

## Fitur Utama

- Autentikasi Pengguna: registrasi, login, dan logout dengan session.
- Dashboard Bookmark Pribadi: menampilkan seluruh bookmark milik pengguna dengan pencarian berdasarkan judul dan filter berdasarkan kategori.
- Manajemen Bookmark (CRUD): tambah, ubah, dan hapus bookmark (judul, URL, deskripsi, kategori).
- Status Favorit: tandai bookmark sebagai favorit dan lihat daftar favorit.
- Visibilitas Publik/Privat: atur apakah bookmark dapat dilihat publik atau hanya pribadi.
- Halaman Explore: jelajahi bookmark publik dari semua pengguna, lengkap dengan pencarian dan filter kategori.
- Simpan ke Koleksi: salin bookmark publik dari pengguna lain ke koleksi pribadi.
- Profil Pengguna: halaman profil yang menampilkan bookmark publik milik pengguna tertentu.
- Statistik: jumlah total bookmark, favorit, privat, dan publik pada dashboard.
- Kategori Tetap: Development, Education, Design, Productivity, Entertainment, Other.

## Tech Stack

- PHP 8.3+ (diuji pada 8.5)
- Laravel 13.x
- Database: SQLite (default), mendukung MySQL/PostgreSQL
- Frontend: Blade Templates, Tailwind CSS 4, Vite
- ORM: Eloquent

## Panduan Instalasi & Menjalankan Secara Lokal

```bash
git clone https://github.com/gedeyodyananta/linkvault-uts-pwbf.git
cd linkvault-uts-pwbf

composer install
cp .env.example .env
php artisan key:generate

# Buat database SQLite (jika menggunakan default)
touch database/database.sqlite
php artisan migrate

npm install
npm run build

php artisan serve
```

Aplikasi dapat diakses di `http://127.0.0.1:8000`.

Alternatif: jalankan `composer run setup` untuk otomatis melakukan instalasi, migrasi, dan build frontend.

## Identitas Mahasiswa

- Nama: Gede Yodyananta
- NIM: [Isi NIM]
- Kelas: [Isi Kelas]

## Lisensi

Project ini dibuat untuk keperluan akademik (UTS PWBF).
"