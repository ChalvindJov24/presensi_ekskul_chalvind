-- =====================================================================
-- Database  : presensi_ekskul_chalvind
-- Tabel     : presensi
-- Deskripsi : Script pembuatan database dan tabel untuk sistem presensi
--             ekstrakurikuler (CRUD satu tabel).
-- Dibuat    : 19 Oktober 2026
-- =====================================================================

SET NAMES utf8mb4;

-- ---------------------------------------------------------------------
-- 1. Membuat database
-- ---------------------------------------------------------------------
CREATE DATABASE IF NOT EXISTS presensi_ekskul_chalvind
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_general_ci;

USE presensi_ekskul_chalvind;

-- ---------------------------------------------------------------------
-- 2. Membuat tabel presensi
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS presensi (
  id_presensi     INT AUTO_INCREMENT PRIMARY KEY,
  nama_peserta    VARCHAR(100) NOT NULL,
  kelas           VARCHAR(20)  NOT NULL,
  ekstrakurikuler VARCHAR(50)  NOT NULL,
  tanggal         DATE         NOT NULL,
  status          ENUM('Hadir','Izin','Sakit','Alpha') NOT NULL,
  keterangan      VARCHAR(100) NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
