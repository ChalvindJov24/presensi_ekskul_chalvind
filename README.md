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
│   └── js/app.js            # skrip global
├── config/
│   └── koneksi.php          # koneksi database + fungsi bantu
├── database/
│   └── presensi_ekskul.sql  # script pembuatan database dan tabel
├── includes/
│   ├── header.php
│   └── footer.php
├── resources/
│   └── css/input.css        # sumber Tailwind
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

| Fase                             | Status        |
| -------------------------------- | ------------- |
| Setup project, database, tooling | Selesai       |
| Halaman daftar presensi (Read)   | Belum dimulai |
| Tambah data (Create)             | Belum dimulai |
| Edit data (Update)               | Belum dimulai |
| Hapus data (Delete)              | Belum dimulai |
