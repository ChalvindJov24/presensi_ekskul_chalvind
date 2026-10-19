<?php
/**
 * Proses hapus presensi (Delete) — tanpa tampilan.
 *
 * Menerima id dari query string. Konfirmasi dilakukan di sisi klien melalui
 * fungsi konfirmasiHapus pada assets/js/app.js.
 */

require_once __DIR__ . '/config/koneksi.php';
require_once __DIR__ . '/includes/fungsi.php';

$id = (int) ($_GET['id'] ?? 0);

if ($id <= 0) {
    set_flash('gagal', 'Data presensi tidak ditemukan.');
    redirect('index.php');
}

$sql = 'DELETE FROM presensi WHERE id_presensi = ?';

try {
    $stmt = mysqli_prepare($koneksi, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $terhapus = mysqli_stmt_affected_rows($stmt);
    mysqli_stmt_close($stmt);

    if ($terhapus > 0) {
        set_flash('sukses', 'Data presensi berhasil dihapus.');
    } else {
        set_flash('gagal', 'Data presensi tidak ditemukan.');
    }
} catch (mysqli_sql_exception $e) {
    set_flash('gagal', 'Data presensi gagal dihapus.');
}

redirect('index.php');
