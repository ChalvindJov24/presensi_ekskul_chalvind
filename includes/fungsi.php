<?php
/**
 * Kumpulan fungsi bantu untuk halaman CRUD presensi.
 *
 * File ini di-include oleh halaman yang membutuhkan rendering badge,
 * format tanggal, penyimpanan old input/error, dan validasi form.
 */

/**
 * Daftar status presensi yang valid (sesuai ENUM kolom `status`).
 *
 * @return string[]
 */
function daftar_status()
{
    return ['Hadir', 'Izin', 'Sakit', 'Alpha'];
}

/**
 * Membuat markup badge status presensi dengan warna sesuai jenisnya.
 *
 * @param string $status Nilai status: Hadir, Izin, Sakit, atau Alpha.
 * @return string Markup HTML badge siap tampil.
 */
function badge_status($status)
{
    $warna = [
        'Hadir' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
        'Izin' => 'bg-amber-50 text-amber-700 ring-amber-600/20',
        'Sakit' => 'bg-sky-50 text-sky-700 ring-sky-600/20',
        'Alpha' => 'bg-rose-50 text-rose-700 ring-rose-600/20',
    ];

    $kelas = $warna[$status] ?? 'bg-slate-100 text-slate-700 ring-slate-500/20';

    return '<span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium ring-1 ring-inset '
        . $kelas . '">' . htmlspecialchars($status) . '</span>';
}

/**
 * Mengubah tanggal format database (Y-m-d) menjadi format tampilan (d-m-Y).
 *
 * @param string|null $tanggal Tanggal dari database.
 * @return string Tanggal yang siap tampil, atau "-" bila kosong/tidak valid.
 */
function format_tanggal($tanggal)
{
    if (empty($tanggal)) {
        return '-';
    }

    $waktu = strtotime($tanggal);

    if ($waktu === false) {
        return '-';
    }

    return date('d-m-Y', $waktu);
}

/**
 * Menyimpan old input ke session agar bisa dikembalikan ke form.
 *
 * @param array $data Data input yang baru saja dikirim pengguna.
 * @return void
 */
function simpan_old($data)
{
    $_SESSION['old'] = $data;
}

/**
 * Mengambil sekaligus menghapus old input dari session.
 *
 * @return array Data old input, atau array kosong bila tidak ada.
 */
function ambil_old()
{
    $data = $_SESSION['old'] ?? [];
    unset($_SESSION['old']);

    return $data;
}

/**
 * Menyimpan daftar error validasi per field ke session.
 *
 * @param array $error Daftar error dengan kunci nama field.
 * @return void
 */
function simpan_error($error)
{
    $_SESSION['error'] = $error;
}

/**
 * Mengambil sekaligus menghapus daftar error validasi dari session.
 *
 * @return array Daftar error, atau array kosong bila tidak ada.
 */
function ambil_error()
{
    $error = $_SESSION['error'] ?? [];
    unset($_SESSION['error']);

    return $error;
}

/**
 * Mengambil nilai lama sebuah field, dengan nilai default bila tidak ada.
 *
 * @param array  $data    Data old input.
 * @param string $kunci   Nama field.
 * @param string $default Nilai default.
 * @return string Nilai field.
 */
function nilai_lama($data, $kunci, $default = '')
{
    if (isset($data[$kunci]) && $data[$kunci] !== '') {
        return (string) $data[$kunci];
    }

    return (string) $default;
}

/**
 * Mengambil pesan error untuk sebuah field.
 *
 * @param array  $error Daftar error validasi.
 * @param string $kunci Nama field.
 * @return string Pesan error, atau string kosong bila tidak ada.
 */
function pesan_error($error, $kunci)
{
    return $error[$kunci] ?? '';
}

/**
 * Mengambil dan membersihkan input presensi dari $_POST.
 *
 * @return array Input yang sudah di-`trim`.
 */
function ambil_input_presensi()
{
    return [
        'nama_peserta' => trim($_POST['nama_peserta'] ?? ''),
        'kelas' => trim($_POST['kelas'] ?? ''),
        'ekstrakurikuler' => trim($_POST['ekstrakurikuler'] ?? ''),
        'tanggal' => trim($_POST['tanggal'] ?? ''),
        'status' => trim($_POST['status'] ?? ''),
        'keterangan' => trim($_POST['keterangan'] ?? ''),
    ];
}

/**
 * Memvalidasi input presensi sesuai aturan planning.
 *
 * @param array $data Input presensi hasil `ambil_input_presensi()`.
 * @return array Daftar error dengan kunci nama field (kosong bila valid).
 */
function validasi_presensi($data)
{
    $error = [];

    // Nama peserta: wajib, 3-100 karakter.
    if ($data['nama_peserta'] === '') {
        $error['nama_peserta'] = 'Nama peserta wajib diisi.';
    } elseif (mb_strlen($data['nama_peserta']) < 3 || mb_strlen($data['nama_peserta']) > 100) {
        $error['nama_peserta'] = 'Nama peserta harus 3-100 karakter.';
    }

    // Kelas: wajib, maksimal 20 karakter.
    if ($data['kelas'] === '') {
        $error['kelas'] = 'Kelas wajib diisi.';
    } elseif (mb_strlen($data['kelas']) > 20) {
        $error['kelas'] = 'Kelas maksimal 20 karakter.';
    }

    // Ekstrakurikuler: wajib, maksimal 50 karakter.
    if ($data['ekstrakurikuler'] === '') {
        $error['ekstrakurikuler'] = 'Ekstrakurikuler wajib diisi.';
    } elseif (mb_strlen($data['ekstrakurikuler']) > 50) {
        $error['ekstrakurikuler'] = 'Ekstrakurikuler maksimal 50 karakter.';
    }

    // Tanggal: wajib, format valid, tidak boleh melebihi hari ini.
    if ($data['tanggal'] === '') {
        $error['tanggal'] = 'Tanggal kegiatan wajib diisi.';
    } else {
        $waktu = strtotime($data['tanggal']);

        if ($waktu === false || date('Y-m-d', $waktu) !== $data['tanggal']) {
            $error['tanggal'] = 'Tanggal kegiatan tidak valid.';
        } elseif ($data['tanggal'] > date('Y-m-d')) {
            $error['tanggal'] = 'Tanggal kegiatan tidak boleh melebihi hari ini.';
        }
    }

    // Status: wajib, harus salah satu dari 4 nilai ENUM.
    if ($data['status'] === '') {
        $error['status'] = 'Status wajib dipilih.';
    } elseif (!in_array($data['status'], daftar_status(), true)) {
        $error['status'] = 'Status tidak valid.';
    }

    // Keterangan: opsional, maksimal 100 karakter.
    if ($data['keterangan'] !== '' && mb_strlen($data['keterangan']) > 100) {
        $error['keterangan'] = 'Keterangan maksimal 100 karakter.';
    }

    return $error;
}
