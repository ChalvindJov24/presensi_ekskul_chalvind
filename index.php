<?php
/**
 * Halaman daftar presensi (Read).
 *
 * Menampilkan seluruh data presensi dalam bentuk tabel, lengkap dengan
 * flash message, tombol tambah, serta aksi edit/hapus tiap baris.
 */

require_once __DIR__ . '/config/koneksi.php';
require_once __DIR__ . '/includes/fungsi.php';

$judul_halaman = 'Daftar Presensi';

// Ambil seluruh data, terbaru di atas.
$sql = 'SELECT id_presensi, nama_peserta, kelas, ekstrakurikuler, tanggal, status, keterangan
        FROM presensi
        ORDER BY tanggal DESC, id_presensi DESC';
$hasil = mysqli_query($koneksi, $sql);

if (!$hasil) {
    die('Gagal mengambil data presensi: ' . htmlspecialchars(mysqli_error($koneksi)));
}

$flash = get_flash();

require __DIR__ . '/includes/header.php';
?>

<div>
    <h1 class="text-xl font-semibold text-slate-800">Daftar Presensi</h1>
    <p class="mt-1 text-sm text-slate-500">Catatan kehadiran peserta kegiatan ekstrakurikuler.</p>
</div>

<?php if ($flash !== null) : ?>
    <?php $kelas_flash = $flash['tipe'] === 'sukses'
        ? 'border-emerald-200 bg-emerald-50 text-emerald-800'
        : 'border-rose-200 bg-rose-50 text-rose-800'; ?>
    <div class="mt-6 rounded-lg border px-4 py-3 text-sm <?= $kelas_flash ?>">
        <?= htmlspecialchars($flash['pesan']) ?>
    </div>
<?php endif; ?>

<div class="mt-6 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
    <?php if (mysqli_num_rows($hasil) === 0) : ?>
        <div class="px-6 py-16 text-center">
            <p class="text-sm font-medium text-slate-700">Belum ada data presensi.</p>
            <p class="mt-1 text-sm text-slate-500">
                Mulai dengan menambahkan catatan kehadiran peserta.
            </p>
            <a
                href="tambah.php"
                class="mt-4 inline-block rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-1"
            >
                Tambah Presensi
            </a>
        </div>
    <?php else : ?>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">No</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Nama Peserta</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Kelas</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Ekstrakurikuler</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Tanggal</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Keterangan</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    <?php $no = 1; ?>
                    <?php while ($baris = mysqli_fetch_assoc($hasil)) : ?>
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-3 text-sm text-slate-500"><?= $no ?></td>
                            <td class="px-4 py-3 text-sm font-medium text-slate-800">
                                <?= htmlspecialchars($baris['nama_peserta']) ?>
                            </td>
                            <td class="px-4 py-3 text-sm text-slate-600">
                                <?= htmlspecialchars($baris['kelas']) ?>
                            </td>
                            <td class="px-4 py-3 text-sm text-slate-600">
                                <?= htmlspecialchars($baris['ekstrakurikuler']) ?>
                            </td>
                            <td class="px-4 py-3 text-sm text-slate-600">
                                <?= htmlspecialchars(format_tanggal($baris['tanggal'])) ?>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <?= badge_status($baris['status']) ?>
                            </td>
                            <td class="px-4 py-3 text-sm text-slate-600">
                                <?php if ($baris['keterangan'] === null || $baris['keterangan'] === '') : ?>
                                    <span class="text-slate-400">-</span>
                                <?php else : ?>
                                    <?= htmlspecialchars($baris['keterangan']) ?>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3 text-right text-sm">
                                <div class="flex items-center justify-end gap-3">
                                    <a
                                        href="edit.php?id=<?= (int) $baris['id_presensi'] ?>"
                                        class="font-medium text-indigo-600 hover:text-indigo-700"
                                    >
                                        Edit
                                    </a>
                                    <a
                                        href="hapus.php?id=<?= (int) $baris['id_presensi'] ?>"
                                        data-nama="<?= htmlspecialchars($baris['nama_peserta']) ?>"
                                        class="font-medium text-rose-600 hover:text-rose-700"
                                        onclick="return konfirmasiHapus(event, this.dataset.nama)"
                                    >
                                        Hapus
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php $no++; ?>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
