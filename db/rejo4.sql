-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 08, 2025 at 07:04 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rejo4`
--

-- --------------------------------------------------------

--
-- Table structure for table `absensi`
--

CREATE TABLE `absensi` (
  `id_absensi` int(11) NOT NULL,
  `id_kelas` varchar(5) NOT NULL,
  `id_ta` varchar(5) NOT NULL,
  `semester` varchar(2) NOT NULL,
  `id_wali_kelas` varchar(5) NOT NULL,
  `id_siswa` varchar(10) NOT NULL,
  `hadir` int(3) NOT NULL,
  `sakit` int(3) NOT NULL,
  `izin` int(3) NOT NULL,
  `alfa` int(3) NOT NULL,
  `tinggi` int(11) NOT NULL,
  `berat` int(11) NOT NULL,
  `penglihatan` varchar(25) NOT NULL,
  `pendengaran` varchar(25) NOT NULL,
  `gigi` varchar(25) NOT NULL,
  `penyakit_lainnya` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `absensi`
--

INSERT INTO `absensi` (`id_absensi`, `id_kelas`, `id_ta`, `semester`, `id_wali_kelas`, `id_siswa`, `hadir`, `sakit`, `izin`, `alfa`, `tinggi`, `berat`, `penglihatan`, `pendengaran`, `gigi`, `penyakit_lainnya`) VALUES
(1, '1', '1', '1', '', '1', 0, 1, 1, 1, 0, 0, '', '', '', ''),
(2, '1', '1', '1', '', '2', 0, 1, 1, 1, 0, 0, '', '', '', ''),
(3, '2', '1', '1', '', '3', 0, 1, 1, 1, 0, 0, '', '', '', ''),
(4, '2', '1', '1', '', '4', 0, 1, 1, 1, 0, 0, '', '', '', ''),
(5, '3', '1', '1', '', '5', 0, 1, 1, 1, 0, 0, '', '', '', ''),
(6, '3', '1', '1', '', '6', 0, 1, 1, 1, 0, 0, '', '', '', ''),
(7, '4', '1', '1', '', '7', 0, 1, 1, 1, 0, 0, '', '', '', ''),
(8, '4', '1', '1', '', '8', 0, 1, 1, 1, 0, 0, '', '', '', ''),
(9, '5', '1', '1', '', '9', 0, 1, 1, 1, 0, 0, '', '', '', ''),
(10, '5', '1', '1', '', '10', 0, 1, 1, 1, 0, 0, '', '', '', ''),
(11, '6', '1', '1', '', '11', 0, 1, 1, 1, 0, 0, '', '', '', ''),
(12, '6', '1', '1', '', '12', 0, 1, 1, 1, 0, 0, '', '', '', ''),
(13, '1', '1', '2', '', '1', 0, 1, 1, 1, 0, 0, '', '', '', ''),
(14, '1', '1', '2', '', '2', 0, 1, 1, 1, 0, 0, '', '', '', ''),
(15, '1', '1', '1', '', '3', 0, 1, 0, 0, 0, 0, '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(3) NOT NULL,
  `nama_admin` varchar(25) NOT NULL,
  `username` varchar(35) NOT NULL,
  `password` text NOT NULL,
  `level` varchar(15) NOT NULL,
  `foto` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `nama_admin`, `username`, `password`, `level`, `foto`) VALUES
(1, 'Administrator', '111', '111', 'Admin', '');

-- --------------------------------------------------------

--
-- Table structure for table `guru`
--

CREATE TABLE `guru` (
  `id_guru` int(11) NOT NULL,
  `nama_guru` varchar(25) NOT NULL,
  `jk` varchar(15) NOT NULL,
  `tmpl` varchar(25) NOT NULL,
  `tgll` date NOT NULL,
  `nip` varchar(25) NOT NULL,
  `gol` varchar(25) NOT NULL,
  `jabatan` varchar(25) NOT NULL,
  `mulai_masuk` varchar(25) NOT NULL,
  `alamat` text NOT NULL,
  `nohp` varchar(15) NOT NULL,
  `ijazah_tahun` varchar(25) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `guru`
--

INSERT INTO `guru` (`id_guru`, `nama_guru`, `jk`, `tmpl`, `tgll`, `nip`, `gol`, `jabatan`, `mulai_masuk`, `alamat`, `nohp`, `ijazah_tahun`) VALUES
(1, 'ULFIYANTI, S.Pd.SD.', 'P', 'Magelang', '1969-08-18', '19690818 200312 2 002', 'PENATA - III/C', 'KEPALA SEKOLAH', '', 'JALAN KANON BLOK H-7 JOGIN I RT 04 RW 10 JOGONEGORO MERTOYUDAN KABUPATEN MAGELANG', '-', 'S1/2010'),
(2, 'SEPTI TRI LESTARI, S.Pd.', 'P', 'MAGELANG', '1967-06-25', '19710918 200801 2 006', 'PENATA MUDA TK I-III/B', 'GURU KELAS ', '01 FEBRUARI 2022', 'GRAHA SURYA JALAN CEMPAKA 2 RT 01 RW 01 MERTOYUDAN KABUPATEN MAGELANG', '-', 'S1/2014'),
(5, 'NINING NUR\'ANI, S.Pd.SD.', 'P', 'SEMARANG', '1970-10-05', '19850401 200903 2 006', 'PENATA MUDA TK I - III/B', 'GURU KELAS', '03 JANUARI 2021', 'DUSUN GENTAN RT 002 RW 007 DESA DONOREJO MERTOYUDAN KABUPATEN MAGELANG\r\n', '00000999', 'S1 UT-2011	'),
(6, 'TUTIK DARYANINGSIH, S.Pd.', 'P', 'MAGELANG', '1969-04-16', '19691029 200801 2 010', 'PENATA MUDA - III/A', 'GURU KELAS ', '01 SEPTEMBER 2019', 'BOGEMAN TIMUR    RT 01 RW 02 PANJANG MAGELANG TENGAH KOTA MAGELANG', '-', 'S1/2013'),
(7, 'SUSETYO ADHI NUGRAHANTO, ', 'L', 'MAGELANG', '1984-11-12', '19901006 202012 1 002', 'PENATA MUDA-III/A', 'GURU KELAS ', '02 JANUARI 2021', 'DUSUN KEPATRAN RT 07 RW 04 DESA BANJARSARI GRABAG KABUPATEN MAGELANG', '-', 'S1/2015'),
(8, 'Silvester Tri Aji K., A.M', 'L', 'MAGELANG', '1984-02-28', '19790104 200903 1 002', 'PENGATUR - II/C', 'GURU KELAS ', '01 JULI 2015', 'JALAN SUNAN KALIJAGA 4 NO 44 RT 04 RW 04 JURANGOMBO SELATAN KOTA MAGELANG', '-', 'D2/2004'),
(9, 'Sulistiyani, S.Pd.', 'P', 'Magelang', '1988-10-10', '19920325 202221 2 004', 'IX', 'Guru Penjaskes', '01 MARET 2019', 'PAJANGAN RT 05 RW 05 KRAMAT SELATN MAGELANG UTARA KOTA MAGELANG', '-', 'S1/2014'),
(10, 'Dhewi Kusyanti, S.Pd.I', 'P', 'MAGELANG', '1991-05-06', '19921113 202221 2 002', 'IX', 'GURU PAI', '07 JANUARI 2020', 'KENDAL RT 002 RW 017 RAMBEANAK MUNGKID KABUPATEN MAGELANG', '-', 'S1/2016'),
(11, 'MAGDALENA TRI NOFIASTUTI,', 'P', 'MAGELANG', '1987-03-09', '19791102 200903 2 002', 'PENATA MUDA TINGKAT I-III', 'GURU PA KATOLIK', '11 JULI 2022', 'PERUM BUMI GEMILANG BLOK G2-35 BANJARNEGORO MERTOYUDAN KABUPATEN MAGELANG', '-', 'S1/2018'),
(12, 'SITI ARDANI, S.Pd.', 'P', 'MAGELANG', '1993-12-31', '-', '-', 'GURU KELAS ', '03 JANUARI 2022', 'KORIPAN RT 04 RW 05 DAWUNG KECAMATAN TEGALREJO KABUPATEN MAGELANG', '-', 'S1/2018'),
(13, 'ERFIN YUDHI ARYANI, S.Pd.', 'P', 'MAGELANG', '0000-00-00', '-', '-', 'TAS', '01 JULI 2005', 'CACABAN BARAT RT 03 RW 10 KOTA MAGELANG', '', 'S1/2003'),
(14, 'RIFA NURBAETI MARJANAH, S', 'P', 'MAGELANG', '0000-00-00', '-', '-', 'TENAGA PERPUSTAKAAN', '03 JANUARI 2021', 'MENDAK SELATAN RT 01 RW 11 DESA BANYUWANGI KECAMATAN BANDONGAN KABUPATEN MAGELANG', '', 'S1/2016'),
(15, 'ALPANDI', 'L', 'MAGELANG', '0000-00-00', '19680907 200901 1 002', 'PENGATUR MUDA TK I-II/B', 'PENJAGA SEKOLAH', '01 AGUSTUS 1998', 'KARANG KIDUL RT 03 RW 05 REJOWINANGUN SELATAN MAGELANG SELATAN KOTA MAGELANG', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `jadwal`
--

CREATE TABLE `jadwal` (
  `id_jadwal` int(11) NOT NULL,
  `hari` varchar(50) DEFAULT NULL,
  `waktu_mulai` time DEFAULT NULL,
  `waktu_selesai` time DEFAULT NULL,
  `id_kelas` int(11) DEFAULT NULL,
  `id_guru` int(11) DEFAULT NULL,
  `id_mapel` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `jadwal`
--

INSERT INTO `jadwal` (`id_jadwal`, `hari`, `waktu_mulai`, `waktu_selesai`, `id_kelas`, `id_guru`, `id_mapel`) VALUES
(1, 'Senin', '07:15:00', '07:50:00', 1, 12, 40);

-- --------------------------------------------------------

--
-- Table structure for table `kelas`
--

CREATE TABLE `kelas` (
  `id_kelas` int(3) NOT NULL,
  `nama_kelas` varchar(25) NOT NULL,
  `tingkat` varchar(2) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `kelas`
--

INSERT INTO `kelas` (`id_kelas`, `nama_kelas`, `tingkat`) VALUES
(1, '1A', '1'),
(2, '2A', '2'),
(3, '3A', '3'),
(4, '4A', '4'),
(5, '5A', '5'),
(6, '6A', '6');

-- --------------------------------------------------------

--
-- Table structure for table `kelas_siswa`
--

CREATE TABLE `kelas_siswa` (
  `id_ks` int(11) NOT NULL,
  `id_siswa` varchar(10) NOT NULL,
  `id_kelas` varchar(5) NOT NULL,
  `id_wali_kelas` varchar(5) NOT NULL,
  `id_ta` varchar(5) NOT NULL,
  `id_next_kelas` varchar(5) NOT NULL,
  `id_ta_next` varchar(5) NOT NULL,
  `status_ks` varchar(15) NOT NULL,
  `catatan_wali_kelas_semester_1` text NOT NULL,
  `catatan_wali_kelas_semester_2` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `kelas_siswa`
--

INSERT INTO `kelas_siswa` (`id_ks`, `id_siswa`, `id_kelas`, `id_wali_kelas`, `id_ta`, `id_next_kelas`, `id_ta_next`, `status_ks`, `catatan_wali_kelas_semester_1`, `catatan_wali_kelas_semester_2`) VALUES
(13, '1', '1', '2', '1', '', '', 'Aktif', '', ''),
(14, '3', '1', '1', '1', '2', '', 'Aktif', '', ''),
(15, '19', '2', '7', '1', '', '', 'Aktif', '', ''),
(16, '4', '1', '1', '1', '', '', 'Aktif', '', ''),
(17, '5', '1', '1', '1', '', '', 'Aktif', '', ''),
(18, '6', '1', '1', '1', '', '', 'Lanjut', '', ''),
(19, '7', '1', '1', '1', '', '', 'Aktif', '', ''),
(20, '8', '1', '1', '1', '', '', 'Aktif', '', ''),
(21, '9', '1', '1', '1', '', '', 'Aktif', '', ''),
(22, '10', '1', '1', '1', '', '', 'Aktif', '', ''),
(23, '11', '1', '1', '1', '', '', 'Aktif', '', ''),
(24, '12', '1', '1', '1', '', '', 'Aktif', '', ''),
(25, '13', '1', '1', '1', '', '', 'Aktif', '', ''),
(26, '14', '1', '1', '1', '', '', 'Aktif', '', ''),
(27, '15', '1', '1', '1', '', '', 'Aktif', '', ''),
(28, '16', '1', '1', '1', '', '', 'Aktif', '', ''),
(29, '17', '1', '1', '1', '', '', 'Aktif', '', ''),
(30, '18', '1', '1', '1', '', '', 'Aktif', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `mapel`
--

CREATE TABLE `mapel` (
  `id_mapel` int(4) NOT NULL,
  `tingkat` varchar(5) NOT NULL,
  `nama_mapel` text NOT NULL,
  `kkm` int(3) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mapel`
--

INSERT INTO `mapel` (`id_mapel`, `tingkat`, `nama_mapel`, `kkm`) VALUES
(1, '1', 'Agama', 50),
(2, '1', 'PPKN', 50),
(3, '1', 'Bahasa Indonesia', 50),
(4, '1', 'Matematika', 50),
(5, '1', 'Seni Budaya', 50),
(6, '1', 'Olahraga', 50),
(7, '1', 'BTA', 50),
(8, '2', 'Agama', 50),
(9, '2', 'PPKN', 50),
(10, '2', 'Bahasa Indonesia', 50),
(11, '2', 'Matematika', 50),
(12, '2', 'Seni Budaya', 50),
(13, '2', 'Olahraga', 50),
(14, '2', 'BTA', 50),
(15, '3', 'Agama', 50),
(16, '3', 'PPKN', 50),
(17, '3', 'Bahasa Indonesia', 50),
(18, '3', 'Matematika', 50),
(19, '3', 'Seni Budaya', 50),
(20, '3', 'Olahraga', 50),
(21, '3', 'BTA', 50),
(22, '4', 'Agama', 50),
(23, '4', 'Bahasa Indonesia', 50),
(24, '4', 'Matematika', 50),
(25, '4', 'Olahraga', 50),
(26, '5', 'Bahasa Indonesia', 50),
(27, '5', 'Agama', 50),
(28, '5', 'Matematika', 50),
(29, '5', 'Olahraga', 50),
(30, '6', 'Matematika', 50),
(31, '6', 'Bahasa Indonesia', 50),
(32, '6', 'Agama', 50),
(33, '6', 'Olahraga', 50),
(34, '7', 'Penjaskes', 0),
(35, '7', 'B.Jawa', 0),
(36, '7', 'Matematika', 0),
(37, '7', 'Agama', 0),
(38, '7', 'IPAS', 0),
(39, '7', 'Bhs.Indo', 0),
(40, '7', 'P.Pancasila', 0),
(41, '7', 'Seni Rupa', 0),
(42, '7', 'Numerasi', 0),
(43, '7', 'Bhs.Inggris', 0),
(44, '7', 'P5', 0),
(45, '7', 'Tematik', 0);

-- --------------------------------------------------------

--
-- Table structure for table `nilai`
--

CREATE TABLE `nilai` (
  `id_nilai` int(11) NOT NULL,
  `id_kelas` varchar(5) NOT NULL,
  `id_ta` varchar(5) NOT NULL,
  `semester` varchar(2) NOT NULL,
  `id_wali_kelas` varchar(5) NOT NULL,
  `id_mapel` varchar(5) NOT NULL,
  `id_siswa` varchar(10) NOT NULL,
  `nilai` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `nilai`
--

INSERT INTO `nilai` (`id_nilai`, `id_kelas`, `id_ta`, `semester`, `id_wali_kelas`, `id_mapel`, `id_siswa`, `nilai`) VALUES
(1, '1', '1', '1', '12', '1', '1', 81),
(2, '1', '1', '1', '12', '2', '1', 82),
(3, '1', '1', '1', '12', '3', '1', 76),
(4, '1', '1', '1', '12', '4', '1', 71),
(5, '1', '1', '1', '12', '5', '1', 73),
(6, '1', '1', '1', '12', '6', '1', 96),
(7, '1', '1', '1', '12', '7', '1', 93),
(8, '1', '1', '1', '12', '1', '2', 91),
(9, '1', '1', '1', '12', '2', '2', 95),
(10, '1', '1', '1', '12', '3', '2', 89),
(11, '1', '1', '1', '12', '4', '2', 79),
(12, '1', '1', '1', '12', '5', '2', 84),
(13, '1', '1', '1', '12', '6', '2', 83),
(14, '1', '1', '1', '12', '7', '2', 94),
(15, '2', '1', '1', '7', '8', '3', 83),
(16, '2', '1', '1', '7', '9', '3', 86),
(17, '2', '1', '1', '7', '10', '3', 81),
(18, '2', '1', '1', '7', '11', '3', 90),
(19, '2', '1', '1', '7', '12', '3', 92),
(20, '2', '1', '1', '7', '13', '3', 76),
(21, '2', '1', '1', '7', '14', '3', 79),
(22, '2', '1', '1', '7', '8', '4', 89),
(23, '2', '1', '1', '7', '9', '4', 90),
(24, '2', '1', '1', '7', '10', '4', 90),
(25, '2', '1', '1', '7', '11', '4', 87),
(26, '2', '1', '1', '7', '12', '4', 77),
(27, '2', '1', '1', '7', '13', '4', 79),
(28, '2', '1', '1', '7', '14', '4', 90),
(29, '3', '1', '1', '6', '15', '5', 89),
(30, '3', '1', '1', '6', '16', '5', 86),
(31, '3', '1', '1', '6', '17', '5', 83),
(32, '3', '1', '1', '6', '18', '5', 78),
(33, '3', '1', '1', '6', '19', '5', 79),
(34, '3', '1', '1', '6', '20', '5', 80),
(35, '3', '1', '1', '6', '21', '5', 90),
(36, '3', '1', '1', '6', '15', '6', 90),
(37, '3', '1', '1', '6', '16', '6', 80),
(38, '3', '1', '1', '6', '17', '6', 81),
(39, '3', '1', '1', '6', '18', '6', 85),
(40, '3', '1', '1', '6', '19', '6', 90),
(41, '3', '1', '1', '6', '20', '6', 94),
(42, '3', '1', '1', '6', '21', '6', 96),
(43, '4', '1', '1', '9', '22', '7', 90),
(44, '4', '1', '1', '9', '23', '7', 87),
(45, '4', '1', '1', '9', '24', '7', 92),
(46, '4', '1', '1', '9', '25', '7', 95),
(47, '4', '1', '1', '9', '22', '8', 80),
(48, '4', '1', '1', '9', '23', '8', 87),
(49, '4', '1', '1', '9', '24', '8', 82),
(50, '4', '1', '1', '9', '25', '8', 92),
(51, '5', '1', '1', '2', '26', '9', 92),
(52, '5', '1', '1', '2', '27', '9', 80),
(53, '5', '1', '1', '2', '28', '9', 76),
(54, '5', '1', '1', '2', '29', '9', 89),
(55, '5', '1', '1', '2', '26', '10', 80),
(56, '5', '1', '1', '2', '27', '10', 82),
(57, '5', '1', '1', '2', '28', '10', 84),
(58, '5', '1', '1', '2', '29', '10', 92),
(59, '6', '1', '1', '5', '30', '11', 80),
(60, '6', '1', '1', '5', '31', '11', 81),
(61, '6', '1', '1', '5', '32', '11', 85),
(62, '6', '1', '1', '5', '33', '11', 91),
(63, '6', '1', '1', '5', '30', '12', 92),
(64, '6', '1', '1', '5', '31', '12', 82),
(65, '6', '1', '1', '5', '32', '12', 87),
(66, '6', '1', '1', '5', '33', '12', 78),
(67, '1', '1', '2', '12', '1', '1', 98),
(68, '1', '1', '2', '12', '2', '1', 91),
(69, '1', '1', '2', '12', '3', '1', 79),
(70, '1', '1', '2', '12', '4', '1', 81),
(71, '1', '1', '2', '12', '5', '1', 86),
(72, '1', '1', '2', '12', '6', '1', 91),
(73, '1', '1', '2', '12', '7', '1', 95),
(74, '1', '1', '2', '12', '1', '2', 79),
(75, '1', '1', '2', '12', '2', '2', 75),
(76, '1', '1', '2', '12', '3', '2', 90),
(77, '1', '1', '2', '12', '4', '2', 76),
(78, '1', '1', '2', '12', '5', '2', 91),
(79, '1', '1', '2', '12', '6', '2', 80),
(80, '1', '1', '2', '12', '7', '2', 84),
(81, '6', '1', '2', '5', '30', '11', 60),
(82, '6', '1', '2', '5', '31', '11', 43),
(83, '6', '1', '2', '5', '32', '11', 34),
(84, '6', '1', '2', '5', '33', '11', 34),
(85, '1', '1', '1', '8', '1', '11', 80),
(86, '1', '1', '1', '8', '2', '11', 75),
(87, '1', '1', '1', '8', '3', '11', 70),
(88, '1', '1', '1', '8', '4', '11', 70),
(89, '1', '1', '1', '8', '5', '11', 70),
(90, '1', '1', '1', '8', '6', '11', 70),
(91, '1', '1', '1', '8', '7', '11', 70);

-- --------------------------------------------------------

--
-- Table structure for table `pengambilan`
--

CREATE TABLE `pengambilan` (
  `id_pengambilan` int(11) NOT NULL,
  `id_siswa` varchar(5) NOT NULL,
  `barang` varchar(25) NOT NULL,
  `biaya` int(16) NOT NULL,
  `waktu_pengambilan` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pengambilan`
--

INSERT INTO `pengambilan` (`id_pengambilan`, `id_siswa`, `barang`, `biaya`, `waktu_pengambilan`) VALUES
(1, '2', 'Baju Olahraga 1 Stel', 150000, '2021-09-06'),
(2, '2', 'Dasi Merah', 70000, '2021-09-06'),
(3, '2', 'Baju Olahraga 1 Stel', 150000, '2021-09-06'),
(4, '2', 'Dasi Merah', 70000, '2021-09-06');

-- --------------------------------------------------------

--
-- Table structure for table `pengumuman`
--

CREATE TABLE `pengumuman` (
  `id_pengumuman` int(11) NOT NULL,
  `nama_pengumuman` varchar(50) NOT NULL,
  `keterangan` text NOT NULL,
  `tgl_input` date NOT NULL,
  `file` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pengumuman`
--

INSERT INTO `pengumuman` (`id_pengumuman`, `nama_pengumuman`, `keterangan`, `tgl_input`, `file`) VALUES
(7, 'Libur Idhul Adha', '<h2 style=\"font-style:italic\">Berhubungan&nbsp; hari esok adalah Tanggal merah maka besog sekolah akan di liburkan</h2>\r\n', '2021-08-16', '063306LIBUR IDUL ADHA.pdf'),
(8, 'Libur Kemerdekaan Indonesia', '<h2 style=\"font-style:italic;\">Berhubungan besog adalah tanggal 17 Agustus yang bertepatan dengan kemerdekaan Republik Indonesia , Maka Sekolah akan diliburkan selama 2 hari.</h2>\r\n', '2021-08-16', '011827Libur Hari Raya Kemerdekaan Indonesia.pdf');

-- --------------------------------------------------------

--
-- Table structure for table `penilaian`
--

CREATE TABLE `penilaian` (
  `id_nilai` int(11) NOT NULL,
  `id_siswa` int(11) DEFAULT NULL,
  `id_kelas` int(11) NOT NULL,
  `id_ta` int(11) NOT NULL,
  `id_mapel` int(11) DEFAULT NULL,
  `ulangan1` int(11) DEFAULT NULL,
  `ulangan2` int(11) DEFAULT NULL,
  `ulangan3` int(11) DEFAULT NULL,
  `ulangan4` int(11) DEFAULT NULL,
  `uts` int(11) DEFAULT NULL,
  `uas` int(11) DEFAULT NULL,
  `rata_rata` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `penilaian`
--

INSERT INTO `penilaian` (`id_nilai`, `id_siswa`, `id_kelas`, `id_ta`, `id_mapel`, `ulangan1`, `ulangan2`, `ulangan3`, `ulangan4`, `uts`, `uas`, `rata_rata`) VALUES
(3, 1, 1, 2, 2, 70, 70, 80, 70, 70, 70, 71.67);

-- --------------------------------------------------------

--
-- Table structure for table `presensi`
--

CREATE TABLE `presensi` (
  `id_presensi` int(11) NOT NULL,
  `id_siswa` int(11) NOT NULL,
  `id_kelas` int(11) NOT NULL,
  `id_ta` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `status_presensi` enum('Hadir','Terlambat','Alfa','Libur','Izin') NOT NULL,
  `surat_izin` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `presensi`
--

INSERT INTO `presensi` (`id_presensi`, `id_siswa`, `id_kelas`, `id_ta`, `tanggal`, `status_presensi`, `surat_izin`, `created_at`) VALUES
(4, 1, 1, 1, '2025-07-07', 'Hadir', '', '2025-07-07 07:11:52'),
(6, 3, 1, 1, '2025-07-07', 'Izin', 'uploads/contoh.pdf', '2025-07-07 07:26:15'),
(8, 19, 2, 1, '2025-07-07', 'Terlambat', '', '2025-07-07 08:28:23'),
(9, 19, 2, 1, '2025-07-07', 'Terlambat', '', '2025-07-07 08:28:37');

-- --------------------------------------------------------

--
-- Table structure for table `siswa`
--

CREATE TABLE `siswa` (
  `id_siswa` int(11) NOT NULL,
  `nama_siswa` varchar(150) NOT NULL,
  `nis` varchar(30) NOT NULL,
  `nisn` varchar(120) NOT NULL,
  `tmpl` varchar(25) NOT NULL,
  `tgll` date NOT NULL,
  `jk` varchar(1) NOT NULL,
  `agama` varchar(15) NOT NULL,
  `pendidikan_sebelumnya` varchar(25) NOT NULL,
  `alamat` varchar(50) NOT NULL,
  `no_telp` varchar(13) NOT NULL,
  `nama_ayah` varchar(25) NOT NULL,
  `nama_ibu` varchar(25) NOT NULL,
  `kerja_ayah` varchar(25) NOT NULL,
  `kerja_ibu` varchar(25) NOT NULL,
  `password` varchar(25) NOT NULL,
  `status_siswa` varchar(25) NOT NULL,
  `daftar_via` varchar(25) NOT NULL,
  `keterangan` text NOT NULL,
  `kelas_awal` varchar(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `siswa`
--

INSERT INTO `siswa` (`id_siswa`, `nama_siswa`, `nis`, `nisn`, `tmpl`, `tgll`, `jk`, `agama`, `pendidikan_sebelumnya`, `alamat`, `no_telp`, `nama_ayah`, `nama_ibu`, `kerja_ayah`, `kerja_ibu`, `password`, `status_siswa`, `daftar_via`, `keterangan`, `kelas_awal`) VALUES
(1, 'Abey Shaka Abinaya', '0001', '3150117266', 'Magelang', '2015-05-31', 'L', 'Islam', 'TK', 'Malangan', '-', 'Nugroho', 'IIN INDRIYANI', 'Karyawan Swasta', 'Ibu Rumah Tangga', '123', 'Aktif', 'Offline', '', '1'),
(2, 'Ahmadin Rasyid Danuri', '0002', '-', 'Magelang', '2015-12-24', 'L', '-', 'TK', '-', '-', '-', '-', '-', '-', '123', 'Aktif', 'offline', '', '1'),
(3, 'Amar Arifin Al Farizi', '0003', '-', 'Magelang', '2015-03-20', 'L', '-', 'TK', '-', '-', '-', '', '-', '-', '123', 'Aktif', 'offline', '-', '1'),
(4, 'Anindita Shaleha', '0004', '-', 'Magelang', '2015-01-21', 'P', '-', 'TK', '-', '-', '-', '-', '-', '-', '123', 'Aktif', 'offline', '', '1'),
(5, 'Azkaraja Putra Hermawan', '0005', '-', 'Magelang', '2022-09-01', 'L', '-', 'TK', '-', '-', '-', '-', '-', '-', '123', 'Aktif', 'offline', '', '1'),
(6, 'Badri Bilal Arif', '0006', '-', 'Magelang', '2015-09-02', 'L', '-', 'TK', '-', '-', '-', '-', '-', '-', '123', 'Aktif', 'offline', '', '1'),
(7, 'Brilliant Badila Fariz Nurmansyah', '0007', '-', 'Magelang', '2015-03-26', 'L', '-', 'TK', '-', '-', '-', '-', '-', '-', '123', 'Aktif', 'offline', '', '1'),
(8, 'Dama Nur Azahra', '0008', '-', 'Magelang', '2015-07-14', 'P', '-', 'TK', '-', '-', '-', '-', '-', '-', '123', 'Aktif', 'offline', '-', '1'),
(9, 'Dirashofia Naura Rahmawati', '0009', '-', 'Magelang', '2015-10-08', 'P', '-', 'TK', '-', '-', '-', '-', '-', '-', '123', 'Aktif', 'offline', '-', '1'),
(10, 'Eno Praditya Efendi', '0010', '-', 'Magelang', '2015-12-21', 'L', '-', 'TK', '-', '-', '-', '-', '-', '-', '123', 'Aktif', 'offline', '', '1'),
(11, 'Evan Zhafran Aveiro', '0011', '-', 'Magelang', '0000-00-00', 'L', '-', 'TK', '-', '-', '-', '-', '-', '-', '', 'Aktif', 'offline', '', '1'),
(12, 'Gerrard Medy Aljabbar', '0012', '-', 'Magelang', '0000-00-00', 'L', '-', 'TK', '-', '-', '-', '-', '', '-', '123', 'Aktif', 'offline', '-', '1'),
(13, 'Inka Jihan Aulia Putri', '0013', '-', 'Magelang', '2015-06-12', 'P', '-', 'TK', '-', '-', '-', '-', '-', '-', '123', 'Aktif', 'offline', '-', '1'),
(14, 'Joddan Dzuhur Ankarosan', '0014', '-', 'Magelang', '2015-04-14', 'L', '-', 'TK', '-', '-', '-', '-', '-', '-', '123', 'Aktif', 'offline', '-', '1'),
(15, 'Nayla Izza El Karimah', '0015', '-', 'Magelang', '2015-11-19', 'P', '-', 'TK', '-', '-', '-', '-', '-', '-', '123', 'Aktif', 'offline', '-', '1'),
(16, 'Reyfan Aditya Pamungkas', '0016', '-', 'Magelang', '2015-03-31', 'P', '-', 'TK', '-', '-', '-', '-', '-', '-', '123', 'Aktif', 'offline', '-', '1'),
(17, 'Shaina Mezza Risqueena', '0017', '-', 'Magelang', '2015-04-23', 'P', '-', 'TK', '-', '-', '-', '-', '-', '-', '123', 'Aktif', 'offline', '-', '1'),
(18, 'Varisca Adara', '0018', '-', 'Magelang', '2015-10-08', 'P', '-', 'TK', '-', '-', '-', '-', '-', '-', '123', 'Aktif', 'offline', '-', '1'),
(21, 'ALFANDO PUTRA RAMADHANIS NUGROHO', '202', '3147453381', 'Magelang', '2014-08-15', 'L', 'Islam', 'TK', 'KARANG GADING  RT 005 RW 004', '-', 'Rudi', 'ANITA VIDA PUTRANTRI RIA', 'Karyawan Swasta', 'Ibu Rumah Tangga', '123', 'Aktif', 'offline', '', '2'),
(19, 'Abrisam Akma Haidar', '200', '0135586996', 'Magelang', '2014-08-14', 'L', 'Islam', 'TK', 'Jalan Dewi Ratih RT 2 RW 3, Karang Gading', '-', 'Anggi', 'Maya Ferniawati', 'Buruh', 'Karyawan Swasta', '123', 'Aktif', 'offline', '', '1'),
(20, 'Ahmad Zulfa Yulianto', '201', '0133790052', 'Magelang', '2015-02-13', 'L', 'Islam', 'TK', 'Jalan Dewi Ratih RT 2 RW 4 Karang Gading', '-', 'Nur Sulistiyanto', 'Yuli Purwanti', 'Karyawan Swasta', 'Karyawan Swasta', '123', 'Aktif', 'offline', '', '2'),
(22, 'ALIFFANIA MEYLA SYAFITRI', '203', '0148283995', 'Magelang', '2014-05-09', 'P', 'Islam', 'TK', 'KARANG KIDUL RT 4 RW 3', '-', 'ROJI EKO PRASETIYO', 'PURWATI', 'Karyawan Swasta', 'Ibu Rumah Tangga', '123', 'Calon Siswa', 'offline', '', '2');

-- --------------------------------------------------------

--
-- Table structure for table `spp`
--

CREATE TABLE `spp` (
  `id_spp` int(11) NOT NULL,
  `id_siswa` int(11) NOT NULL,
  `id_kelas` int(11) NOT NULL,
  `bulan` enum('Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember') NOT NULL,
  `tahun` year(4) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `status_pembayaran` enum('Belum Lunas','Lunas') DEFAULT 'Belum Lunas',
  `tanggal_pembayaran` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `spp`
--

INSERT INTO `spp` (`id_spp`, `id_siswa`, `id_kelas`, `bulan`, `tahun`, `jumlah`, `status_pembayaran`, `tanggal_pembayaran`) VALUES
(1, 1, 1, 'Januari', 2025, 100000, 'Lunas', '2025-07-08'),
(2, 1, 1, 'Februari', 2025, 100000, 'Belum Lunas', NULL),
(3, 1, 1, 'Maret', 2025, 100000, 'Belum Lunas', NULL),
(4, 1, 1, 'April', 2025, 100000, 'Belum Lunas', NULL),
(5, 1, 1, 'Mei', 2025, 100000, 'Belum Lunas', NULL),
(6, 1, 1, 'Juni', 2025, 100000, 'Belum Lunas', NULL),
(7, 1, 1, 'Juli', 2025, 100000, 'Belum Lunas', NULL),
(8, 1, 1, 'Agustus', 2025, 100000, 'Belum Lunas', NULL),
(9, 1, 1, 'September', 2025, 100000, 'Belum Lunas', NULL),
(10, 1, 1, 'Oktober', 2025, 100000, 'Belum Lunas', NULL),
(11, 1, 1, 'November', 2025, 100000, 'Belum Lunas', NULL),
(12, 1, 1, 'Desember', 2025, 100000, 'Belum Lunas', NULL),
(13, 3, 1, 'Januari', 2025, 100000, 'Belum Lunas', NULL),
(14, 3, 1, 'Februari', 2025, 100000, 'Belum Lunas', NULL),
(15, 3, 1, 'Maret', 2025, 100000, 'Belum Lunas', NULL),
(16, 3, 1, 'April', 2025, 100000, 'Belum Lunas', NULL),
(17, 3, 1, 'Mei', 2025, 100000, 'Belum Lunas', NULL),
(18, 3, 1, 'Juni', 2025, 100000, 'Belum Lunas', NULL),
(19, 3, 1, 'Juli', 2025, 100000, 'Belum Lunas', NULL),
(20, 3, 1, 'Agustus', 2025, 100000, 'Belum Lunas', NULL),
(21, 3, 1, 'September', 2025, 100000, 'Belum Lunas', NULL),
(22, 3, 1, 'Oktober', 2025, 100000, 'Belum Lunas', NULL),
(23, 3, 1, 'November', 2025, 100000, 'Belum Lunas', NULL),
(24, 3, 1, 'Desember', 2025, 100000, 'Belum Lunas', NULL),
(25, 4, 1, 'Januari', 2025, 100000, 'Belum Lunas', NULL),
(26, 4, 1, 'Februari', 2025, 100000, 'Belum Lunas', NULL),
(27, 4, 1, 'Maret', 2025, 100000, 'Belum Lunas', NULL),
(28, 4, 1, 'April', 2025, 100000, 'Belum Lunas', NULL),
(29, 4, 1, 'Mei', 2025, 100000, 'Belum Lunas', NULL),
(30, 4, 1, 'Juni', 2025, 100000, 'Belum Lunas', NULL),
(31, 4, 1, 'Juli', 2025, 100000, 'Belum Lunas', NULL),
(32, 4, 1, 'Agustus', 2025, 100000, 'Belum Lunas', NULL),
(33, 4, 1, 'September', 2025, 100000, 'Belum Lunas', NULL),
(34, 4, 1, 'Oktober', 2025, 100000, 'Belum Lunas', NULL),
(35, 4, 1, 'November', 2025, 100000, 'Belum Lunas', NULL),
(36, 4, 1, 'Desember', 2025, 100000, 'Belum Lunas', NULL),
(37, 5, 1, 'Januari', 2025, 100000, 'Belum Lunas', NULL),
(38, 5, 1, 'Februari', 2025, 100000, 'Belum Lunas', NULL),
(39, 5, 1, 'Maret', 2025, 100000, 'Belum Lunas', NULL),
(40, 5, 1, 'April', 2025, 100000, 'Belum Lunas', NULL),
(41, 5, 1, 'Mei', 2025, 100000, 'Belum Lunas', NULL),
(42, 5, 1, 'Juni', 2025, 100000, 'Belum Lunas', NULL),
(43, 5, 1, 'Juli', 2025, 100000, 'Belum Lunas', NULL),
(44, 5, 1, 'Agustus', 2025, 100000, 'Belum Lunas', NULL),
(45, 5, 1, 'September', 2025, 100000, 'Belum Lunas', NULL),
(46, 5, 1, 'Oktober', 2025, 100000, 'Belum Lunas', NULL),
(47, 5, 1, 'November', 2025, 100000, 'Belum Lunas', NULL),
(48, 5, 1, 'Desember', 2025, 100000, 'Belum Lunas', NULL),
(49, 6, 1, 'Januari', 2025, 100000, 'Belum Lunas', NULL),
(50, 6, 1, 'Februari', 2025, 100000, 'Belum Lunas', NULL),
(51, 6, 1, 'Maret', 2025, 100000, 'Belum Lunas', NULL),
(52, 6, 1, 'April', 2025, 100000, 'Belum Lunas', NULL),
(53, 6, 1, 'Mei', 2025, 100000, 'Belum Lunas', NULL),
(54, 6, 1, 'Juni', 2025, 100000, 'Belum Lunas', NULL),
(55, 6, 1, 'Juli', 2025, 100000, 'Belum Lunas', NULL),
(56, 6, 1, 'Agustus', 2025, 100000, 'Belum Lunas', NULL),
(57, 6, 1, 'September', 2025, 100000, 'Belum Lunas', NULL),
(58, 6, 1, 'Oktober', 2025, 100000, 'Belum Lunas', NULL),
(59, 6, 1, 'November', 2025, 100000, 'Belum Lunas', NULL),
(60, 6, 1, 'Desember', 2025, 100000, 'Belum Lunas', NULL),
(61, 7, 1, 'Januari', 2025, 100000, 'Belum Lunas', NULL),
(62, 7, 1, 'Februari', 2025, 100000, 'Belum Lunas', NULL),
(63, 7, 1, 'Maret', 2025, 100000, 'Belum Lunas', NULL),
(64, 7, 1, 'April', 2025, 100000, 'Belum Lunas', NULL),
(65, 7, 1, 'Mei', 2025, 100000, 'Belum Lunas', NULL),
(66, 7, 1, 'Juni', 2025, 100000, 'Belum Lunas', NULL),
(67, 7, 1, 'Juli', 2025, 100000, 'Belum Lunas', NULL),
(68, 7, 1, 'Agustus', 2025, 100000, 'Belum Lunas', NULL),
(69, 7, 1, 'September', 2025, 100000, 'Belum Lunas', NULL),
(70, 7, 1, 'Oktober', 2025, 100000, 'Belum Lunas', NULL),
(71, 7, 1, 'November', 2025, 100000, 'Belum Lunas', NULL),
(72, 7, 1, 'Desember', 2025, 100000, 'Belum Lunas', NULL),
(73, 8, 1, 'Januari', 2025, 100000, 'Belum Lunas', NULL),
(74, 8, 1, 'Februari', 2025, 100000, 'Belum Lunas', NULL),
(75, 8, 1, 'Maret', 2025, 100000, 'Belum Lunas', NULL),
(76, 8, 1, 'April', 2025, 100000, 'Belum Lunas', NULL),
(77, 8, 1, 'Mei', 2025, 100000, 'Belum Lunas', NULL),
(78, 8, 1, 'Juni', 2025, 100000, 'Belum Lunas', NULL),
(79, 8, 1, 'Juli', 2025, 100000, 'Belum Lunas', NULL),
(80, 8, 1, 'Agustus', 2025, 100000, 'Belum Lunas', NULL),
(81, 8, 1, 'September', 2025, 100000, 'Belum Lunas', NULL),
(82, 8, 1, 'Oktober', 2025, 100000, 'Belum Lunas', NULL),
(83, 8, 1, 'November', 2025, 100000, 'Belum Lunas', NULL),
(84, 8, 1, 'Desember', 2025, 100000, 'Belum Lunas', NULL),
(85, 9, 1, 'Januari', 2025, 100000, 'Belum Lunas', NULL),
(86, 9, 1, 'Februari', 2025, 100000, 'Belum Lunas', NULL),
(87, 9, 1, 'Maret', 2025, 100000, 'Belum Lunas', NULL),
(88, 9, 1, 'April', 2025, 100000, 'Belum Lunas', NULL),
(89, 9, 1, 'Mei', 2025, 100000, 'Belum Lunas', NULL),
(90, 9, 1, 'Juni', 2025, 100000, 'Belum Lunas', NULL),
(91, 9, 1, 'Juli', 2025, 100000, 'Belum Lunas', NULL),
(92, 9, 1, 'Agustus', 2025, 100000, 'Belum Lunas', NULL),
(93, 9, 1, 'September', 2025, 100000, 'Belum Lunas', NULL),
(94, 9, 1, 'Oktober', 2025, 100000, 'Belum Lunas', NULL),
(95, 9, 1, 'November', 2025, 100000, 'Belum Lunas', NULL),
(96, 9, 1, 'Desember', 2025, 100000, 'Belum Lunas', NULL),
(97, 10, 1, 'Januari', 2025, 100000, 'Belum Lunas', NULL),
(98, 10, 1, 'Februari', 2025, 100000, 'Belum Lunas', NULL),
(99, 10, 1, 'Maret', 2025, 100000, 'Belum Lunas', NULL),
(100, 10, 1, 'April', 2025, 100000, 'Belum Lunas', NULL),
(101, 10, 1, 'Mei', 2025, 100000, 'Belum Lunas', NULL),
(102, 10, 1, 'Juni', 2025, 100000, 'Belum Lunas', NULL),
(103, 10, 1, 'Juli', 2025, 100000, 'Belum Lunas', NULL),
(104, 10, 1, 'Agustus', 2025, 100000, 'Belum Lunas', NULL),
(105, 10, 1, 'September', 2025, 100000, 'Belum Lunas', NULL),
(106, 10, 1, 'Oktober', 2025, 100000, 'Belum Lunas', NULL),
(107, 10, 1, 'November', 2025, 100000, 'Belum Lunas', NULL),
(108, 10, 1, 'Desember', 2025, 100000, 'Belum Lunas', NULL),
(109, 11, 1, 'Januari', 2025, 100000, 'Belum Lunas', NULL),
(110, 11, 1, 'Februari', 2025, 100000, 'Belum Lunas', NULL),
(111, 11, 1, 'Maret', 2025, 100000, 'Belum Lunas', NULL),
(112, 11, 1, 'April', 2025, 100000, 'Belum Lunas', NULL),
(113, 11, 1, 'Mei', 2025, 100000, 'Belum Lunas', NULL),
(114, 11, 1, 'Juni', 2025, 100000, 'Belum Lunas', NULL),
(115, 11, 1, 'Juli', 2025, 100000, 'Belum Lunas', NULL),
(116, 11, 1, 'Agustus', 2025, 100000, 'Belum Lunas', NULL),
(117, 11, 1, 'September', 2025, 100000, 'Belum Lunas', NULL),
(118, 11, 1, 'Oktober', 2025, 100000, 'Belum Lunas', NULL),
(119, 11, 1, 'November', 2025, 100000, 'Belum Lunas', NULL),
(120, 11, 1, 'Desember', 2025, 100000, 'Belum Lunas', NULL),
(121, 12, 1, 'Januari', 2025, 100000, 'Belum Lunas', NULL),
(122, 12, 1, 'Februari', 2025, 100000, 'Belum Lunas', NULL),
(123, 12, 1, 'Maret', 2025, 100000, 'Belum Lunas', NULL),
(124, 12, 1, 'April', 2025, 100000, 'Belum Lunas', NULL),
(125, 12, 1, 'Mei', 2025, 100000, 'Belum Lunas', NULL),
(126, 12, 1, 'Juni', 2025, 100000, 'Belum Lunas', NULL),
(127, 12, 1, 'Juli', 2025, 100000, 'Belum Lunas', NULL),
(128, 12, 1, 'Agustus', 2025, 100000, 'Belum Lunas', NULL),
(129, 12, 1, 'September', 2025, 100000, 'Belum Lunas', NULL),
(130, 12, 1, 'Oktober', 2025, 100000, 'Belum Lunas', NULL),
(131, 12, 1, 'November', 2025, 100000, 'Belum Lunas', NULL),
(132, 12, 1, 'Desember', 2025, 100000, 'Belum Lunas', NULL),
(133, 13, 1, 'Januari', 2025, 100000, 'Belum Lunas', NULL),
(134, 13, 1, 'Februari', 2025, 100000, 'Belum Lunas', NULL),
(135, 13, 1, 'Maret', 2025, 100000, 'Belum Lunas', NULL),
(136, 13, 1, 'April', 2025, 100000, 'Belum Lunas', NULL),
(137, 13, 1, 'Mei', 2025, 100000, 'Belum Lunas', NULL),
(138, 13, 1, 'Juni', 2025, 100000, 'Belum Lunas', NULL),
(139, 13, 1, 'Juli', 2025, 100000, 'Belum Lunas', NULL),
(140, 13, 1, 'Agustus', 2025, 100000, 'Belum Lunas', NULL),
(141, 13, 1, 'September', 2025, 100000, 'Belum Lunas', NULL),
(142, 13, 1, 'Oktober', 2025, 100000, 'Belum Lunas', NULL),
(143, 13, 1, 'November', 2025, 100000, 'Belum Lunas', NULL),
(144, 13, 1, 'Desember', 2025, 100000, 'Belum Lunas', NULL),
(145, 14, 1, 'Januari', 2025, 100000, 'Belum Lunas', NULL),
(146, 14, 1, 'Februari', 2025, 100000, 'Belum Lunas', NULL),
(147, 14, 1, 'Maret', 2025, 100000, 'Belum Lunas', NULL),
(148, 14, 1, 'April', 2025, 100000, 'Belum Lunas', NULL),
(149, 14, 1, 'Mei', 2025, 100000, 'Belum Lunas', NULL),
(150, 14, 1, 'Juni', 2025, 100000, 'Belum Lunas', NULL),
(151, 14, 1, 'Juli', 2025, 100000, 'Belum Lunas', NULL),
(152, 14, 1, 'Agustus', 2025, 100000, 'Belum Lunas', NULL),
(153, 14, 1, 'September', 2025, 100000, 'Belum Lunas', NULL),
(154, 14, 1, 'Oktober', 2025, 100000, 'Belum Lunas', NULL),
(155, 14, 1, 'November', 2025, 100000, 'Belum Lunas', NULL),
(156, 14, 1, 'Desember', 2025, 100000, 'Belum Lunas', NULL),
(157, 15, 1, 'Januari', 2025, 100000, 'Belum Lunas', NULL),
(158, 15, 1, 'Februari', 2025, 100000, 'Belum Lunas', NULL),
(159, 15, 1, 'Maret', 2025, 100000, 'Belum Lunas', NULL),
(160, 15, 1, 'April', 2025, 100000, 'Belum Lunas', NULL),
(161, 15, 1, 'Mei', 2025, 100000, 'Belum Lunas', NULL),
(162, 15, 1, 'Juni', 2025, 100000, 'Belum Lunas', NULL),
(163, 15, 1, 'Juli', 2025, 100000, 'Belum Lunas', NULL),
(164, 15, 1, 'Agustus', 2025, 100000, 'Belum Lunas', NULL),
(165, 15, 1, 'September', 2025, 100000, 'Belum Lunas', NULL),
(166, 15, 1, 'Oktober', 2025, 100000, 'Belum Lunas', NULL),
(167, 15, 1, 'November', 2025, 100000, 'Belum Lunas', NULL),
(168, 15, 1, 'Desember', 2025, 100000, 'Belum Lunas', NULL),
(169, 16, 1, 'Januari', 2025, 100000, 'Belum Lunas', NULL),
(170, 16, 1, 'Februari', 2025, 100000, 'Belum Lunas', NULL),
(171, 16, 1, 'Maret', 2025, 100000, 'Belum Lunas', NULL),
(172, 16, 1, 'April', 2025, 100000, 'Belum Lunas', NULL),
(173, 16, 1, 'Mei', 2025, 100000, 'Belum Lunas', NULL),
(174, 16, 1, 'Juni', 2025, 100000, 'Belum Lunas', NULL),
(175, 16, 1, 'Juli', 2025, 100000, 'Belum Lunas', NULL),
(176, 16, 1, 'Agustus', 2025, 100000, 'Belum Lunas', NULL),
(177, 16, 1, 'September', 2025, 100000, 'Belum Lunas', NULL),
(178, 16, 1, 'Oktober', 2025, 100000, 'Belum Lunas', NULL),
(179, 16, 1, 'November', 2025, 100000, 'Belum Lunas', NULL),
(180, 16, 1, 'Desember', 2025, 100000, 'Belum Lunas', NULL),
(181, 17, 1, 'Januari', 2025, 100000, 'Belum Lunas', NULL),
(182, 17, 1, 'Februari', 2025, 100000, 'Belum Lunas', NULL),
(183, 17, 1, 'Maret', 2025, 100000, 'Belum Lunas', NULL),
(184, 17, 1, 'April', 2025, 100000, 'Belum Lunas', NULL),
(185, 17, 1, 'Mei', 2025, 100000, 'Belum Lunas', NULL),
(186, 17, 1, 'Juni', 2025, 100000, 'Belum Lunas', NULL),
(187, 17, 1, 'Juli', 2025, 100000, 'Belum Lunas', NULL),
(188, 17, 1, 'Agustus', 2025, 100000, 'Belum Lunas', NULL),
(189, 17, 1, 'September', 2025, 100000, 'Belum Lunas', NULL),
(190, 17, 1, 'Oktober', 2025, 100000, 'Belum Lunas', NULL),
(191, 17, 1, 'November', 2025, 100000, 'Belum Lunas', NULL),
(192, 17, 1, 'Desember', 2025, 100000, 'Belum Lunas', NULL),
(193, 18, 1, 'Januari', 2025, 100000, 'Belum Lunas', NULL),
(194, 18, 1, 'Februari', 2025, 100000, 'Belum Lunas', NULL),
(195, 18, 1, 'Maret', 2025, 100000, 'Belum Lunas', NULL),
(196, 18, 1, 'April', 2025, 100000, 'Belum Lunas', NULL),
(197, 18, 1, 'Mei', 2025, 100000, 'Belum Lunas', NULL),
(198, 18, 1, 'Juni', 2025, 100000, 'Belum Lunas', NULL),
(199, 18, 1, 'Juli', 2025, 100000, 'Belum Lunas', NULL),
(200, 18, 1, 'Agustus', 2025, 100000, 'Belum Lunas', NULL),
(201, 18, 1, 'September', 2025, 100000, 'Belum Lunas', NULL),
(202, 18, 1, 'Oktober', 2025, 100000, 'Belum Lunas', NULL),
(203, 18, 1, 'November', 2025, 100000, 'Belum Lunas', NULL),
(204, 18, 1, 'Desember', 2025, 100000, 'Belum Lunas', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tabungan`
--

CREATE TABLE `tabungan` (
  `id_tabungan` int(11) NOT NULL,
  `id_siswa` varchar(50) NOT NULL,
  `id_kelas` int(11) NOT NULL,
  `jenis_transaksi` enum('Setoran','Penarikan') NOT NULL,
  `jumlah` int(15) NOT NULL,
  `tanggal_transaksi` date NOT NULL,
  `saldo` int(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tabungan`
--

INSERT INTO `tabungan` (`id_tabungan`, `id_siswa`, `id_kelas`, `jenis_transaksi`, `jumlah`, `tanggal_transaksi`, `saldo`) VALUES
(1, '1', 1, 'Setoran', 30000, '2025-07-08', 30000),
(3, '1', 1, 'Penarikan', 10000, '2025-07-08', 20000),
(4, '1', 1, 'Setoran', 15000, '2025-07-09', 35000);

-- --------------------------------------------------------

--
-- Table structure for table `tahun_ajaran`
--

CREATE TABLE `tahun_ajaran` (
  `no` int(11) NOT NULL,
  `id_ta` varchar(5) NOT NULL,
  `nama_ta` varchar(25) NOT NULL,
  `semester` int(2) NOT NULL,
  `status_ta` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tahun_ajaran`
--

INSERT INTO `tahun_ajaran` (`no`, `id_ta`, `nama_ta`, `semester`, `status_ta`) VALUES
(1, '1', '2022', 1, 'Aktif'),
(2, '2', '2023', 2, 'Aktif');

-- --------------------------------------------------------

--
-- Table structure for table `wali_kelas`
--

CREATE TABLE `wali_kelas` (
  `id_walikelas` int(11) NOT NULL,
  `id_guru` varchar(5) NOT NULL,
  `id_kelas` varchar(5) NOT NULL,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `status_wali_kelas` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wali_kelas`
--

INSERT INTO `wali_kelas` (`id_walikelas`, `id_guru`, `id_kelas`, `username`, `password`, `status_wali_kelas`) VALUES
(1, '8', '1', 'Silverster', '123', '1'),
(2, '7', '2', 'Susetno', '123', '1'),
(3, '6', '3', '14', '123', '1'),
(4, '9', '4', '15', '123', '1'),
(5, '2', '5', '16', '123', '1'),
(6, '5', '6', '17', '123', '1'),
(9, '10', '7', '010', '123', '1'),
(10, '11', '7', '20', '123', '1'),
(11, '10', '5', '002', '123', '1');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `absensi`
--
ALTER TABLE `absensi`
  ADD PRIMARY KEY (`id_absensi`);

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `guru`
--
ALTER TABLE `guru`
  ADD PRIMARY KEY (`id_guru`);

--
-- Indexes for table `jadwal`
--
ALTER TABLE `jadwal`
  ADD PRIMARY KEY (`id_jadwal`);

--
-- Indexes for table `kelas`
--
ALTER TABLE `kelas`
  ADD PRIMARY KEY (`id_kelas`);

--
-- Indexes for table `kelas_siswa`
--
ALTER TABLE `kelas_siswa`
  ADD PRIMARY KEY (`id_ks`);

--
-- Indexes for table `mapel`
--
ALTER TABLE `mapel`
  ADD PRIMARY KEY (`id_mapel`);

--
-- Indexes for table `nilai`
--
ALTER TABLE `nilai`
  ADD PRIMARY KEY (`id_nilai`);

--
-- Indexes for table `pengambilan`
--
ALTER TABLE `pengambilan`
  ADD PRIMARY KEY (`id_pengambilan`);

--
-- Indexes for table `pengumuman`
--
ALTER TABLE `pengumuman`
  ADD PRIMARY KEY (`id_pengumuman`);

--
-- Indexes for table `penilaian`
--
ALTER TABLE `penilaian`
  ADD PRIMARY KEY (`id_nilai`);

--
-- Indexes for table `presensi`
--
ALTER TABLE `presensi`
  ADD PRIMARY KEY (`id_presensi`);

--
-- Indexes for table `siswa`
--
ALTER TABLE `siswa`
  ADD PRIMARY KEY (`id_siswa`);

--
-- Indexes for table `spp`
--
ALTER TABLE `spp`
  ADD PRIMARY KEY (`id_spp`);

--
-- Indexes for table `tabungan`
--
ALTER TABLE `tabungan`
  ADD PRIMARY KEY (`id_tabungan`);

--
-- Indexes for table `tahun_ajaran`
--
ALTER TABLE `tahun_ajaran`
  ADD PRIMARY KEY (`no`);

--
-- Indexes for table `wali_kelas`
--
ALTER TABLE `wali_kelas`
  ADD PRIMARY KEY (`id_walikelas`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `absensi`
--
ALTER TABLE `absensi`
  MODIFY `id_absensi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `guru`
--
ALTER TABLE `guru`
  MODIFY `id_guru` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `jadwal`
--
ALTER TABLE `jadwal`
  MODIFY `id_jadwal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `kelas`
--
ALTER TABLE `kelas`
  MODIFY `id_kelas` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `kelas_siswa`
--
ALTER TABLE `kelas_siswa`
  MODIFY `id_ks` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `mapel`
--
ALTER TABLE `mapel`
  MODIFY `id_mapel` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `nilai`
--
ALTER TABLE `nilai`
  MODIFY `id_nilai` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;

--
-- AUTO_INCREMENT for table `pengambilan`
--
ALTER TABLE `pengambilan`
  MODIFY `id_pengambilan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `pengumuman`
--
ALTER TABLE `pengumuman`
  MODIFY `id_pengumuman` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `penilaian`
--
ALTER TABLE `penilaian`
  MODIFY `id_nilai` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `presensi`
--
ALTER TABLE `presensi`
  MODIFY `id_presensi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `siswa`
--
ALTER TABLE `siswa`
  MODIFY `id_siswa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=215;

--
-- AUTO_INCREMENT for table `spp`
--
ALTER TABLE `spp`
  MODIFY `id_spp` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=205;

--
-- AUTO_INCREMENT for table `tabungan`
--
ALTER TABLE `tabungan`
  MODIFY `id_tabungan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tahun_ajaran`
--
ALTER TABLE `tahun_ajaran`
  MODIFY `no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `wali_kelas`
--
ALTER TABLE `wali_kelas`
  MODIFY `id_walikelas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
