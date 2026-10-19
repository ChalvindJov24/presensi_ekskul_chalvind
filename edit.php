<?php
/**
 * Halaman edit presensi (Update) — menampilkan form terisi.
 *
 * Membaca satu baris berdasarkan id dari query string. Bila id tidak valid
 * atau data tidak ditemukan, pengguna dialihkan kembali ke daftar. Bila
 * sebelumnya validasi gagal di update.php, old input & error ditampilkan.
 */

require_once __DIR__ . '/config/koneksi.php';
require_once __DIR__ . '/includes/fungsi.php';

$judul_halaman = 'Edit Presensi';

$id = (int) ($_GET['id'] ?? 0);

if ($id <= 0) {
    set_flash('gagal', 'Data presensi tidak ditemukan.');
    redirect('index.php');
}

// Ambil data berdasarkan id (prepared statement).
$sql = 'SELECT id_presensi, nama_peserta, kelas, ekstrakurikuler, tanggal, status, keterangan
        FROM presensi
        WHERE id_presensi = ?
        LIMIT 1';
$stmt = mysqli_prepare($koneksi, $sql);
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$hasil = mysqli_stmt_get_result($stmt);
$baris = mysqli_fetch_assoc($hasil);
mysqli_stmt_close($stmt);

if (!$baris) {
    set_flash('gagal', 'Data presensi tidak ditemukan.');
    redirect('index.php');
}

$old = ambil_old();
$error = ambil_error();

// Nilai form: old input bila baru gagal validasi, selain itu data dari database.
if (!empty($old)) {
    $form_nilai = $old;
} else {
    $form_nilai = [
        'nama_peserta' => $baris['nama_peserta'],
        'kelas' => $baris['kelas'],
        'ekstrakurikuler' => $baris['ekstrakurikuler'],
        'tanggal' => $baris['tanggal'],
        'status' => $baris['status'],
        'keterangan' => $baris['keterangan'] ?? '',
    ];
}

$form_error = $error;
$form_aksi = 'update.php';
$form_tombol = 'Perbarui Presensi';
$form_id = $id;

require __DIR__ . '/includes/header.php';
?>

<div class="mx-auto max-w-2xl">
    <div>
        <h1 class="text-xl font-semibold text-slate-800">Edit Presensi</h1>
        <p class="mt-1 text-sm text-slate-500">Perbarui data kehadiran peserta kegiatan ekstrakurikuler.</p>
    </div>

    <div class="mt-6 rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
        <?php require __DIR__ . '/includes/form-presensi.php'; ?>
    </div>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
