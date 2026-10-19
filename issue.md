# Planning — Setup Project `presensi_ekskul_chalvind`

> **Status dokumen:** MENUNGGU APPROVAL
> **Versi:** 1.0
> **Tanggal:** 19 Oktober 2026
> **Fase:** SETUP PROJECT (belum masuk penulisan code aplikasi)

---

## 1. Context

### 1.1 Latar Belakang

Project ini adalah **sistem presensi ekstrakurikuler** — aplikasi sederhana untuk mencatat kehadiran peserta ekskul per tanggal kegiatan, dengan status **Hadir, Izin, Sakit, Alpha**. Bentuk aplikasinya murni **CRUD** dengan **satu tabel database**.

Aplikasi akan dibangun dengan **PHP native + MySQL** (tanpa framework), dijalankan di atas **XAMPP**, dan tampilan memakai **Tailwind CSS**.

### 1.2 Kondisi Saat Ini

| Item | Status |
|---|---|
| Folder project | `c:\xampp\htdocs\presensi_ekskul_chalvind` — **kosong** |
| Git lokal | **Belum** di-`init` |
| Repo GitHub | **Sudah dibuat**: https://github.com/ChalvindJov24/presensi_ekskul_chalvind (PRIVATE, masih kosong) |
| Akun `gh` CLI | Login sebagai `ChalvindJov24` |

### 1.3 Hasil Verifikasi Environment

| Komponen | Versi / Lokasi | Status |
|---|---|---|
| OS | Windows 10 | ✅ |
| XAMPP | `C:\xampp` | ✅ |
| PHP | **8.2.12** (CLI) — `C:\xampp\php\php.exe` | ✅ |
| `php.ini` | `C:\xampp\php\php.ini` | ✅ |
| Ekstensi PHP | `mysqli` ✅, `pdo_mysql` ✅ | ✅ |
| MySQL / MariaDB | **MariaDB 10.4.32** — `C:\xampp\mysql\bin\mysql.exe` | ✅ |
| Node.js | **v24.22.0** | ✅ |
| npm | **11.19.1** | ⚠️ lihat 1.4 |
| Composer | 2.9.5 (tidak dipakai) | — |
| git | 2.53.0 | ✅ |
| `gh` CLI | 2.102.0 (belum di PATH, wajib path lengkap) | ⚠️ lihat 1.4 |
| Apache / MySQL service | **Tidak** terdaftar sebagai Windows Service → harus distart dari XAMPP Control Panel | ⚠️ |

### 1.4 Catatan Penting dari Verifikasi

1. **`npm` tidak bisa dipanggil sebagai `npm`** di PowerShell.
   Penyebab: ExecutionPolicy default memblokir `npm.ps1`.
   Solusi yang dipakai: panggil **`npm.cmd`** (tidak mengubah setting sistem).

2. **`gh` tidak dikenali di PATH** sesi ini.
   Penyebab: PATH Machine sudah benar (`C:\Program Files\GitHub CLI\`), tapi sesi terminal belum refresh.
   Solusi yang dipakai: panggil dengan path lengkap **`& "C:\Program Files\GitHub CLI\gh.exe"`**.

3. **Apache & MySQL tidak berjalan sebagai service**, jadi harus dinyalakan manual lewat XAMPP Control Panel sebelum testing.

### 1.5 Perubahan Keputusan dari Diskusi Sebelumnya

| Aspek | Keputusan Awal | Keputusan Baru | Alasan |
|---|---|---|---|
| CSS Framework | **Bootstrap 5 via CDN** | **Tailwind CSS (build lokal)** | Instruksi terbaru: *"setup tailwind untuk project ini"* |

> ⚠️ **Perubahan ini perlu dikonfirmasi** (lihat Bagian 8, Pertanyaan Approval).

### 1.6 Tujuan Planning

Planning ini bertujuan untuk:

1. Menyiapkan **fondasi project** yang bersih, konsisten, dan bisa dijalankan sebelum satu baris pun code fitur ditulis.
2. Menetapkan **standar build** (Tailwind CLI) dan **standar kualitas kode** (ESLint + Prettier + PHP lint).
3. Menyamakan ekspektasi antara user dan AI agent tentang **apa yang termasuk setup** dan **apa yang bukan**.
4. Menjadi **kontrak kerja**: agent mengerjakan hanya apa yang tertulis di sini, dan berhenti untuk bertanya jika keluar dari lingkup ini.
5. Menyediakan **Definition of Done** yang objektif dan bisa diverifikasi, sehingga approval tidak berdasarkan asumsi.

### 1.7 Ruang Lingkup

**✅ Termasuk (In Scope):** inisialisasi git & repo, struktur folder, database MySQL (satu tabel), setup Node + Tailwind, setup linter/formatter, konfigurasi koneksi database, layout dasar (`header`/`footer`), dokumentasi `README.md`, dan verifikasi end-to-end.

**❌ Tidak Termasuk (Out of Scope):** seluruh halaman CRUD (`index.php`, `tambah.php`, `simpan.php`, `edit.php`, `update.php`, `hapus.php`), autentikasi/login, fitur pencarian & filter, pagination, dan deployment/hosting.

---

## 2. Keputusan Teknis

| Kode | Keputusan | Nilai |
|---|---|---|
| D1 | CSS framework | **Tailwind CSS v4.3.3** (build lokal via `@tailwindcss/cli`) |
| D2 | Cara build | npm script (`npm run build`, `npm run dev`) |
| D3 | Document root | **Root project** (bukan `public/`) → URL `http://localhost/presensi_ekskul_chalvind/` |
| D4 | Lokasi CSS hasil build | `assets/css/app.css` (**gitignored**, hasil generate) |
| D5 | Sumber Tailwind | `resources/css/input.css` |
| D6 | Konfigurasi Tailwind | **Tidak ada `tailwind.config.js`** — Tailwind v4 pakai CSS-first config |
| D7 | Deteksi file | `@source` eksplisit agar file `.php` ikut ter-scan |
| D8 | Linter JS | **ESLint 10.13.0** (flat config `eslint.config.js`) |
| D9 | Formatter | **Prettier 3.9.10** + `prettier-plugin-tailwindcss` 0.8.1 |
| D10 | Linter PHP | **`php -l`** (syntax check) — *ESLint tidak bisa melint PHP* |
| D11 | Akses database | **mysqli procedural** + prepared statement |
| D12 | Nama database | `presensi_ekskul_chalvind` |
| D13 | Nama tabel | `presensi` |
| D14 | Bahasa | Komentar kode, nama variabel teknis, dan teks UI: **Bahasa Indonesia** |

### 2.1 Catatan soal "lolos ESLint"

ESLint adalah linter untuk **JavaScript**, bukan PHP. Karena itu standar kualitas di project ini dibagi dua:

- **`npm run lint`** → ESLint untuk file JS di `assets/js/**/*.js`
- **`php -l <file>`** → syntax check untuk semua file `.php`

Keduanya digabung menjadi satu perintah verifikasi di `npm run verify`.

---

## 3. Struktur Folder & File

### 3.1 Target Struktur Akhir Project

```
presensi_ekskul_chalvind/
│
├── .gitignore                              [SETUP]
├── .prettierrc.json                        [SETUP]
├── .prettierignore                         [SETUP]
├── .editorconfig                           [SETUP]
├── eslint.config.js                        [SETUP]
├── package.json                            [SETUP]
├── package-lock.json                       [SETUP - generated]
├── README.md                               [SETUP]
├── issue.md                                [SETUP - dokumen ini]
│
├── resources/
│   └── css/
│       └── input.css                       [SETUP] sumber Tailwind
│
├── assets/
│   ├── css/
│   │   └── app.css                         [SETUP - GENERATED, gitignored]
│   └── js/
│       └── app.js                          [SETUP] placeholder JS minimum
│
├── config/
│   └── koneksi.php                         [SETUP] koneksi mysqli
│
├── database/
│   └── presensi_ekskul.sql                 [SETUP] script buat DB + tabel
│
├── includes/
│   ├── header.php                          [SETUP] <head> + navbar
│   └── footer.php                          [SETUP] penutup + script
│
├── _setup-check.php                        [SETUP - SEMENTARA, dihapus di akhir]
│
├── index.php                               [TAHAP BERIKUTNYA]
├── tambah.php                              [TAHAP BERIKUTNYA]
├── simpan.php                              [TAHAP BERIKUTNYA]
├── edit.php                                [TAHAP BERIKUTNYA]
├── update.php                              [TAHAP BERIKUTNYA]
└── hapus.php                               [TAHAP BERIKUTNYA]
```

### 3.2 Contoh Isi `.gitignore`

```gitignore
# Dependencies
node_modules/
vendor/

# Build output
assets/css/app.css

# Logs & OS
*.log
.DS_Store
Thumbs.db

# Editor
.idea/
```

### 3.3 Skema Database (satu tabel)

```sql
CREATE DATABASE IF NOT EXISTS presensi_ekskul_chalvind
  DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

USE presensi_ekskul_chalvind;

CREATE TABLE IF NOT EXISTS presensi_ekskul (
  id_presensi     INT AUTO_INCREMENT PRIMARY KEY,
  nama_peserta    VARCHAR(100) NOT NULL,
  kelas           VARCHAR(20)  NOT NULL,
  ekstrakurikuler VARCHAR(50)  NOT NULL,
  tanggal         DATE         NOT NULL,
  status          ENUM('Hadir','Izin','Sakit','Alpha') NOT NULL,
  keterangan      VARCHAR(100) NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

> Kolom `keterangan` memakai `VARCHAR(100)` sesuai revisi terakhir.

### 3.4 Parameter Koneksi Database

| Parameter | Nilai |
|---|---|
| Host | `localhost` |
| User | `root` |
| Password | *(kosong — default XAMPP)* |
| Database | `presensi_ekskul_chalvind` |
| Port | `3306` |

---

## 4. Step by Step Implementasi

> Total **8 fase, 34 langkah**. Agent harus mengerjakan **urut** dan boleh berhenti di checkpoint untuk konfirmasi.

### FASE A — Persiapan Environment

| # | Langkah | Perintah / Aksi | Bukti |
|---|---|---|---|
| A1 | Pastikan Apache & MySQL menyala | XAMPP Control Panel → **Start** Apache, **Start** MySQL | Indikator hijau; `http://localhost` bisa dibuka |
| A2 | Verifikasi PHP | `php -v` | Keluar `PHP 8.2.12` |
| A3 | Verifikasi MySQL | `& "C:\xampp\mysql\bin\mysql.exe" --version` | Keluar `MariaDB 10.4.32` |
| A4 | Verifikasi Node & npm | `node -v` dan `& npm.cmd -v` | `v24.22.0` dan `11.19.1` |

> ⚠️ **Checkpoint 1:** kalau MySQL/Apache tidak bisa start, **BERHENTI** dan laporkan ke user. Jangan mengubah konfigurasi XAMPP sendirian.

### FASE B — Inisialisasi Git & Repo

| # | Langkah | Perintah / Aksi | Bukti |
|---|---|---|---|
| B1 | Inisialisasi git | `git init -b main` | Folder `.git/` muncul |
| B2 | Buat `.gitignore` | Tulis file sesuai Bagian 3.2 | File ada |
| B3 | Sambungkan remote | `git remote add origin https://github.com/ChalvindJov24/presensi_ekskul_chalvind.git` | `git remote -v` menampilkan `origin` |
| B4 | Commit awal | `git add .gitignore && git commit -m "chore: inisialisasi project dan gitignore"` | `git log` menampilkan 1 commit |

### FASE C — Setup Database MySQL

| # | Langkah | Perintah / Aksi | Bukti |
|---|---|---|---|
| C1 | Buat file SQL | Tulis `database/presensi_ekskul.sql` sesuai Bagian 3.3 | File ada |
| C2 | Import database | `& "C:\xampp\mysql\bin\mysql.exe" -u root < database/presensi_ekskul.sql` | Tidak ada error |
| C3 | Verifikasi struktur | `... mysql.exe -u root -e "USE presensi_ekskul_chalvind; DESCRIBE presensi;"` | 7 kolom tampil sesuai skema |
| C4 | Verifikasi charset | `... -e "SHOW CREATE TABLE presensi_ekskul_chalvind.presensi\G"` | `utf8mb4` + `InnoDB` |

### FASE D — Setup Node + Tailwind CSS

| # | Langkah | Perintah / Aksi | Bukti |
|---|---|---|---|
| D1 | Buat `package.json` | `& npm.cmd init -y` lalu rapikan (name, private, scripts) | File ada, JSON valid |
| D2 | Install Tailwind | `& npm.cmd install -D tailwindcss@4 @tailwindcss/cli@4` | Muncul di `devDependencies` |
| D3 | Buat sumber CSS | Tulis `resources/css/input.css` (`@import "tailwindcss";` + blok `@source`) | File ada |
| D4 | Tambah scripts build | `"build": "tailwindcss -i ./resources/css/input.css -o ./assets/css/app.css --minify"` | `package.json` terupdate |
| D5 | Tambah script watch | `"dev": "tailwindcss -i ./resources/css/input.css -o ./assets/css/app.css --watch"` | `package.json` terupdate |
| D6 | Jalankan build pertama | `& npm.cmd run build` | Exit code `0` |
| D7 | Verifikasi hasil build | Cek `assets/css/app.css` | File ada, ukuran > 10 KB, berisi utility class |

> ⚠️ **Checkpoint 2:** kalau build gagal, **BERHENTI** dan laporkan error mentah ke user.

### FASE E — Setup Linter & Formatter

| # | Langkah | Perintah / Aksi | Bukti |
|---|---|---|---|
| E1 | Install dev deps | `& npm.cmd install -D eslint prettier prettier-plugin-tailwindcss globals` | Muncul di `devDependencies` |
| E2 | Buat config ESLint | Tulis `eslint.config.js` (flat config) | File ada |
| E3 | Buat config Prettier | Tulis `.prettierrc.json` + `.prettierignore` | File ada |
| E4 | Buat `.editorconfig` | Tulis file | File ada |
| E5 | Tambah scripts | `lint`, `lint:fix`, `format`, `format:check`, `verify` | `package.json` terupdate |
| E6 | Jalankan lint | `& npm.cmd run lint` | **0 error, 0 warning** |

Rancangan scripts di `package.json`:

```json
{
  "scripts": {
    "build":  "tailwindcss -i ./resources/css/input.css -o ./assets/css/app.css --minify",
    "dev":    "tailwindcss -i ./resources/css/input.css -o ./assets/css/app.css --watch",
    "lint":   "eslint .",
    "lint:fix":"eslint . --fix",
    "format": "prettier --write .",
    "format:check": "prettier --check .",
    "lint:php": "php -l config/koneksi.php && php -l includes/header.php && php -l includes/footer.php",
    "verify": "npm run lint && npm run lint:php && npm run build"
  }
}
```

### FASE F — Konfigurasi Koneksi PHP

| # | Langkah | Perintah / Aksi | Bukti |
|---|---|---|---|
| F1 | Buat `config/koneksi.php` | Koneksi mysqli + helper `setFlash()`, `getFlash()`, `redirect()` | File ada, lolos `php -l` |
| F2 | Test koneksi | Jalankan script cek koneksi | Koneksi **berhasil**, pesan sukses tampil |

### FASE G — Layout Dasar & Integrasi Tailwind

| # | Langkah | Perintah / Aksi | Bukti |
|---|---|---|---|
| G1 | Buat `includes/header.php` | `<!doctype html>`, `<head>`, link `assets/css/app.css`, navbar | File ada, lolos `php -l` |
| G2 | Buat `includes/footer.php` | Penutup `</body></html>` + `assets/js/app.js` | File ada, lolos `php -l` |
| G3 | Buat `assets/js/app.js` | Placeholder minimum (agar ESLint punya target) | File ada, lolos ESLint |
| G4 | Buat `_setup-check.php` | Halaman uji: koneksi DB + render kelas Tailwind | Halaman tampil benar |

> ℹ️ `_setup-check.php` adalah file **sementara** khusus verifikasi setup, dan **wajib dihapus** di Fase H.

### FASE H — Verifikasi Akhir & Dokumentasi

| # | Langkah | Perintah / Aksi | Bukti |
|---|---|---|---|
| H1 | Jalankan verifikasi menyeluruh | `& npm.cmd run verify` | Exit code `0` |
| H2 | Uji manual browser | Buka `http://localhost/presensi_ekskul_chalvind/_setup-check.php` | Tailwind ter-render, koneksi OK, **console browser bersih** |
| H3 | Hapus file sementara | Hapus `_setup-check.php` | File tidak ada lagi |
| H4 | Tulis `README.md` | Prasyarat, cara install, cara build, cara menjalankan | File ada & akurat |
| H5 | Commit & push | `git add . && git commit -m "chore: setup project, database, dan tailwind"` lalu `git push -u origin main` | Muncul di GitHub |

---

## 5. Definition of Done (DoD)

Setup dinyatakan **SELESAI** hanya jika **SEMUA** poin di bawah terbukti.

### 5.1 Tidak Ada Error

| # | Kriteria | Cara Verifikasi | Target |
|---|---|---|---|
| 1 | Tidak ada error syntax PHP | `php -l` pada setiap file `.php` | Semua `No syntax errors detected` |
| 2 | Tidak ada error di console browser | Buka halaman uji → DevTools Console | **0 error, 0 warning** |
| 3 | Tidak ada error build | `& npm.cmd run build` | Exit code `0` |
| 4 | Tidak ada error koneksi DB | Jalankan `_setup-check.php` | Koneksi `mysqli` sukses |
| 5 | Tidak ada warning deprecation PHP | Output halaman uji | Tidak ada notice/warning |
| 6 | Tidak ada file yang tertinggal | Cek `_setup-check.php` sudah terhapus | File tidak ada |

### 5.2 Berhasil Build

| # | Kriteria | Cara Verifikasi | Target |
|---|---|---|---|
| 7 | Build berjalan | `& npm.cmd run build` | Exit code `0` |
| 8 | Output CSS ter-generate | Cek `assets/css/app.css` | File ada, ukuran **> 10 KB** |
| 9 | Utility class ter-ikutkan | `Select-String "\.flex" assets/css/app.css` | Ditemukan |
| 10 | Tailwind ter-render di browser | Inspect element pada halaman uji | Rule Tailwind aktif, style ter-apply |
| 11 | Script watch berfungsi | `& npm.cmd run dev`, ubah `input.css`, refresh | CSS berubah otomatis |

### 5.3 Lolos ESLint

| # | Kriteria | Cara Verifikasi | Target |
|---|---|---|---|
| 12 | ESLint berjalan | `& npm.cmd run lint` | Exit code `0` |
| 13 | Tidak ada error ESLint | Output lint | **0 error** |
| 14 | Tidak ada warning ESLint | Output lint | **0 warning** |
| 15 | Prettier check lolos | `& npm.cmd run format:check` | Exit code `0` |

### 5.4 Sesuai Requirement Planning

| # | Kriteria | Cara Verifikasi | Target |
|---|---|---|---|
| 16 | Database terbentuk | `SHOW DATABASES;` | `presensi_ekskul_chalvind` ada |
| 17 | Tabel sesuai skema | `DESCRIBE presensi;` | Tepat **7 kolom** sesuai Bagian 3.3 |
| 18 | `status` hanya 4 nilai | Cek definisi kolom | `enum('Hadir','Izin','Sakit','Alpha')` |
| 19 | `keterangan` = `VARCHAR(100)` | `DESCRIBE presensi;` | Tipe & panjang benar |
| 20 | Charset benar | `SHOW CREATE TABLE` | `utf8mb4`, `InnoDB` |
| 21 | Struktur folder sesuai | Bandingkan dengan Bagian 3.1 | Sama |
| 22 | Tidak ada code CRUD | Cek root folder | Hanya file ber-tag `[SETUP]` |
| 23 | `README.md` bisa diikuti | Ikuti dari nol di folder bersih | Setup berhasil tanpa langkah tambahan |
| 24 | `.gitignore` bekerja | `git status` | `node_modules/` & `assets/css/app.css` **tidak** muncul |
| 25 | Remote terhubung | `git remote -v` | `origin` → repo `ChalvindJov24/presensi_ekskul_chalvind` |
| 26 | Push berhasil | `git push` | Commit terlihat di GitHub |

### 5.5 Bukti Serah Terima (Evidence)

Agent wajib menyerahkan **output mentah** (bukan ringkasan) dari:

1. `php -v`, `& npm.cmd -v`, `& "C:\xampp\mysql\bin\mysql.exe" --version`
2. `DESCRIBE presensi;` lengkap
3. `& npm.cmd run verify` lengkap
4. `git status` (menunjukkan `.gitignore` bekerja)
5. `git log --oneline`
6. Screenshot/hasil halaman `_setup-check.php` + console browser

---

## 6. Boundaries untuk AI Agent

### 6.1 Cakupan Kerja

| # | Batasan |
|---|---|
| B1 | Agent **HANYA** boleh membuat file yang terdaftar di Bagian 3.1 dengan tag `[SETUP]`. |
| B2 | Agent **DILARANG** membuat file CRUD (`index.php`, `tambah.php`, `simpan.php`, `edit.php`, `update.php`, `hapus.php`) pada fase ini. |
| B3 | Agent **DILARANG** menambah fitur di luar Bagian 1.7, sekecil apa pun ("sekalian bikin..."). |
| B4 | Agent **HARUS** berhenti di setiap **Checkpoint** dan menunggu konfirmasi user. |
| B5 | Agent **HARUS** bertanya sebelum mengambil keputusan teknis yang tidak tertulis di dokumen ini. |

### 6.2 Larangan Perubahan Sistem

| # | Batasan |
|---|---|
| B6 | **DILARANG** mengubah `php.ini` (`C:\xampp\php\php.ini`). |
| B7 | **DILARANG** mengubah ExecutionPolicy PowerShell. *Gunakan `npm.cmd`.* |
| B8 | **DILARANG** menginstall Apache/MySQL sebagai Windows Service. |
| B9 | **DILARANG** mengubah file konfigurasi XAMPP (`httpd.conf`, `my.ini`, dll). |
| B10 | **DILARANG** mengubah visibilitas repo GitHub tanpa izin eksplisit. |
| B11 | **DILARANG** `git push --force` atau menulis ulang history. |
| B12 | **DILARANG** menghapus/menimpa file yang bukan buatan agent pada fase ini. |

### 6.3 Larangan Teknis

| # | Batasan |
|---|---|
| B13 | **DILARANG** memakai Bootstrap, atau Tailwind via CDN. |
| B14 | **DILARANG** membuat `tailwind.config.js` (Tailwind v4 pakai CSS-first config). |
| B15 | **DILARANG** memakai framework/library PHP (Laravel, CodeIgniter, dll). |
| B16 | **DILARANG** memakai Composer untuk dependency PHP. |
| B17 | **DILARANG** mengubah `ENUM` pada kolom `status` (harus tepat 4 nilai). |
| B18 | **DILARANG** mengubah nama database/tabel/kolom dari Bagian 3.3. |
| B19 | **DILARANG** meng-commit `node_modules/` dan `assets/css/app.css`. |
| B20 | **DILARANG** menambah dependency npm di luar yang terdaftar di Bagian 4 (Fase D & E). |
| B21 | **DILARANG** memakai `mysqli` versi OOP — wajib **procedural** + prepared statement. |
| B22 | **DILARANG** menulis query dengan interpolasi string; wajib `bind_param`. |

### 6.4 Standar Kode

| # | Batasan |
|---|---|
| B23 | Semua output dinamis ke HTML **wajib** lewat `htmlspecialchars()`. |
| B24 | Komentar kode dan teks UI memakai **Bahasa Indonesia**. |
| B25 | Indentasi **4 spasi** untuk PHP, **2 spasi** untuk JSON/JS/CSS (lihat `.editorconfig`). |
| B26 | Encoding file **UTF-8 tanpa BOM**, line ending **LF**. |

### 6.5 Protokol Ketika Keluar dari Rencana

1. **BERHENTI** — jangan improvisasi.
2. **LAPORKAN**: langkah mana, error apa, kenapa di luar rencana.
3. **USULKAN** 2–3 opsi solusi beserta dampaknya.
4. **TUNGGU** keputusan user. Jangan lanjut sebelum dijawab.

---

## 7. Risiko & Mitigasi

| # | Risiko | Dampak | Mitigasi |
|---|---|---|---|
| R1 | ExecPolicy memblokir `npm.ps1` | npm tidak jalan | Selalu pakai `npm.cmd` |
| R2 | `gh` tidak dikenali di PATH | push/PR gagal | Pakai path lengkap `gh.exe` |
| R3 | Apache/MySQL belum distart | Import DB & browser test gagal | Start dari XAMPP Control Panel (langkah A1) |
| R4 | Port 80 dipakai aplikasi lain (IIS/Skype) | `http://localhost` gagal | Cek port; laporkan ke user, jangan ubah `httpd.conf` |
| R5 | ASCII/UTF-8 salah | Data karakter khusus rusak | `utf8mb4` + `SET NAMES utf8mb4` |
| R6 | Tailwind tidak men-scan file `.php` | Class tidak ter-generate | Tambah `@source` eksplisit di `input.css` |
| R7 | `assets/css/app.css` ter-commit | Repo kotor | Sudah masuk `.gitignore` |
| R8 | Node 24 + Tailwind v4 tidak kompatibel | Build gagal | Versi sudah dicek; jika gagal → Checkpoint 2 |

---

## 8. Pertanyaan yang Perlu Approval

Tolong jawab **SELURUHNYA** sebelum implementasi dimulai:

| # | Pertanyaan | Rekomendasi |
|---|---|---|
| Q1 | Tailwind **menggantikan** Bootstrap — setuju? *(keputusan awal adalah Bootstrap CDN)* | Ya, Tailwind |
| Q2 | `npm` dipanggil sebagai **`npm.cmd`** (tanpa mengubah sistem) — setuju? | Ya, `npm.cmd` |
| Q3 | Import SQL lewat **CLI `mysql.exe`** atau manual lewat **phpMyAdmin**? | CLI |
| Q4 | `assets/css/app.css` **tidak di-commit** (harus `npm run build` dulu) — setuju? | Ya (lebih bersih) |
| Q5 | File sementara **`_setup-check.php`** boleh dibuat lalu dihapus? | Ya |
| Q6 | `git init` + commit + push ke `main` masuk fase setup ini? | Ya |
| Q7 | **Prettier** ikut dipasang, atau cukup ESLint? | Ya, ikut |
| Q8 | Setelah setup selesai, langsung **PR ke GitHub**, atau **push ke `main`** dulu? | PR (sesuai alur yang disepakati) |
| Q9 | Nama file sumber Tailwind `resources/css/input.css` — setuju, atau mau `src/app.css`? | `resources/css/input.css` |

---

## 9. Roadmap Setelah Setup

Fase ini **berhenti** setelah DoD Bagian 5 terpenuhi. Pekerjaan berikutnya akan dibuat planning-nya masing-masing:

| Fase | Isi | Perlu approval baru? |
|---|---|---|
| Fase 2 | Read — `index.php` (list polos) | Ya |
| Fase 3 | Create — `tambah.php` + `simpan.php` | Ya |
| Fase 4 | Update — `edit.php` + `update.php` | Ya |
| Fase 5 | Delete — `hapus.php` | Ya |
| Fase 6 | Polish UI Tailwind + `README.md` final | Ya |
| Fase 7 | Push branch + Pull Request ke GitHub | Ya |

---

## 10. Ringkasan Alur Kerja

```
[1] User review issue.md
        │
        ▼
[2] User approve (+ jawab Q1–Q9)
        │
        ▼
[3] Agent implementasi Fase A → H, berhenti di Checkpoint
        │
        ▼
[4] Agent serahkan Evidence (Bagian 5.5)
        │
        ▼
[5] User approve hasil setup
        │
        ▼
[6] Lanjut ke Fase 2 (planning terpisah)
```

---

**Menunggu approval user untuk mulai implementasi.**
