<?php
/**
 * Proses perbarui presensi (Update) — tanpa tampilan.
 *
 * Menerima POST dari edit.php, memvalidasi, lalu memperbarui baris sesuai id.
 * Bila gagal validasi, pengguna dikembalikan ke edit.php bersama old input.
 */

require_once __DIR__ . '/config/koneksi.php';
require_once __DIR__ . '/includes/fungsi.php';

// Hanya menerima metode POST.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('index.php');
}

$id = (int) ($_POST['id_presensi'] ?? 0);

if ($id <= 0) {
    set_flash('gagal', 'Data presensi tidak ditemukan.');
    redirect('index.php');
}

$input = ambil_input_presensi();
$error = validasi_presensi($input);

// Validasi gagal: kembalikan ke form edit bersama old input & error.
if (!empty($error)) {
    simpan_old($input);
    simpan_error($error);
    redirect('edit.php?id=' . $id);
}

// Pastikan baris dengan id tersebut benar-benar ada.
$cekJumlah = mysqli_prepare($koneksi, 'SELECT id_presensi FROM presensi WHERE id_presensi = ? LIMIT 1');
mysqli_stmt_bind_param($cekJumlah, 'i', $id);
mysqli_stmt_execute($cekJumlah);
mysqli_stmt_store_result($cekJumlah);
$ada = mysqli_stmt_num_rows($cekJumlah) > 0;
mysqli_stmt_close($cekJumlah);

if (!$ada) {
    set_flash('gagal', 'Data presensi tidak ditemukan.');
    redirect('index.php');
}

// Keterangan kosong disimpan sebagai NULL.
$keterangan = $input['keterangan'] === '' ? null : $input['keterangan'];

$sql = 'UPDATE presensi
        SET nama_peserta = ?, kelas = ?, ekstrakurikuler = ?, tanggal = ?, status = ?, keterangan = ?
        WHERE id_presensi = ?';

try {
    $stmt = mysqli_prepare($koneksi, $sql);
    mysqli_stmt_bind_param(
        $stmt,
        'ssssssi',
        $input['nama_peserta'],
        $input['kelas'],
        $input['ekstrakurikuler'],
        $input['tanggal'],
        $input['status'],
        $keterangan,
        $id
    );
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    set_flash('sukses', 'Data presensi berhasil diperbarui.');
} catch (mysqli_sql_exception $e) {
    set_flash('gagal', 'Data presensi gagal diperbarui.');
}

redirect('index.php');
