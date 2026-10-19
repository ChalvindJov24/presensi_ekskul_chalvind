<?php
/**
 * Bagian atas halaman: <head>, navbar, dan pembuka <body>.
 *
 * Variabel opsional sebelum include:
 *   $judul_halaman (string) Judul yang tampil di tab browser.
 */

if (!isset($judul_halaman)) {
    $judul_halaman = 'Presensi Ekstrakurikuler';
}

$app_name = 'Presensi Ekskul';
$tahun = date('Y');

// Status navigasi: menentukan tautan navbar yang sedang aktif.
$halaman_aktif = basename($_SERVER['PHP_SELF']);
$aktif_daftar = in_array($halaman_aktif, ['index.php', 'edit.php'], true);
$aktif_tambah = $halaman_aktif === 'tambah.php';
$kelas_nav = 'rounded-lg px-3 py-1.5 text-sm font-medium text-slate-300 hover:bg-white/5 hover:text-white';
$kelas_nav_aktif = 'rounded-lg bg-white/10 px-3 py-1.5 text-sm font-medium text-white';
?>
<!doctype html>
<html lang="id">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title><?= htmlspecialchars($judul_halaman) ?> | <?= htmlspecialchars($app_name) ?></title>
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link
            href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap"
            rel="stylesheet"
        />
        <link rel="stylesheet" href="assets/css/app.css" />
    </head>
    <body class="flex min-h-screen flex-col bg-slate-100 text-slate-800 antialiased">
        <header class="bg-slate-900 text-white shadow-md">
            <div class="mx-auto flex max-w-6xl items-center justify-between px-4 py-4">
                <a href="index.php" class="text-lg font-semibold tracking-tight">
                    <?= htmlspecialchars($app_name) ?>
                </a>
                <nav class="flex items-center gap-1">
                    <a
                        href="index.php"
                        class="<?= $aktif_daftar ? $kelas_nav_aktif : $kelas_nav ?>"
                        <?= $aktif_daftar ? 'aria-current="page"' : '' ?>
                    >
                        Daftar Presensi
                    </a>
                    <a
                        href="tambah.php"
                        class="<?= $aktif_tambah ? $kelas_nav_aktif : $kelas_nav ?>"
                        <?= $aktif_tambah ? 'aria-current="page"' : '' ?>
                    >
                        Tambah Presensi
                    </a>
                </nav>
            </div>
        </header>

        <main class="mx-auto w-full max-w-6xl flex-1 px-4 py-8">
