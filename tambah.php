<?php
/**
 * Halaman tambah presensi (Create) — menampilkan form kosong.
 *
 * Bila sebelumnya validasi gagal di simpan.php, form diisi kembali dengan
 * old input beserta pesan error per field.
 */

require_once __DIR__ . '/config/koneksi.php';
require_once __DIR__ . '/includes/fungsi.php';

$judul_halaman = 'Tambah Presensi';

$old = ambil_old();
$error = ambil_error();

// Nilai awal form: old input bila ada, selain itu default.
$form_nilai = [
    'nama_peserta' => nilai_lama($old, 'nama_peserta'),
    'kelas' => nilai_lama($old, 'kelas'),
    'ekstrakurikuler' => nilai_lama($old, 'ekstrakurikuler'),
    'tanggal' => nilai_lama($old, 'tanggal', date('Y-m-d')),
    'status' => nilai_lama($old, 'status'),
    'keterangan' => nilai_lama($old, 'keterangan'),
];
$form_error = $error;
$form_aksi = 'simpan.php';
$form_tombol = 'Simpan Presensi';
$form_id = null;

require __DIR__ . '/includes/header.php';
?>

<div class="mx-auto max-w-2xl">
    <div>
        <h1 class="text-xl font-semibold text-slate-800">Tambah Presensi</h1>
        <p class="mt-1 text-sm text-slate-500">Isi data kehadiran peserta kegiatan ekstrakurikuler.</p>
    </div>

    <div class="mt-6 rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
        <?php require __DIR__ . '/includes/form-presensi.php'; ?>
    </div>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
