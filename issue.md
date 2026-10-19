# Planning — Implementasi Fitur CRUD Presensi Ekstrakurikuler

> **Status dokumen:** MENUNGGU APPROVAL
> **Versi:** 1.0
> **Tanggal:** 19 Oktober 2026
> **Fase:** IMPLEMENTASI FITUR (CRUD + UI)
> **Prasyarat:** Fase setup (issue.md versi 1.0) **sudah selesai** dan terverifikasi.

---

## 1. Context

### 1.1 Latar Belakang

Fase setup sudah tuntas: struktur project, tooling (Tailwind, ESLint, Prettier), koneksi
database, layout dasar, dan database `presensi_ekskul_chalvind` (tabel `presensi`) sudah siap.

Fase ini mengisi **inti aplikasi**: seluruh operasi **CRUD** (Create, Read, Update, Delete)
beserta **UI yang rapi, bersih, dan konsisten** — tanpa kesan "AI slop".

Aplikasi tetap **PHP native + MySQL (mysqli procedural)** tanpa framework, dan tampilan tetap
memakai **Tailwind CSS** yang sudah terpasang.

### 1.2 Kondisi Saat Ini

| Item | Status |
|---|---|
| Database `presensi_ekskul_chalvind` | ✅ Ada |
| Tabel `presensi` (7 kolom) | ✅ Ada, sesuai skema |
| `config/koneksi.php` (+ `set_flash`, `get_flash`, `redirect`) | ✅ Ada |
| `includes/header.php`, `includes/footer.php` | ✅ Ada |
| `assets/js/app.js` (berisi `konfirmasiHapus`) | ✅ Ada |
| Tailwind build (`assets/css/app.css`) | ✅ Berjalan (`npm run build`) |
| Halaman CRUD (`index`, `tambah`, `simpan`, `edit`, `update`, `hapus`) | ❌ **Belum ada** |

### 1.3 Tujuan Planning

1. Menetapkan **spesifikasi perilaku** tiap halaman CRUD secara eksplisit.
2. Menetapkan **standar UI/UX** yang konkret agar hasil konsisten dan tidak "AI slop".
3. Menetapkan **aturan validasi & keamanan** yang wajib dipatuhi.
4. Menjadi **kontrak kerja**: agent mengerjakan hanya yang tertulis, dan berhenti untuk bertanya
   jika keluar dari lingkup ini.
5. Menyediakan **Definition of Done** yang objektif dan bisa diverifikasi.

### 1.4 Ruang Lingkup

**✅ Termasuk (In Scope):**
- Halaman **Read** (`index.php`) — daftar presensi dalam bentuk **tabel**.
- Halaman **Create** (`tambah.php` + `simpan.php`) — form + proses simpan dengan validasi.
- Halaman **Update** (`edit.php` + `update.php`) — form terisi + proses update dengan validasi.
- Halaman **Delete** (`hapus.php`) — hapus satu baris dengan konfirmasi.
- Partial/helper bersama agar form dan rendering tidak duplikatif.
- Flash message (sukses/gagal) dan empty state.
- Badge status berwarna untuk 4 nilai ENUM.
- Polish UI + pembaruan `README.md` (bagian penggunaan aplikasi).

**❌ Tidak Termasuk (Out of Scope):**
- Autentikasi / login / hak akses.
- Multi-user / relasi tabel (relasi ekskul–peserta master).
- Export/import (Excel/PDF/CSV).
- REST API / integrasi eksternal.
- Dark mode.
- Penggantian framework atau library PHP.
- Deployment/hosting.
- Pencarian/filter/pagination → **opsional**, lihat Bagian 11 (pertanyaan approval).

---

## 2. Keputusan Teknis

| Kode | Keputusan | Nilai |
|---|---|---|
| D1 | Akses database | **mysqli procedural** + **prepared statement** (`bind_param`) |
| D2 | Arsitektur | Multi-halaman (server-rendered), **tanpa** JS framework |
| D3 | Validasi | Validasi di **server (PHP)**; atribut HTML hanya bantu UX, bukan pengganti |
| D4 | Output ke HTML | Wajib `htmlspecialchars()` |
| D5 | CSS | **Tailwind CSS** yang sudah terpasang (utility-first) |
| D6 | Komponen UI | **Tailwind murni** — **tanpa menambah dependency baru** (termasuk daisyUI); lihat 2.1 |
| D7 | Konfirmasi hapus | `window.confirm` via `konfirmasiHapus` yang **sudah ada** di `assets/js/app.js` |
| D8 | Pesan hasil | **Flash message** lewat `set_flash()` / `get_flash()` |
| D9 | Bahasa | Komentar kode, teks UI, dan pesan: **Bahasa Indonesia** |
| D10 | Format tanggal tampil | `dd-mm-yyyy` (atau format Indonesia), disimpan sebagai `DATE` (`Y-m-d`) |
| D11 | Indentasi | **4 spasi** PHP, **2 spasi** JS/CSS/JSON (mengikuti `.editorconfig` yang ada) |
| D12 | Encoding & EOL | UTF-8 tanpa BOM, line ending **LF** |

### 2.1 Tailwind Table vs daisyUI

Permintaanmu: *"untuk tampilkan data gunakan table saja dari Tailwind, atau jika perlu install
daisyUI."*

| Opsi | Kelebihan | Kekurangan | Biaya |
|---|---|---|---|
| **A. Tailwind murni (REKOMENDASI)** | Tidak menambah dependency; konsisten dengan `header`/`footer` yang sudah ada; bobot CSS kecil; kendali penuh atas tampilan | Perlu menulis class tabel/badge sendiri | 0 dependency baru |
| B. Tambah daisyUI | Komponen siap pakai (table, badge, btn) | Dependency baru + konfigurasi tema; mudah terlihat "template" generik; proyek ini hanya satu tabel sederhana | 1 dependency baru |

**Rekomendasi: Opsi A (Tailwind murni).** Untuk satu tabel sederhana, menulis komponen sendiri
lebih ringan dan menghasilkan UI yang lebih "disengaja", bukan sekadar mengikuti default komponen
library. Keputusan final ada di Bagian 11 (Q1).

### 2.2 Prinsip Desain — Anti "AI Slop"

Aturan berikut **wajib** dipatuhi agar UI terlihat rapi, konsisten, dan tidak generik:

1. **Satu warna aksen saja.** Palet: netral `slate` + aksen `indigo`. Tidak ada warna acak.
2. **Tanpa dekorasi berlebihan:** dilarang gradien, glow, glassmorphism, blob/particle, dan animasi
   berlebihan. Gunakan `shadow-sm`, bukan `shadow-2xl`.
3. **Tanpa emoji** di teks UI. Gunakan teks atau ikon SVG inline sederhana bila perlu.
4. **Konsisten spasial:** skala jarak kelipatan 4 (`p-4`, `py-2.5`, `gap-4`), radius `rounded-lg`
   untuk kontrol dan `rounded-xl` untuk kartu.
5. **Tipografi terbatas:** font sistem (default Tailwind), maksimal 2 tingkat ketebalan
   (`font-medium`, `font-semibold`).
6. **Border + bayangan tipis:** `border border-slate-200` + `shadow-sm`. Tidak menumpuk bayangan.
7. **Konten nyata:** dilarang angka/statistik palsu atau teks placeholder yang tidak berfungsi.
8. **State jelas:** selalu ada kondisi *empty*, *error*, dan *sukses* yang eksplisit.
9. **Konsisten kapitalisasi:** judul *Title Case*, isi *sentence case*.
10. **Aksesibel:** setiap input punya `<label>`; fokus terlihat (`focus:ring-2`); kontras teks wajar.

---

## 3. Struktur Folder & File

### 3.1 File Baru / Diubah

```
presensi_ekskul_chalvind/
│
├── index.php                       [CRUD - Read]   daftar presensi (tabel)
├── tambah.php                      [CRUD - Create] form kosong
├── simpan.php                      [CRUD - Create] proses INSERT (tanpa tampilan)
├── edit.php                        [CRUD - Update] form terisi
├── update.php                      [CRUD - Update] proses UPDATE (tanpa tampilan)
├── hapus.php                       [CRUD - Delete] proses DELETE (tanpa tampilan)
│
├── includes/
│   ├── fungsi.php                  [BARU] helper bersama (badge, format, old input, validasi)
│   └── form-presensi.php           [BARU] partial form dipakai tambah.php & edit.php
│
├── package.json                    [DIUBAH] script `lint:php` melint semua file PHP baru
├── README.md                       [DIUBAH] tambah bagian cara pakai aplikasi
└── assets/js/app.js                [kemungkinan DIUBAH kecil] bila perlu penyesuaian UX
```

### 3.2 Tanggung Jawab Tiap File

| File | Peran | Menampilkan HTML? |
|---|---|---|
| `index.php` | Query daftar, render tabel, flash, empty state, tombol Tambah | Ya |
| `tambah.php` | Render form kosong (+ error/old input bila ada) | Ya |
| `simpan.php` | Terima `POST`, validasi, `INSERT`, redirect | Tidak |
| `edit.php` | Terima `GET id`, ambil 1 baris, render form terisi | Ya |
| `update.php` | Terima `POST`, validasi, `UPDATE`, redirect | Tidak |
| `hapus.php` | Terima `id`, hapus, redirect | Tidak |
| `includes/fungsi.php` | Helper: badge status, format tanggal, ambil/bersihkan old input & error | Tidak |
| `includes/form-presensi.php` | Partial form (label, input, error per field) untuk Create & Update | Ya |

> **Prinsip DRY:** form `tambah.php` dan `edit.php` berbagi *partial* yang sama, dibedakan oleh
> variabel nilai awal dan aksi tujuan (`simpan.php` vs `update.php`).

### 3.3 Alur Request

```
[Read]   Browser → index.php ──SELECT──> MySQL → render tabel
[Create] tambah.php (form) → POST simpan.php → validasi → INSERT → redirect index.php (+flash)
[Update] index.php → edit.php?id=N → UPDATE form → POST update.php → validasi → UPDATE → redirect (+flash)
[Delete] index.php → hapus.php?id=N (konfirmasi JS) → DELETE → redirect index.php (+flash)
```

---

## 4. Pemetaan Data & Validasi

### 4.1 Kolom ↔ Input Form

| Kolom DB | Label UI | Jenis input | Wajib | Batas |
|---|---|---|---|---|
| `id_presensi` | — (tidak ditampilkan di form) | auto | — | — |
| `nama_peserta` | Nama Peserta | teks | Ya | 3–100 karakter |
| `kelas` | Kelas | teks | Ya | 1–20 karakter |
| `ekstrakurikuler` | Ekstrakurikuler | teks | Ya | 1–50 karakter |
| `tanggal` | Tanggal Kegiatan | date | Ya | format `Y-m-d`, valid |
| `status` | Status | select | Ya | salah satu dari 4 ENUM |
| `keterangan` | Keterangan | teks/textarea | Tidak | maks 100 karakter |

### 4.2 Aturan Validasi (server-side)

| Field | Aturan | Pesan error (contoh) |
|---|---|---|
| `nama_peserta` | wajib; setelah `trim` minimal 3, maksimal 100 | "Nama peserta wajib diisi (3–100 karakter)." |
| `kelas` | wajib; maksimal 20 | "Kelas wajib diisi (maksimal 20 karakter)." |
| `ekstrakurikuler` | wajib; maksimal 50 | "Ekstrakurikuler wajib diisi (maksimal 50 karakter)." |
| `tanggal` | wajib; format tanggal valid (`Y-m-d`) | "Tanggal kegiatan tidak valid." |
| `status` | wajib; harus tepat salah satu: Hadir, Izin, Sakit, Alpha | "Status tidak valid." |
| `keterangan` | opsional; maksimal 100; kosong → `NULL` | "Keterangan maksimal 100 karakter." |

Catatan:
- Panjang dihitung dengan **jumlah karakter** yang aman untuk `utf8mb4` (mis. `mb_strlen`).
- Bila validasi gagal: **jangan simpan**, kembalikan ke form asal bersama **old input** dan **daftar
  error per field**.
- Aturan tambahan (opsional, lihat Q2): `tanggal` **tidak boleh melebihi hari ini**.

### 4.3 Normalisasi Sebelum Simpan

- `trim()` semua input teks; rapikan spasi ganda.
- Konversi `keterangan` kosong menjadi `NULL`.
- `id` pada `edit`/`update`/`hapus` dipaksa menjadi bilangan bulat (`(int)`).

---

## 5. Spesifikasi UI/UX

### 5.1 Design Token (kelas Tailwind yang dipakai konsisten)

| Peran | Nilai |
|---|---|
| Latar halaman | `bg-slate-50` (mengikuti body yang sudah ada: `bg-slate-100`) |
| Permukaan/kartu | `bg-white border border-slate-200 rounded-xl shadow-sm` |
| Teks utama | `text-slate-800` |
| Teks sekunder | `text-slate-500` |
| Aksen (utama) | `indigo-600` (hover `indigo-700`), fokus `ring-indigo-500` |
| Bahaya (hapus) | `rose-600` (hover `rose-700`) |
| Fokus | `focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-1` |

**Warna badge status:**

| Status | Kelas badge |
|---|---|
| Hadir | `bg-emerald-50 text-emerald-700 ring-emerald-600/20` |
| Izin | `bg-amber-50 text-amber-700 ring-amber-600/20` |
| Sakit | `bg-sky-50 text-sky-700 ring-sky-600/20` |
| Alpha | `bg-rose-50 text-rose-700 ring-rose-600/20` |

### 5.2 Komponen

**Tabel (`index.php`):**
- Dibungkus kartu putih; tabel `min-w-full divide-y divide-slate-200`.
- Header: `bg-slate-50 text-xs font-semibold uppercase tracking-wide text-slate-500`.
- Baris: `hover:bg-slate-50`; sel `px-4 py-3 text-sm`.
- Kolom: No, Nama Peserta, Kelas, Ekstrakurikuler, Tanggal, Status (badge), Keterangan, Aksi.
- Kolom Aksi: tombol teks **Edit** (netral/indigo) dan **Hapus** (rose).
- Responsif: wrapper `overflow-x-auto` agar bisa di-scroll di layar kecil.

**Badge status:** inline-flex, `px-2.5 py-0.5 text-xs font-medium rounded-full ring-1 ring-inset`.

**Tombol:**
- Primer: `bg-indigo-600 text-white hover:bg-indigo-700 rounded-lg px-4 py-2 text-sm font-medium`.
- Sekunder/batal: `bg-white text-slate-700 border border-slate-300 hover:bg-slate-50`.
- Bahaya: `bg-rose-600 text-white hover:bg-rose-700`.

**Form:**
- Kartu putih, lebar maksimal nyaman (`max-w-2xl`).
- Setiap field: `<label>` (`text-sm font-medium text-slate-700`) + input/select
  (`w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500`).
- Error per field: teks kecil `text-rose-600` tepat di bawah input + border input menjadi
  `border-rose-400` bila error.
- Aksi: tombol **Simpan** (primer) + **Batal** (sekunder, kembali ke `index.php`).

**Flash message:**
- Sukses: `bg-emerald-50 text-emerald-800 border border-emerald-200`.
- Gagal: `bg-rose-50 text-rose-800 border border-rose-200`.
- Diletakkan di atas konten; ditutup otomatis manual (tanpa JS wajib).

**Empty state:**
- Bila tabel kosong: tampilkan pesan tenang "Belum ada data presensi." + tombol "Tambah Presensi"
  pertama. Tidak ada ilustrasi berlebihan.

### 5.3 Daftar Halaman & Judul

| Halaman | `$judul_halaman` |
|---|---|
| `index.php` | "Daftar Presensi" |
| `tambah.php` | "Tambah Presensi" |
| `edit.php` | "Edit Presensi" |

### 5.4 Responsif & Aksesibilitas

- Tabel dibungkus `overflow-x-auto`; form tetap satu kolom di mobile.
- Semua kontrol punya `label` yang terasosiasi (`for`/`id`).
- Urutan tab wajar; fokus selalu terlihat.
- Teks tombol deskriptif ("Simpan Presensi", bukan "OK").

---

## 6. Rincian Perilaku Tiap Halaman

### 6.1 `index.php` (Read)
- Berisi `config/koneksi.php`, `includes/fungsi.php`, `includes/header.php`.
- Ambil seluruh baris dari tabel `presensi`, urut **`tanggal DESC`, lalu `id_presensi DESC`**
  (terbaru di atas).
- Tampilkan flash message (bila ada) lalu kartu tabel.
- Menampilkan nomor urut kolom "No" berbasis posisi (bukan `id`).
- Tombol **Tambah Presensi** mengarah ke `tambah.php`.
- Bila 0 baris → tampilkan *empty state*.

### 6.2 `tambah.php` (Create — form)
- Ambil old input & error dari session (bila ada), lalu bersihkan.
- Field kosong (tanggal default: hari ini).
- Submit `POST` ke `simpan.php`.

### 6.3 `simpan.php` (Create — proses)
- Tolak akses selain `POST` (kembalikan ke `tambah.php`).
- Ambil input, normalisasi, validasi (Bagian 4.2).
- Jika gagal: `set_flash`/simpan error + old input → redirect ke `tambah.php`.
- Jika sukses: `INSERT` prepared statement → `set_flash` sukses → redirect `index.php`.

### 6.4 `edit.php` (Update — form)
- Terima `GET id`; jika kosong/tidak valid → flash gagal → redirect `index.php`.
- `SELECT` baris berdasarkan `id` (prepared statement). Jika tidak ditemukan → flash gagal →
  redirect `index.php`.
- Render form terisi. Prioritas nilai: **old input** (bila baru gagal validasi) → data DB.
- Submit `POST` ke `update.php` (menyertakan `id` tersembunyi).

### 6.5 `update.php` (Update — proses)
- Tolak akses selain `POST`.
- Ambil `id` + input, normalisasi, validasi (sama seperti simpan).
- Jika gagal: redirect kembali ke `edit.php?id=N` dengan error + old input.
- Jika sukses: `UPDATE` prepared statement → flash sukses → redirect `index.php`.
- Bila `id` tidak ada di DB → flash gagal → redirect `index.php`.

### 6.6 `hapus.php` (Delete — proses)
- Terima `id`; jika kosong/tidak valid → flash gagal → redirect `index.php`.
- `DELETE` prepared statement berdasarkan `id`.
- Flash sukses ("Data presensi berhasil dihapus.") atau gagal → redirect `index.php`.
- Konfirmasi di sisi klien memakai `konfirmasiHapus` yang sudah ada di `assets/js/app.js`.

---

## 7. Step by Step Implementasi

> Agent mengerjakan **urut**, **satu langkah per satu**, dan **berhenti di setiap Checkpoint**.
> Tidak ada instalasi dependency pada fase ini.

### FASE 1 — Fondasi Bersama
| # | Langkah | Bukti |
|---|---|---|
| 1.1 | Buat `includes/fungsi.php` (badge status, format tanggal, old input & error) | File ada, `php -l` bersih |
| 1.2 | Buat `includes/form-presensi.php` (partial form) | File ada, `php -l` bersih |
| 1.3 | Perbarui `lint:php` di `package.json` agar mencakup semua file PHP baru | Script jalan, `php -l` semua file |

> ⚠️ **Checkpoint 1:** tampilkan isi `fungsi.php` & `form-presensi.php` untuk ditinjau sebelum lanjut.

### FASE 2 — Read (`index.php`)
| # | Langkah | Bukti |
|---|---|---|
| 2.1 | Buat `index.php` (query + tabel + flash + empty state) | Halaman tampil, `php -l` bersih |
| 2.2 | Uji di browser (data kosong & data ada) | Tabel/empty state benar |

> ⚠️ **Checkpoint 2:** minta konfirmasi tampilan `index.php` sebelum lanjut.

### FASE 3 — Create (`tambah.php` + `simpan.php`)
| # | Langkah | Bukti |
|---|---|---|
| 3.1 | Buat `tambah.php` | Form tampil |
| 3.2 | Buat `simpan.php` (validasi + INSERT) | Data tersimpan, redirect + flash |
| 3.3 | Uji: input valid → tersimpan; input invalid → error per field | Sesuai Bagian 4.2 |

> ⚠️ **Checkpoint 3:** konfirmasi alur Create sebelum lanjut.

### FASE 4 — Update (`edit.php` + `update.php`)
| # | Langkah | Bukti |
|---|---|---|
| 4.1 | Buat `edit.php` (form terisi) | Form terisi benar |
| 4.2 | Buat `update.php` (validasi + UPDATE) | Data berubah, flash tampil |
| 4.3 | Uji: id tidak ada → redirect + flash gagal | Ditangani |

> ⚠️ **Checkpoint 4:** konfirmasi alur Update sebelum lanjut.

### FASE 5 — Delete (`hapus.php`)
| # | Langkah | Bukti |
|---|---|---|
| 5.1 | Buat `hapus.php` (DELETE + konfirmasi) | Data terhapus, flash tampil |
| 5.2 | Uji: batal konfirmasi → tidak terhapus | Sesuai harapan |

> ⚠️ **Checkpoint 5:** konfirmasi alur Delete sebelum lanjut.

### FASE 6 — Polish UI & Dokumentasi
| # | Langkah | Bukti |
|---|---|---|
| 6.1 | Rapikan konsistensi UI (jarak, badge, tombol, fokus) | Tampilan konsisten |
| 6.2 | Tambah bagian "Cara Pakai Aplikasi" di `README.md` | README akurat |
| 6.3 | `npm run verify` | Exit code `0` |

### FASE 7 — Verifikasi & Serah Terima
| # | Langkah | Bukti |
|---|---|---|
| 7.1 | Uji end-to-end keempat operasi CRUD | Semua berfungsi |
| 7.2 | Cek console browser bersih | 0 error/warning |
| 7.3 | Commit & push ke branch `feat/crud-presensi` | Muncul di GitHub |
| 7.4 | (Opsional) Buka Pull Request | PR siap direview |

---

## 8. Definition of Done (DoD)

Fitur CRUD dinyatakan **SELESAI** hanya jika **SEMUA** poin berikut terbukti.

### 8.1 Fungsi CRUD
| # | Kriteria | Target |
|---|---|---|
| 1 | Create berfungsi | Data baru masuk ke tabel, tampil di daftar |
| 2 | Read berfungsi | Semua baris tampil dalam tabel, urut tanggal terbaru |
| 3 | Update berfungsi | Perubahan tersimpan & tampil |
| 4 | Delete berfungsi | Baris terhapus dari tabel |
| 5 | Konfirmasi hapus muncul | Tombol batal tidak menghapus data |

### 8.2 Validasi & Keamanan
| # | Kriteria | Target |
|---|---|---|
| 6 | Field wajib divalidasi | Input kosong ditolak dengan pesan per field |
| 7 | Batas panjang divalidasi | Melebihi batas ditolak |
| 8 | `status` hanya 4 nilai | Nilai di luar ENUM ditolak |
| 9 | Query prepared statement | **Tidak ada** interpolasi string di query |
| 10 | Output di-escape | Semua output dinamis lewat `htmlspecialchars()` |
| 11 | `id` selalu cast integer | `edit`/`update`/`hapus` tidak error saat id aneh |

### 8.3 UI/UX (Anti AI Slop)
| # | Kriteria | Target |
|---|---|---|
| 12 | Konsisten dengan Bagian 2.2 & 5 | Satu aksen, tanpa dekorasi berlebihan |
| 13 | Badge status 4 warna berbeda | Hadir/Izin/Sakit/Alpha jelas |
| 14 | Empty state ada | Tampil saat tabel kosong |
| 15 | Flash sukses & gagal tampil | Setelah setiap aksi |
| 16 | Error per field tampil | Bila validasi gagal |
| 17 | Responsif | Tabel bisa di-scroll di mobile |
| 18 | Aksesibel dasar | Label, fokus terlihat |

### 8.4 Teknis
| # | Kriteria | Target |
|---|---|---|
| 19 | Tidak ada error syntax PHP | `php -l` semua file: bersih |
| 20 | ESLint bersih | 0 error, 0 warning |
| 21 | `npm run verify` | Exit code `0` |
| 22 | Console browser bersih | 0 error/warning |
| 23 | Tanpa dependency baru | `package.json` devDependencies **tidak** bertambah |
| 24 | `.gitignore` tetap bekerja | `node_modules/` & `assets/css/app.css` tidak ter-commit |

### 8.5 Bukti Serah Terima (Evidence)
Agent menyerahkan **output mentah** (bukan ringkasan) dari:
1. `php -l` untuk setiap file PHP baru.
2. `npm run verify` lengkap.
3. `git status` (branch `feat/crud-presensi`).
4. Cuplikan query yang dipakai (untuk membuktikan prepared statement).
5. Screenshot/deskripsi hasil tiap operasi CRUD di browser + console bersih.

---

## 9. Boundaries untuk AI Agent

### 9.1 Cakupan Kerja
| # | Batasan |
|---|---|
| B1 | Agent **hanya** membuat file pada Bagian 3.1. |
| B2 | Agent **dilarang** menambah fitur di luar Bagian 1.4 ("sekalian bikin..."). |
| B3 | Agent **harus** berhenti di setiap **Checkpoint** dan menunggu konfirmasi. |
| B4 | Agent **harus** bertanya sebelum keputusan teknis yang tidak tertulis di dokumen ini. |
| B5 | Agent **wajib** mengerjakan satu langkah per satu, tidak mengeksekusi banyak task sekaligus. |

### 9.2 Larangan Perubahan Sistem
| # | Batasan |
|---|---|
| B6 | **Dilarang** mengubah skema database (nama DB/tabel/kolom/ENUM). |
| B7 | **Dilarang** mengubah konfigurasi XAMPP (`httpd.conf`, `my.ini`, `php.ini`). |
| B8 | **Dilarang** menginstall/menambah dependency npm/PHP (termasuk daisyUI) tanpa approval. |
| B9 | **Dilarang** `git push --force` atau menulis ulang history. |
| B10 | **Dilarang** menghapus/menimpa file yang bukan bagian dari fase ini. |

### 9.3 Larangan Teknis
| # | Batasan |
|---|---|
| B11 | **Dilarang** memakai framework PHP / Composer. |
| B12 | **Dilarang** memakai mysqli versi OOP — wajib **procedural**. |
| B13 | **Dilarang** menulis query dengan interpolasi string; wajib `bind_param`. |
| B14 | **Dilarang** memakai Bootstrap / Tailwind via CDN / daisyUI (tanpa approval). |
| B15 | **Dilarang** membuat `tailwind.config.js`. |
| B16 | **Dilarang** menampilkan nilai tanpa `htmlspecialchars()`. |
| B17 | **Dilarang** mengandalkan validasi HTML saja tanpa validasi server. |

### 9.4 Standar Kode
| # | Batasan |
|---|---|
| B18 | Komentar kode & teks UI: **Bahasa Indonesia**. |
| B19 | Indentasi 4 spasi PHP, 2 spasi JS/CSS/JSON. |
| B20 | UTF-8 tanpa BOM, line ending LF. |
| B21 | Format tanggal tampil Indonesia (`dd-mm-yyyy`), simpan `Y-m-d`. |

### 9.5 Protokol Ketika Keluar dari Rencana
1. **BERHENTI** — jangan improvisasi.
2. **LAPORKAN**: langkah mana, error apa, kenapa di luar rencana.
3. **USULKAN** 2–3 opsi solusi beserta dampaknya.
4. **TUNGGU** keputusan user.

---

## 10. Risiko & Mitigasi

| # | Risiko | Dampak | Mitigasi |
|---|---|---|---|
| R1 | Validasi hanya di HTML | Data kotor masuk DB | Validasi wajib di server |
| R2 | Interpolasi string di query | Celah injection | Wajib prepared statement + `bind_param` |
| R3 | XSS pada kolom teks | Script jahat tereksekusi | `htmlspecialchars()` di semua output |
| R4 | `id` non-angka di URL | Error/warning PHP | Cast `(int)` + cek keberadaan baris |
| R5 | Form ter-resubmit (refresh) | Data dobel | Redirect-after-POST (pola POST→redirect→GET) |
| R6 | UI terlihat generik | Kesan "AI slop" | Patuhi Bagian 2.2 & 5 |
| R7 | File PHP baru tak ter-lint | Error syntax lolos | Perbarui script `lint:php` (langkah 1.3) |
| R8 | Duplikasi kode form | Sulit dirawat | Gunakan partial `form-presensi.php` |

---

## 11. Pertanyaan yang Perlu Approval

Tolong jawab sebelum implementasi dimulai:

| # | Pertanyaan | Rekomendasi |
|---|---|---|
| Q1 | Tampilan tabel memakai **Tailwind murni** (tanpa daisyUI)? | **Ya** (tanpa dependency baru) |
| Q2 | `tanggal` kegiatan **tidak boleh melebihi hari ini**? | Ya (validasi sopan) |
| Q3 | Hapus pakai **`GET` + konfirmasi JS** (sesuai `app.js` yang ada)? | Ya |
| Q4 | Kolom **Keterangan** ditampilkan di tabel (bisa kosong)? | Ya, tampilkan "-" bila kosong |
| Q5 | Fitur **pencarian/filter** (`index.php`) masuk fase ini atau fase terpisah? | **Fase terpisah** (agar tiap fase kecil & komputer aman) |
| Q6 | Bila Q5 = fase terpisah, apakah **pagination** juga dipisah? | Ya, dipisah |
| Q7 | Bekerja di branch **`feat/crud-presensi`**, lalu PR? | Ya |
| Q8 | Urutan kolom tabel: No, Nama, Kelas, Ekskul, Tanggal, Status, Keterangan, Aksi — setuju? | Ya |

---

## 12. Ringkasan Alur Kerja

```
[1] User review issue.md (planning CRUD)
        │
        ▼
[2] User approve (+ jawab Q1–Q8)
        │
        ▼
[3] Agent implementasi Fase 1 → 7, SATU LANGKAH PER SATU, berhenti di Checkpoint
        │
        ▼
[4] Agent serahkan Evidence (Bagian 8.5)
        │
        ▼
[5] User approve hasil
        │
        ▼
[6] (Opsional) Fase terpisah: pencarian, filter, pagination, polish lanjutan
```

---

**Menunggu approval user untuk mulai implementasi.**
