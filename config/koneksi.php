<?php
/**
 * Konfigurasi koneksi database + fungsi bantu.
 *
 * Project : presensi_ekskul_chalvind
 * Database: presensi_ekskul_chalvind (MySQL/MariaDB - XAMPP)
 *
 * File ini di-include oleh seluruh halaman aplikasi.
 */

// ---------------------------------------------------------------------
// Pengaturan database (nilai default XAMPP)
// ---------------------------------------------------------------------
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'presensi_ekskul_chalvind');
define('DB_PORT', 3306);

// Zona waktu aplikasi
date_default_timezone_set('Asia/Jakarta');

// Jalankan session sekali saja (dipakai untuk flash message)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ---------------------------------------------------------------------
// Membuat koneksi (mysqli procedural)
// ---------------------------------------------------------------------
$koneksi = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);

if (!$koneksi) {
    die('Koneksi database gagal: ' . mysqli_connect_error());
}

mysqli_set_charset($koneksi, 'utf8mb4');

// ---------------------------------------------------------------------
// Fungsi bantu
// ---------------------------------------------------------------------

/**
 * Menyimpan pesan sementara (flash message) ke dalam session.
 *
 * @param string $tipe  Jenis pesan: "sukses" atau "gagal".
 * @param string $pesan Isi pesan yang ditampilkan ke pengguna.
 * @return void
 */
function set_flash($tipe, $pesan)
{
    $_SESSION['flash'] = [
        'tipe' => $tipe,
        'pesan' => $pesan,
    ];
}

/**
 * Mengambil sekaligus menghapus flash message dari session.
 *
 * @return array|null Array berisi "tipe" dan "pesan", atau null jika kosong.
 */
function get_flash()
{
    if (empty($_SESSION['flash'])) {
        return null;
    }

    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);

    return $flash;
}

/**
 * Mengalihkan pengguna ke halaman lain, lalu menghentikan eksekusi.
 *
 * @param string $tujuan Alamat halaman tujuan.
 * @return void
 */
function redirect($tujuan)
{
    header('Location: ' . $tujuan);
    exit;
}
