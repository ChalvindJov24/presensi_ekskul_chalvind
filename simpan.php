<?php
/**
 * Proses simpan presensi (Create) — tanpa tampilan.
 *
 * Menerima POST dari tambah.php, memvalidasi, lalu menyimpan ke database.
 * Bila gagal validasi, old input & error dikembalikan ke form.
 */

require_once __DIR__ . '/config/koneksi.php';
require_once __DIR__ . '/includes/fungsi.php';

// Hanya menerima metode POST.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('tambah.php');
}

$input = ambil_input_presensi();
$error = validasi_presensi($input);

// Validasi gagal: kembalikan ke form bersama old input & error.
if (!empty($error)) {
    simpan_old($input);
    simpan_error($error);
    redirect('tambah.php');
}

// Keterangan kosong disimpan sebagai NULL.
$keterangan = $input['keterangan'] === '' ? null : $input['keterangan'];

$sql = 'INSERT INTO presensi
            (nama_peserta, kelas, ekstrakurikuler, tanggal, status, keterangan)
        VALUES (?, ?, ?, ?, ?, ?)';

try {
    $stmt = mysqli_prepare($koneksi, $sql);
    mysqli_stmt_bind_param(
        $stmt,
        'ssssss',
        $input['nama_peserta'],
        $input['kelas'],
        $input['ekstrakurikuler'],
        $input['tanggal'],
        $input['status'],
        $keterangan
    );
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    set_flash('sukses', 'Data presensi berhasil disimpan.');
} catch (mysqli_sql_exception $e) {
    set_flash('gagal', 'Data presensi gagal disimpan.');
}

redirect('index.php');
