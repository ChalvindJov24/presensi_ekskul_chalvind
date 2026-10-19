/**
 * Skrip global aplikasi presensi ekstrakurikuler.
 * Dimuat di setiap halaman melalui includes/footer.php.
 */
(function () {
  "use strict";

  /**
   * Meminta konfirmasi sebelum data presensi dihapus.
   *
   * @param {Event} event Peristiwa klik pada tautan hapus.
   * @param {string} namaPeserta Nama peserta yang akan dihapus.
   * @returns {boolean} Bernilai true jika pengguna menyetujui penghapusan.
   */
  function konfirmasiHapus(event, namaPeserta) {
    const pesan = "Yakin ingin menghapus data presensi " + namaPeserta + "?";
    const disetujui = window.confirm(pesan);

    if (!disetujui) {
      event.preventDefault();
    }

    return disetujui;
  }

  window.konfirmasiHapus = konfirmasiHapus;
})();
