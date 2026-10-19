<?php
/**
 * Partial form presensi.
 *
 * Dipakai bersama oleh tambah.php (Create) dan edit.php (Update) agar
 * markup form tidak duplikatif. Halaman pemanggil wajib menyiapkan:
 *
 *   $form_aksi   (string)  URL tujuan submit, mis. "simpan.php".
 *   $form_tombol (string)  Label tombol submit, mis. "Simpan Presensi".
 *   $form_nilai  (array)   Nilai tiap field.
 *   $form_error  (array)   Daftar error validasi per field.
 *   $form_id     (int|null) id_presensi; diisi hanya saat mode Update.
 */

$form_id = $form_id ?? null;
$form_nilai = $form_nilai ?? [];
$form_error = $form_error ?? [];

$kelas_dasar = 'w-full rounded-lg border px-3 py-2 text-sm text-slate-800 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-1';
$kelas_normal = $kelas_dasar . ' border-slate-300';
$kelas_gagal = $kelas_dasar . ' border-rose-400';

/**
 * Memilih kelas input sesuai ada tidaknya error pada field terkait.
 *
 * @param array  $error Daftar error validasi.
 * @param string $kunci Nama field.
 * @return string Kelas Tailwind untuk elemen input.
 */
function kelas_field($error, $kunci)
{
    global $kelas_normal, $kelas_gagal;

    return pesan_error($error, $kunci) !== '' ? $kelas_gagal : $kelas_normal;
}
?>
<form action="<?= htmlspecialchars($form_aksi) ?>" method="post" class="space-y-6">
    <?php if (!empty($form_id)) : ?>
        <input type="hidden" name="id_presensi" value="<?= (int) $form_id ?>" />
    <?php endif; ?>

    <div>
        <label for="nama_peserta" class="block text-sm font-medium text-slate-700">
            Nama Peserta
        </label>
        <input
            type="text"
            id="nama_peserta"
            name="nama_peserta"
            maxlength="100"
            value="<?= htmlspecialchars(nilai_lama($form_nilai, 'nama_peserta')) ?>"
            class="mt-1 <?= kelas_field($form_error, 'nama_peserta') ?>"
        />
        <?php if (pesan_error($form_error, 'nama_peserta') !== '') : ?>
            <p class="mt-1 text-xs text-rose-600">
                <?= htmlspecialchars(pesan_error($form_error, 'nama_peserta')) ?>
            </p>
        <?php endif; ?>
    </div>

    <div>
        <label for="kelas" class="block text-sm font-medium text-slate-700">Kelas</label>
        <input
            type="text"
            id="kelas"
            name="kelas"
            maxlength="20"
            value="<?= htmlspecialchars(nilai_lama($form_nilai, 'kelas')) ?>"
            class="mt-1 <?= kelas_field($form_error, 'kelas') ?>"
        />
        <?php if (pesan_error($form_error, 'kelas') !== '') : ?>
            <p class="mt-1 text-xs text-rose-600">
                <?= htmlspecialchars(pesan_error($form_error, 'kelas')) ?>
            </p>
        <?php endif; ?>
    </div>

    <div>
        <label for="ekstrakurikuler" class="block text-sm font-medium text-slate-700">
            Ekstrakurikuler
        </label>
        <input
            type="text"
            id="ekstrakurikuler"
            name="ekstrakurikuler"
            maxlength="50"
            value="<?= htmlspecialchars(nilai_lama($form_nilai, 'ekstrakurikuler')) ?>"
            class="mt-1 <?= kelas_field($form_error, 'ekstrakurikuler') ?>"
        />
        <?php if (pesan_error($form_error, 'ekstrakurikuler') !== '') : ?>
            <p class="mt-1 text-xs text-rose-600">
                <?= htmlspecialchars(pesan_error($form_error, 'ekstrakurikuler')) ?>
            </p>
        <?php endif; ?>
    </div>

    <div>
        <label for="tanggal" class="block text-sm font-medium text-slate-700">
            Tanggal Kegiatan
        </label>
        <input
            type="date"
            id="tanggal"
            name="tanggal"
            value="<?= htmlspecialchars(nilai_lama($form_nilai, 'tanggal')) ?>"
            class="mt-1 <?= kelas_field($form_error, 'tanggal') ?>"
        />
        <?php if (pesan_error($form_error, 'tanggal') !== '') : ?>
            <p class="mt-1 text-xs text-rose-600">
                <?= htmlspecialchars(pesan_error($form_error, 'tanggal')) ?>
            </p>
        <?php endif; ?>
    </div>

    <div>
        <label for="status" class="block text-sm font-medium text-slate-700">Status</label>
        <select id="status" name="status" class="mt-1 <?= kelas_field($form_error, 'status') ?>">
            <option value="">-- Pilih status --</option>
            <?php foreach (daftar_status() as $pilihan) : ?>
                <option
                    value="<?= htmlspecialchars($pilihan) ?>"
                    <?= nilai_lama($form_nilai, 'status') === $pilihan ? 'selected' : '' ?>
                >
                    <?= htmlspecialchars($pilihan) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <?php if (pesan_error($form_error, 'status') !== '') : ?>
            <p class="mt-1 text-xs text-rose-600">
                <?= htmlspecialchars(pesan_error($form_error, 'status')) ?>
            </p>
        <?php endif; ?>
    </div>

    <div>
        <label for="keterangan" class="block text-sm font-medium text-slate-700">
            Keterangan <span class="font-normal text-slate-400">(opsional)</span>
        </label>
        <input
            type="text"
            id="keterangan"
            name="keterangan"
            maxlength="100"
            value="<?= htmlspecialchars(nilai_lama($form_nilai, 'keterangan')) ?>"
            class="mt-1 <?= kelas_field($form_error, 'keterangan') ?>"
        />
        <?php if (pesan_error($form_error, 'keterangan') !== '') : ?>
            <p class="mt-1 text-xs text-rose-600">
                <?= htmlspecialchars(pesan_error($form_error, 'keterangan')) ?>
            </p>
        <?php endif; ?>
    </div>

    <div class="flex items-center gap-3 border-t border-slate-200 pt-6">
        <button
            type="submit"
            class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-1"
        >
            <?= htmlspecialchars($form_tombol) ?>
        </button>
        <a
            href="index.php"
            class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-1"
        >
            Batal
        </a>
    </div>
</form>
