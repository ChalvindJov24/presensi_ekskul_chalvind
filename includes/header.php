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
?>
<!doctype html>
<html lang="id">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title><?= htmlspecialchars($judul_halaman) ?> | <?= htmlspecialchars($app_name) ?></title>
        <link rel="stylesheet" href="assets/css/app.css" />
    </head>
    <body class="min-h-screen bg-slate-100 text-slate-800 antialiased">
        <header class="bg-slate-900 text-white shadow-md">
            <div class="mx-auto flex max-w-6xl items-center justify-between px-4 py-4">
                <a href="index.php" class="text-lg font-semibold tracking-tight">
                    <?= htmlspecialchars($app_name) ?>
                </a>
                <nav class="text-sm text-slate-300">
                    <span>Sistem Presensi Ekstrakurikuler</span>
                </nav>
            </div>
        </header>

        <main class="mx-auto max-w-6xl px-4 py-8">
