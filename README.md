# 🚀 TSEL Inventory — Sistem Manajemen Inventaris PT Telkomsel

[![Laravel 11](https://img.shields.io/badge/Laravel-11.x-red.svg?logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2%2B-blue.svg?logo=php&logoColor=white)](https://php.net)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-3.x-38bdf8.svg?logo=tailwind-css&logoColor=white)](https://tailwindcss.com)
[![Alpine.js](https://img.shields.io/badge/Alpine.js-3.x-77c1d2.svg?logo=alpine.js&logoColor=white)](https://alpinejs.dev)
[![Docker](https://img.shields.io/badge/Docker-Enabled-blue.svg?logo=docker&logoColor=white)](https://www.docker.com)
[![Build Status](https://img.shields.io/badge/Tests-80%20Passed-emerald.svg?logo=phpunit&logoColor=white)](https://phpunit.de)

**TSEL Inventory** adalah prototipe aplikasi sistem manajemen inventaris kantor berbasis web modern yang dirancang khusus untuk memenuhi studi kasus **PT Telkomsel**. Sistem ini dibangun dengan fokus pada efisiensi pencatatan aset kantor, pemantauan stok real-time, transparansi peminjaman barang, serta kemudahan penyusunan laporan guna menekan risiko kehilangan aset dan duplikasi pencatatan.

---

## 🎨 Desain & UI/UX Premium
Aplikasi ini dikembangkan dengan prinsip **Motion Design Modern** untuk memberikan pengalaman pengguna (*User Experience*) terbaik:
* **Tema Visual Khas:** Menggunakan palet warna merah utama (*Telkomsel Red*) dipadukan dengan aksen jingga sekunder (*Telkomsel Orange*) yang disesuaikan secara visual untuk identitas korporat PT Telkomsel.
* **Responsive Dark Mode:** Transisi mode gelap/terang secara halus dengan *state retention* menggunakan LocalStorage.
* **Premium Empty States:** Setiap halaman tanpa data (akibat pencarian kosong atau ketiadaan entri) dilengkapi dengan ilustrasi SVG premium, deskripsi kontekstual, icon interaktif, serta Call-to-Action (CTA) dinamis untuk menambah data atau menyegarkan pencarian.
* **Micro-Animations & Easing:** Animasi kemunculan konten (*fade-in*, *slide-up*) snappy dengan durasi natural maksimal 300ms untuk kenyamanan visual yang elegan tanpa mengganggu operasional sistem.

---

## 🛠️ Arsitektur & Tech Stack

Sistem ini menerapkan pola arsitektur **MVC (Model-View-Controller)** yang dikombinasikan dengan **Service Pattern** untuk memisahkan logika bisnis dari Controller, sehingga kode tetap modular, bersih, dan mudah diuji.

* **Backend Framework:** Laravel 11.x (PHP 8.2+)
* **Frontend Stack:** Tailwind CSS 3.x, Alpine.js 3.x (Vite Bundler)
* **Database:** MySQL 8.0 / MariaDB (Mendukung soft-deletes, integrity constraints, dan indexing)
* **Auth System:** Laravel Breeze (Session-based & Laravel Sanctum API Token-based)
* **Testing:** PHPUnit (80 Unit & Feature Tests Passed)
* **Containerization:** Docker & Docker Compose
* **Libraries Utama:**
  * `barryvdh/laravel-dompdf` (Pembuatan Laporan PDF)
  * `openspout/openspout` (Ekspor Excel memori-efisien)
  * `chart.js` (Visualisasi data analitik di Dashboard)

---

## ⚙️ Key Features (Fitur Utama)

1. **Role-Based Access Control (RBAC):**
   * **Admin:** Akses penuh untuk manajemen pengguna, kelola barang, kategori, transaksi, dan cetak laporan.
   * **Staff:** Mengelola inventaris barang, kategori, serta memproses transaksi peminjaman & pengembalian.
   * **Manager:** Hanya dapat mengakses dasbor analitik, melihat riwayat peminjaman, dan mengunduh laporan Excel/PDF.
2. **Pessimistic Locking (`lockForUpdate`):**
   * Mengamankan stok barang saat transaksi peminjaman dibuat secara bersamaan (*concurrency*) guna mencegah *race condition* yang dapat menyebabkan stok bernilai minus di database.
3. **Sistem Notifikasi Stok Menipis (Low Stock Alert):**
   * Menampilkan peringatan stok rendah secara otomatis di sidebar dan dasbor jika stok barang kurang dari atau sama dengan batas minimum (`min_stock`).
4. **Alur Transaksi Dinamis:**
   * Penggunaan Alpine.js pada form peminjaman untuk menambah/menghapus baris barang secara dinamis tanpa reload halaman.
5. **Ekspor Laporan Handal:**
   * Ekspor PDF berformat kop resmi siap cetak, serta unduhan Excel (.xlsx) instan dengan filter tanggal, kategori, dan status.
6. **REST API endpoints:**
   * 18 REST endpoints terintegrasi dan aman dengan otentikasi token **Laravel Sanctum**.

---

## 👥 Akun Uji Coba (Testing Accounts)

Gunakan akun pra-konfigurasi berikut untuk menguji hak akses sistem (semua password adalah `password`):

| Role | Email | Hak Akses |
| :--- | :--- | :--- |
| **Admin** | `admin@telkomsel.com` | Akses Penuh (CRUD Barang, Kategori, User, Transaksi, Laporan PDF/Excel) |
| **Staff** | `staff@telkomsel.com` | CRUD Barang, CRUD Kategori, Transaksi Peminjaman & Pengembalian |
| **Manager** | `manager@telkomsel.com` | Read-only Dashboard, Cetak PDF, Unduh Excel |

---

## 💻 Panduan Instalasi & Menjalankan Proyek

### Opsi A: Instalasi Lokal (Tradisional)

#### Prasyarat Sistem
* PHP >= 8.2
* Composer
* Node.js & NPM
* MySQL / MariaDB

#### Langkah-langkah
1. **Clone repositori:**
   ```bash
   git clone https://github.com/kakaadsp/telkomsel-inventory.git
   cd telkomsel-inventory
   ```
2. **Instal dependensi Composer & NPM:**
   ```bash
   composer install
   npm install
   ```
3. **Konfigurasi Environment:**
   Salin berkas `.env.example` ke `.env` dan sesuaikan koneksi database MySQL Anda:
   ```bash
   cp .env.example .env
   ```
4. **Generate App Key:**
   ```bash
   php artisan key:generate
   ```
5. **Jalankan Migrasi & Seeder Database:**
   ```bash
   php artisan migrate --seed
   ```
6. **Hubungkan Storage Link:**
   ```bash
   php artisan storage:link
   ```
7. **Build Aset Frontend (Vite):**
   ```bash
   npm run build
   ```
8. **Jalankan Server Lokal:**
   ```bash
   php artisan serve
   ```
   Aplikasi dapat diakses di browser melalui alamat: `http://localhost:8000`

---

### Opsi B: Menggunakan Docker

Pastikan aplikasi **Docker Desktop** sudah aktif di komputer Anda.

1. **Jalankan container (Aplikasi & Database MySQL):**
   ```bash
   docker-compose up -d --build
   ```
2. **Jalankan migrasi database dan seeder di dalam container:**
   ```bash
   docker-compose exec app php artisan migrate --seed
   ```
3. **Buat storage link di dalam container:**
   ```bash
   docker-compose exec app php artisan storage:link
   ```
   Aplikasi dapat diakses di browser melalui alamat: `http://localhost:8000`

---

## 🧪 Eksekusi Unit & Feature Testing

Sistem ini dilengkapi dengan 80 tes fungsionalitas otomatis untuk menjamin integritas kode. Jalankan perintah berikut untuk mengecek keabsahan test suite:
```bash
php artisan test --no-coverage
```

---

## 📁 Lampiran Output Tambahan
* **Database SQL Dump:** [database.sql](database.sql) (Struktur tabel & dummy seeder siap di-import).
* **Dokumentasi REST API:** [API_DOCUMENTATION.md](API_DOCUMENTATION.md) (Dokumentasi lengkap untuk 18 endpoints API).

---

Developed by **Kaka Dimas Soehendra Putra**
