# Presensi Ekskul Chalvind

Sistem sederhana untuk mencatat **presensi kegiatan ekstrakurikuler**. Setiap peserta
dicatat kehadirannya per tanggal kegiatan dengan status **Hadir, Izin, Sakit, atau Alpha**.

Dibangun dengan PHP native, MySQL, dan Tailwind CSS. Data disimpan dalam **satu tabel**
(`presensi`).

## Teknologi

| Komponen     | Versi / Keterangan                           |
| ------------ | -------------------------------------------- |
| PHP          | 8.2.12 (XAMPP)                               |
| MySQL        | MariaDB 10.4.32 (XAMPP)                      |
| Tailwind CSS | 4.3.3 (build lokal via `@tailwindcss/cli`)   |
| ESLint       | 10.13.0                                      |
| Prettier     | 3.9.10 + `prettier-plugin-tailwindcss`       |
| Node.js      | 24.22.0 (hanya untuk tooling, bukan runtime) |

## Prasyarat

1. **XAMPP** terpasang di `C:\xampp`.
2. **Node.js** dan npm terpasang.
3. Apache dan MySQL sudah dinyalakan dari **XAMPP Control Panel**.

## Struktur Folder

```
presensi_ekskul_chalvind/
├── assets/
│   ├── css/app.css          # hasil build Tailwind (tidak di-commit)
│   └── js/app.js            # skrip global (konfirmasi hapus)
├── config/
│   └── koneksi.php          # koneksi database + fungsi bantu
├── database/
│   └── presensi_ekskul.sql  # script pembuatan database dan tabel
├── includes/
│   ├── fungsi.php           # helper badge, format tanggal, validasi
│   ├── form-presensi.php    # partial form (dipakai tambah & edit)
│   ├── header.php
│   └── footer.php
├── resources/
│   └── css/input.css        # sumber Tailwind
├── index.php                # daftar presensi (Read)
├── tambah.php               # form tambah (Create)
├── simpan.php               # proses simpan (Create)
├── edit.php                 # form edit (Update)
├── update.php               # proses perbarui (Update)
├── hapus.php                # proses hapus (Delete)
├── eslint.config.js
├── package.json
└── README.md
```

## Langkah Instalasi

### 1. Dapatkan source code

Letakkan folder project ini di `C:\xampp\htdocs\presensi_ekskul_chalvind`.

### 2. Jalankan Apache dan MySQL

Buka **XAMPP Control Panel**, lalu klik **Start** pada **Apache** dan **MySQL**.

### 3. Buat database dan tabel

Lewat terminal:

```powershell
& "C:\xampp\mysql\bin\mysql.exe" -u root -e "source C:/xampp/htdocs/presensi_ekskul_chalvind/database/presensi_ekskul.sql"
```

Atau lewat **phpMyAdmin**:

1. Buka `http://localhost/phpmyadmin`
2. Masuk ke tab **SQL**
3. Tempel isi file `database/presensi_ekskul.sql`, lalu klik **Go**

### 4. Pasang dependency dan build CSS

```powershell
npm install
npm run build
```

### 5. Buka aplikasi

```
http://localhost/presensi_ekskul_chalvind/
```

## Cara Pakai Aplikasi

1. Halaman **Daftar Presensi** (`index.php`) menampilkan seluruh catatan dalam bentuk tabel,
   diurutkan dari tanggal terbaru.
2. Klik **Tambah Presensi** (`tambah.php`), isi form, lalu klik **Simpan**.
3. Klik **Edit** pada sebuah baris untuk mengubah data (`edit.php`), lalu klik **Perbarui**.
4. Klik **Hapus** pada sebuah baris, lalu setujui dialog konfirmasi untuk menghapus data.

Setiap field divalidasi di sisi server. Bila ada yang tidak sesuai, form akan menampilkan
pesan error di bawah field terkait dan input sebelumnya tidak hilang.

| Field            | Aturan                                        |
| ---------------- | --------------------------------------------- |
| Nama Peserta     | Wajib, 3–100 karakter                         |
| Kelas            | Wajib, maksimal 20 karakter                   |
| Ekstrakurikuler  | Wajib, maksimal 50 karakter                   |
| Tanggal Kegiatan | Wajib, tanggal valid, tidak melebihi hari ini |
| Status           | Wajib, salah satu dari Hadir/Izin/Sakit/Alpha |
| Keterangan       | Opsional, maksimal 100 karakter               |

## Daftar Script npm

| Perintah               | Fungsi                                                  |
| ---------------------- | ------------------------------------------------------- |
| `npm run build`        | Compile Tailwind sekali (hasil di `assets/css/app.css`) |
| `npm run dev`          | Compile Tailwind mode watch                             |
| `npm run lint`         | Periksa JavaScript dengan ESLint                        |
| `npm run lint:fix`     | Perbaiki otomatis masalah ESLint                        |
| `npm run lint:php`     | Periksa syntax file PHP dengan `php -l`                 |
| `npm run format`       | Rapikan format file dengan Prettier                     |
| `npm run format:check` | Cek format tanpa mengubah file                          |
| `npm run verify`       | ESLint + PHP lint + build                               |

## Konfigurasi Database

Diatur di `config/koneksi.php`:

| Parameter | Nilai                      |
| --------- | -------------------------- |
| Host      | `localhost`                |
| User      | `root`                     |
| Password  | (kosong)                   |
| Database  | `presensi_ekskul_chalvind` |
| Port      | `3306`                     |

## Struktur Tabel `presensi`

| Kolom           | Tipe                           | Keterangan  |
| --------------- | ------------------------------ | ----------- |
| id_presensi     | INT AUTO_INCREMENT             | Primary key |
| nama_peserta    | VARCHAR(100)                   | Wajib       |
| kelas           | VARCHAR(20)                    | Wajib       |
| ekstrakurikuler | VARCHAR(50)                    | Wajib       |
| tanggal         | DATE                           | Wajib       |
| status          | ENUM Hadir, Izin, Sakit, Alpha | Wajib       |
| keterangan      | VARCHAR(100)                   | Opsional    |

## Catatan Pengembangan

- **`assets/css/app.css` tidak di-commit.** Setelah `git clone`, wajib jalankan
  `npm install` lalu `npm run build` agar tampilan muncul.
- **ESLint hanya memeriksa JavaScript.** PHP diperiksa dengan `php -l` melalui
  `npm run lint:php`.
- **Prettier tidak memformat file PHP**, karena belum ada plugin resmi yang dipakai
  di project ini.
- Di **PowerShell**, npm dipanggil sebagai `npm.cmd` karena execution policy
  memblokir `npm.ps1`.

## Status Pengerjaan

| Fase                             | Status  |
| -------------------------------- | ------- |
| Setup project, database, tooling | Selesai |
| Halaman daftar presensi (Read)   | Selesai |
| Tambah data (Create)             | Selesai |
| Edit data (Update)               | Selesai |
| Hapus data (Delete)              | Selesai |
