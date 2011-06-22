-- phpMyAdmin SQL Dump
-- version 2.11.6
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 11, 2011 at 07:54 PM
-- Server version: 5.0.51
-- PHP Version: 5.2.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `elitezdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id_admin` int(11) NOT NULL auto_increment,
  `nama_admin` varchar(50) NOT NULL,
  `alamat_admin` text NOT NULL,
  `email_admin` varchar(50) NOT NULL,
  `telp_admin` varchar(20) NOT NULL,
  `password_admin` varchar(32) NOT NULL,
  `verification_admin` varchar(32) NOT NULL,
  `status_login` enum('0','1') NOT NULL,
  `waktu_login` int(11) NOT NULL,
  PRIMARY KEY  (`id_admin`),
  UNIQUE KEY `email_admin` (`email_admin`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='Tabel Administrator' AUTO_INCREMENT=7 ;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id_admin`, `nama_admin`, `alamat_admin`, `email_admin`, `telp_admin`, `password_admin`, `verification_admin`, `status_login`, `waktu_login`) VALUES
(1, 'elitez admin', 'suka tangguh', 'elitez.cloth@gmail.com', '1234567890', 'e807f1fcf82d132f9bb018ca6738a19f', 'c44a471bd78cc6c2fea32b9fe028d30a', '1', 1307793157);

-- --------------------------------------------------------

--
-- Table structure for table `detailproduk`
--

CREATE TABLE `detailproduk` (
  `id_detailproduk` int(11) NOT NULL auto_increment,
  `id_produk` int(4) unsigned zerofill NOT NULL,
  `id_warna` int(11) default NULL,
  `id_ukuran` int(11) NOT NULL,
  `tanggal_detailproduk` datetime NOT NULL,
  `stok_detailproduk` int(11) NOT NULL,
  `berat_detailproduk` float NOT NULL,
  PRIMARY KEY  (`id_detailproduk`),
  KEY `id_warna` (`id_warna`),
  KEY `id_ukuran` (`id_ukuran`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Tabel Detail Produk' AUTO_INCREMENT=5 ;

--
-- Dumping data for table `detailproduk`
--

INSERT INTO `detailproduk` (`id_detailproduk`, `id_produk`, `id_warna`, `id_ukuran`, `tanggal_detailproduk`, `stok_detailproduk`, `berat_detailproduk`) VALUES
(1, 0001, 1, 1, '2011-05-25 18:48:12', 6, 0.4),
(2, 0002, 1, 1, '2011-05-25 18:51:05', 8, 0.6),
(3, 0003, 1, 2, '2011-05-25 18:52:25', 10, 0.6),
(4, 0004, 2, 5, '2011-06-01 22:49:15', 11, 0.7);

-- --------------------------------------------------------

--
-- Table structure for table `detail_pembelian`
--

CREATE TABLE `detail_pembelian` (
  `idpembelian` int(11) NOT NULL,
  `hargabeli` int(20) NOT NULL,
  `id_detailproduk` int(8) NOT NULL,
  `qty` int(11) NOT NULL,
  `berat` float NOT NULL,
  `retur_qty` int(11) NOT NULL,
  KEY `pembelian_FK` (`idpembelian`),
  KEY `produk_id_FK` (`id_detailproduk`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `detail_pembelian`
--

INSERT INTO `detail_pembelian` (`idpembelian`, `hargabeli`, `id_detailproduk`, `qty`, `berat`, `retur_qty`) VALUES
(1, 70000, 3, 1, 0.6, 0),
(2, 63000, 1, 1, 0.4, 0),
(3, 85000, 2, 1, 0.6, 0),
(4, 85000, 2, 1, 0.6, 0),
(5, 63000, 1, 1, 0.4, 0),
(6, 85000, 2, 1, 0.6, 0),
(6, 70000, 3, 1, 0.6, 0),
(7, 85000, 2, 1, 0.6, 0),
(8, 63000, 1, 2, 0.4, 0),
(8, 108000, 4, 2, 0.7, 0),
(9, 63000, 1, 1, 0.4, 0);

-- --------------------------------------------------------

--
-- Table structure for table `detail_retur`
--

CREATE TABLE `detail_retur` (
  `id_retur` int(11) NOT NULL,
  `idpembelian` int(11) NOT NULL,
  `id_detailproduk` int(11) NOT NULL,
  `qty_retur` int(11) NOT NULL,
  `komplain` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `detail_retur`
--


-- --------------------------------------------------------

--
-- Table structure for table `gambar`
--

CREATE TABLE `gambar` (
  `id_gambar` int(11) NOT NULL auto_increment,
  `id_produk` int(11) NOT NULL,
  `nama_gambar` varchar(200) NOT NULL,
  `profile_gambar` enum('0','1') NOT NULL,
  PRIMARY KEY  (`id_gambar`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='Tabel Gambar' AUTO_INCREMENT=6 ;

--
-- Dumping data for table `gambar`
--

INSERT INTO `gambar` (`id_gambar`, `id_produk`, `nama_gambar`, `profile_gambar`) VALUES
(1, 1, 'under-construction-kaos.jpg', '1'),
(2, 2, 'ngecat-langit-kaosnya.jpg', '1'),
(3, 3, 'kaos acta.jpg', '1'),
(4, 4, 'celana1.jpg', '1'),
(5, 4, 'celana.jpg', '0');

-- --------------------------------------------------------

--
-- Table structure for table `hubungi`
--

CREATE TABLE `hubungi` (
  `id_hubungi` int(11) NOT NULL auto_increment,
  `id_member` int(11) default NULL,
  `nama_hubungi` varchar(100) NOT NULL,
  `email_hubungi` varchar(50) NOT NULL,
  `isi_hubungi` text NOT NULL,
  `status_hubungi` enum('1','0') NOT NULL,
  `tanggal_hubungi` datetime NOT NULL,
  PRIMARY KEY  (`id_hubungi`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='Tabel Hubungi Kami' AUTO_INCREMENT=11 ;

--
-- Dumping data for table `hubungi`
--

INSERT INTO `hubungi` (`id_hubungi`, `id_member`, `nama_hubungi`, `email_hubungi`, `isi_hubungi`, `status_hubungi`, `tanggal_hubungi`) VALUES
(10, 1, 'jpoasj', 'budi@localhost', 'abfcajkcbjac', '1', '2011-05-06 14:28:24'),
(9, 0, 'jajao', 'jaja@localhost', 'sfgjhfhjsax ascjc  kascbkuc qwcbuc khcaswhc jcodlsc lknoich osidvh  lhcdoisc hcowc cbqwoc coicj nxloajx bc iox xlsahxclox xn;lxasblc ', '0', '2011-05-02 17:23:54');

-- --------------------------------------------------------

--
-- Table structure for table `jasapengiriman`
--

CREATE TABLE `jasapengiriman` (
  `id_jasapengiriman` int(11) NOT NULL auto_increment,
  `nama_jasapengiriman` varchar(500) NOT NULL,
  `deskripsi_jasapengiriman` text NOT NULL,
  PRIMARY KEY  (`id_jasapengiriman`),
  UNIQUE KEY `nama_jasapengiriman` (`nama_jasapengiriman`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `jasapengiriman`
--

INSERT INTO `jasapengiriman` (`id_jasapengiriman`, `nama_jasapengiriman`, `deskripsi_jasapengiriman`) VALUES
(1, 'JNE', '						Ekspres Accros Nation                                  '),
(2, 'TIKI', 'Titipan kilat '),
(6, 'COD', 'Pembayaran di tempat bertemu..');

-- --------------------------------------------------------

--
-- Table structure for table `jenispengiriman`
--

CREATE TABLE `jenispengiriman` (
  `id_jenispengiriman` int(11) NOT NULL auto_increment,
  `id_jasapengiriman` int(11) NOT NULL,
  `nama_jenispengiriman` varchar(50) NOT NULL,
  `deskripsi_jenispengiriman` text NOT NULL,
  PRIMARY KEY  (`id_jenispengiriman`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='Tabel Jenis Pengiriman' AUTO_INCREMENT=10 ;

--
-- Dumping data for table `jenispengiriman`
--

INSERT INTO `jenispengiriman` (`id_jenispengiriman`, `id_jasapengiriman`, `nama_jenispengiriman`, `deskripsi_jenispengiriman`) VALUES
(1, 1, 'Reguler Service (REG)', 'Paket Antar Satu Minggu'),
(2, 1, 'Yakin Esok Sampai (YES)', 'Paket Antar 1 Malam'),
(3, 2, 'Reguler (REG)', 'Estimasi waktu yang di perkirakan antara 2 hari sampai 2 minggu tergantung jarak.'),
(4, 2, 'One Night Services (ONS)', 'Jasa pengiriman kilat Satu malam sampai.'),
(9, 6, 'COD', 'Cash On Delivery');

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id_kategori` int(11) NOT NULL auto_increment,
  `nama_kategori` varchar(50) NOT NULL,
  PRIMARY KEY  (`id_kategori`),
  UNIQUE KEY `nama_kategori` (`nama_kategori`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='Tabel Kategori' AUTO_INCREMENT=11 ;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id_kategori`, `nama_kategori`) VALUES
(1, 'Tshirt'),
(2, 'Celana'),
(3, 'Jaket'),
(4, 'Topi'),
(5, 'Sepatu');

-- --------------------------------------------------------

--
-- Table structure for table `kota`
--

CREATE TABLE `kota` (
  `id_kota` int(10) NOT NULL auto_increment,
  `id_provinsi` int(10) default NULL,
  `nama_kota` varchar(50) default NULL,
  `kabkota` varchar(20) default NULL,
  UNIQUE KEY `kota#PX` (`id_kota`),
  KEY `id_provinsi` (`id_provinsi`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=425 ;

--
-- Dumping data for table `kota`
--

INSERT INTO `kota` (`id_kota`, `id_provinsi`, `nama_kota`, `kabkota`) VALUES
(1, 1, 'KEPULAUAN SERIBU', 'KABUPATEN'),
(2, 1, 'JAKARTA SELATAN', 'KOTA'),
(3, 1, 'JAKARTA TIMUR', 'KOTA'),
(4, 1, 'JAKARTA PUSAT', 'KOTA'),
(5, 1, 'JAKARTA BARAT', 'KOTA'),
(6, 1, 'JAKARTA UTARA', 'KOTA'),
(7, 2, 'Kab.BOGOR', 'KABUPATEN'),
(8, 2, 'Kab.SUKABUMI', 'KABUPATEN'),
(9, 2, 'Kab.CIANJUR', 'KABUPATEN'),
(10, 2, 'Kab.BANDUNG', 'KABUPATEN'),
(11, 2, 'Kab.GARUT', 'KABUPATEN'),
(12, 2, 'Kab.TASIK MALAYA', 'KABUPATEN'),
(13, 2, 'Kab.CIAMIS', 'KABUPATEN'),
(14, 2, 'Kab.KUNINGAN', 'KABUPATEN'),
(15, 2, 'Kab.CIREBON', 'KABUPATEN'),
(16, 2, 'Kab.MAJALENGKA', 'KABUPATEN'),
(17, 2, 'Kab.SUMEDANG', 'KABUPATEN'),
(18, 2, 'Kab.INDRAMAYU', 'KABUPATEN'),
(19, 2, 'Kab.SUBANG', 'KABUPATEN'),
(20, 2, 'Kab.PURWAKARTA', 'KABUPATEN'),
(21, 2, 'Kab.KARAWANG', 'KABUPATEN'),
(22, 2, 'Kab.BEKASI', 'KABUPATEN'),
(23, 2, 'BOGOR', 'KOTA'),
(24, 2, 'SUKABUMI', 'KOTA'),
(25, 2, 'BANDUNG', 'KOTA'),
(26, 2, 'CIREBON', 'KOTA'),
(27, 2, 'BEKASI', 'KOTA'),
(28, 2, 'DEPOK', 'KOTA'),
(29, 2, 'CIMAHI', 'KOTA'),
(30, 2, 'TASIK MALAYA', 'KOTA'),
(31, 2, 'BANJAR', 'KOTA'),
(32, 3, 'CILACAP', 'KABUPATEN'),
(33, 3, 'BANYUMAS', 'KABUPATEN'),
(34, 3, 'PURBALINGGA', 'KABUPATEN'),
(35, 3, 'BANJARNEGARA', 'KABUPATEN'),
(36, 3, 'KEBUMEN', 'KABUPATEN'),
(37, 3, 'PURWOREJO', 'KABUPATEN'),
(38, 3, 'WONOSOBO', 'KABUPATEN'),
(39, 3, 'MAGELANG', 'KABUPATEN'),
(40, 3, 'BOYOLALI', 'KABUPATEN'),
(41, 3, 'KLATEN', 'KABUPATEN'),
(42, 3, 'SUKOHARJO', 'KABUPATEN'),
(43, 3, 'WONOGIRI', 'KABUPATEN'),
(44, 3, 'KARANG ANYAR', 'KABUPATEN'),
(45, 3, 'SRAGEN', 'KABUPATEN'),
(46, 3, 'GROBOGAN', 'KABUPATEN'),
(47, 3, 'BLORA', 'KABUPATEN'),
(48, 3, 'REMBANG', 'KABUPATEN'),
(49, 3, 'PATI', 'KABUPATEN'),
(50, 3, 'KUDUS', 'KABUPATEN'),
(51, 3, 'JEPARA', 'KABUPATEN'),
(52, 3, 'DEMAK', 'KABUPATEN'),
(53, 3, 'SEMARANG', 'KABUPATEN'),
(54, 3, 'TEMANGGUNG', 'KABUPATEN'),
(55, 3, 'KENDAL', 'KABUPATEN'),
(56, 3, 'BATANG', 'KABUPATEN'),
(57, 3, 'PEKALONGAN', 'KABUPATEN'),
(58, 3, 'PEMALANG', 'KABUPATEN'),
(59, 3, 'TEGAL', 'KABUPATEN'),
(60, 3, 'BREBES', 'KABUPATEN'),
(61, 3, 'MAGELANG', 'KOTA'),
(62, 3, 'SURAKARTA', 'KOTA'),
(63, 3, 'SALATIGA', 'KOTA'),
(64, 3, 'SEMARANG', 'KOTA'),
(65, 3, 'PEKALONGAN', 'KOTA'),
(66, 3, 'TEGAL', 'KOTA'),
(67, 4, 'KULON PROGO', 'KABUPATEN'),
(68, 4, 'BANTUL', 'KABUPATEN'),
(69, 4, 'GUNUNG KIDUL', 'KABUPATEN'),
(70, 4, 'SLEMAN', 'KABUPATEN'),
(71, 4, 'YOGYAKARTA', 'KOTA'),
(72, 5, 'PACITAN', 'KABUPATEN'),
(73, 5, 'PONOROGO', 'KABUPATEN'),
(74, 5, 'TRENGGALEK', 'KABUPATEN'),
(75, 5, 'TULUNGAGUNG', 'KABUPATEN'),
(76, 5, 'BLITAR', 'KABUPATEN'),
(77, 5, 'KEDIRI', 'KABUPATEN'),
(78, 5, 'MALANG', 'KABUPATEN'),
(79, 5, 'LUMAJANG', 'KABUPATEN'),
(80, 5, 'JEMBER', 'KABUPATEN'),
(81, 5, 'BANYUWANGI', 'KABUPATEN'),
(82, 5, 'BONDOWOSO', 'KABUPATEN'),
(83, 5, 'SITUBONDO', 'KABUPATEN'),
(84, 5, 'PROBOLINGGO', 'KABUPATEN'),
(85, 5, 'PASURUAN', 'KABUPATEN'),
(86, 5, 'SIDOARJO', 'KABUPATEN'),
(87, 5, 'MOJOKERTO', 'KABUPATEN'),
(88, 5, 'JOMBANG', 'KABUPATEN'),
(89, 5, 'NGANJUK', 'KABUPATEN'),
(90, 5, 'MADIUN', 'KABUPATEN'),
(91, 5, 'MAGETAN', 'KABUPATEN'),
(92, 5, 'NGAWI', 'KABUPATEN'),
(93, 5, 'BOJONEGORO', 'KABUPATEN'),
(94, 5, 'TUBAN', 'KABUPATEN'),
(95, 5, 'LAMONGAN', 'KABUPATEN'),
(96, 5, 'GRESIK', 'KABUPATEN'),
(97, 5, 'BANGKALAN', 'KABUPATEN'),
(98, 5, 'SAMPANG', 'KABUPATEN'),
(99, 5, 'PAMEKASAN', 'KABUPATEN'),
(100, 5, 'SUMENEP', 'KABUPATEN'),
(101, 5, 'KEDIRI', 'KOTA'),
(102, 5, 'BLITAR', 'KOTA'),
(103, 5, 'MALANG', 'KOTA'),
(104, 5, 'PROBOLINGGO', 'KOTA'),
(105, 5, 'PASURUAN', 'KOTA'),
(106, 5, 'MOJOKERTO', 'KOTA'),
(107, 5, 'MADIUN', 'KOTA'),
(108, 5, 'SURABAYA', 'KOTA'),
(109, 5, 'BATU', 'KOTA'),
(110, 6, 'SIMEULUE SINABUNG', 'KABUPATEN'),
(111, 6, 'ACEH SINGKIL', 'KABUPATEN'),
(112, 6, 'ACEH SELATAN', 'KABUPATEN'),
(113, 6, 'ACEH TENGGARA', 'KABUPATEN'),
(114, 6, 'ACEH TIMUR', 'KABUPATEN'),
(115, 6, 'ACEH TENGAH', 'KABUPATEN'),
(116, 6, 'ACEH BARAT', 'KABUPATEN'),
(117, 6, 'ACEH BESAR', 'KABUPATEN'),
(118, 6, 'PIDIE', 'KABUPATEN'),
(119, 6, 'BIREUEN', 'KABUPATEN'),
(120, 6, 'ACEH UTARA', 'KABUPATEN'),
(121, 6, 'ACEH BARAT DAYA', 'KABUPATEN'),
(122, 6, 'GAYO LUES', 'KABUPATEN'),
(123, 6, 'ACEH TAMIANG', 'KABUPATEN'),
(124, 6, 'NAGAN RAYA', 'KABUPATEN'),
(125, 6, 'ACEH JAYA', 'KABUPATEN'),
(126, 6, 'BANDA ACEH', 'KOTA'),
(127, 6, 'SABANG', 'KOTA'),
(128, 6, 'LANGSA', 'KOTA'),
(129, 6, 'LHOKSEUMAWE', 'KOTA'),
(130, 7, 'NIAS', 'KABUPATEN'),
(131, 7, 'MANDAILING NATAL', 'KABUPATEN'),
(132, 7, 'TAPANULI SELATAN', 'KABUPATEN'),
(133, 7, 'TAPANULI TENGAH', 'KABUPATEN'),
(134, 7, 'TAPANULI UTARA', 'KABUPATEN'),
(135, 7, 'TOBA SAMOSIR', 'KABUPATEN'),
(136, 7, 'LABUHAN BATU', 'KABUPATEN'),
(137, 7, 'ASAHAN', 'KABUPATEN'),
(138, 7, 'SIMALUNGUN', 'KABUPATEN'),
(139, 7, 'DAIRI', 'KABUPATEN'),
(140, 7, 'KARO', 'KABUPATEN'),
(141, 7, 'DELI SERDANG', 'KABUPATEN'),
(142, 7, 'LANGKAT', 'KABUPATEN'),
(143, 7, 'NIAS SELATAN', 'KABUPATEN'),
(144, 7, 'HUMBANG HASUNDUTAN', 'KABUPATEN'),
(145, 7, 'PAK-PAK BARAT', 'KABUPATEN'),
(146, 7, 'SIBOLGA', 'KOTA'),
(147, 7, 'TANJUNG BALAI', 'KOTA'),
(148, 7, 'PEMATANG SIANTAR', 'KOTA'),
(149, 7, 'TEBING TINGGI', 'KOTA'),
(150, 7, 'MEDAN', 'KOTA'),
(151, 7, 'BINJAI', 'KOTA'),
(152, 7, 'PADANG SIDEMPUAN', 'KOTA'),
(153, 8, 'KEPULAUAN MENTAWAI', 'KABUPATEN'),
(154, 8, 'PESISIR SELATAN', 'KABUPATEN'),
(155, 8, 'SOLOK', 'KABUPATEN'),
(156, 8, 'SAWAH LUNTO', 'KABUPATEN'),
(157, 8, 'TANAH DATAR', 'KABUPATEN'),
(158, 8, 'PADANG PARIAMAN', 'KABUPATEN'),
(159, 8, 'AGAM', 'KABUPATEN'),
(160, 8, 'LIMA PULUH KOTO', 'KABUPATEN'),
(161, 8, 'PASAMAN', 'KABUPATEN'),
(162, 8, 'PADANG', 'KOTA'),
(163, 8, 'SOLOK', 'KOTA'),
(164, 8, 'SAWAH LUNTO', 'KOTA'),
(165, 8, 'PADANG PANJANG', 'KOTA'),
(166, 8, 'BUKITTINGGI', 'KOTA'),
(167, 8, 'PAYAKUMBUH', 'KOTA'),
(168, 8, 'PARIAMAN', 'KOTA'),
(169, 9, 'KUANTAN SINGINGI', 'KABUPATEN'),
(170, 9, 'INDRAGIRI HULU', 'KABUPATEN'),
(171, 9, 'INDRAGIRI HILIR', 'KABUPATEN'),
(172, 9, 'PELALAWAN', 'KABUPATEN'),
(173, 9, 'SIAK', 'KABUPATEN'),
(174, 9, 'KAMPAR', 'KABUPATEN'),
(175, 9, 'ROKAN HULU', 'KABUPATEN'),
(176, 9, 'BENGKALIS', 'KABUPATEN'),
(177, 9, 'ROKAN HILIR', 'KABUPATEN'),
(178, 9, 'PEKANBARU', 'KOTA'),
(179, 9, 'DUMAI', 'KOTA'),
(180, 10, 'KERINCI', 'KABUPATEN'),
(181, 10, 'MERANGIN', 'KABUPATEN'),
(182, 10, 'SAROLANGUN', 'KABUPATEN'),
(183, 10, 'BATANG HARI', 'KABUPATEN'),
(184, 10, 'MUARO JAMBI', 'KABUPATEN'),
(185, 10, 'TANJUNG JABUNG TIMUR', 'KABUPATEN'),
(186, 10, 'TANJUNG JABUNG BARAT', 'KABUPATEN'),
(187, 10, 'TEBO', 'KABUPATEN'),
(188, 10, 'BUNGO', 'KABUPATEN'),
(189, 10, 'JAMBI', 'KOTA'),
(190, 11, 'OGAN KOMERING ULU', 'KABUPATEN'),
(191, 11, 'OGAN KOMERING ILIR', 'KABUPATEN'),
(192, 11, 'MUARA ENIM', 'KABUPATEN'),
(193, 11, 'LAHAT', 'KABUPATEN'),
(194, 11, 'MUSI RAWAS', 'KABUPATEN'),
(195, 11, 'MUSI BANYUASIN', 'KABUPATEN'),
(196, 11, 'BANYUASIN', 'KABUPATEN'),
(197, 11, 'PALEMBANG', 'KOTA'),
(198, 11, 'PRABUMULIH', 'KOTA'),
(199, 11, 'PAGARALAM', 'KOTA'),
(200, 11, 'LUBUK LINGGAU', 'KOTA'),
(201, 12, 'LAMPUNG BARAT', 'KABUPATEN'),
(202, 12, 'TANGGAMUS', 'KABUPATEN'),
(203, 12, 'LAMPUNG SELATAN', 'KABUPATEN'),
(204, 12, 'LAMPUNG TIMUR', 'KABUPATEN'),
(205, 12, 'LAMPUNG TENGAH', 'KABUPATEN'),
(206, 12, 'LAMPUNG UTARA', 'KABUPATEN'),
(207, 12, 'WAY KANAN', 'KABUPATEN'),
(208, 12, 'TULANGBAWANG', 'KABUPATEN'),
(209, 12, 'BANDAR LAMPUNG', 'KOTA'),
(210, 12, 'METRO', 'KOTA'),
(211, 13, 'SAMBAS', 'KABUPATEN'),
(212, 13, 'BENGKAYANG', 'KABUPATEN'),
(213, 13, 'LANDAK', 'KABUPATEN'),
(214, 13, 'PONTIANAK', 'KABUPATEN'),
(215, 13, 'SANGGAU', 'KABUPATEN'),
(216, 13, 'KETAPANG', 'KABUPATEN'),
(217, 13, 'SINTANG', 'KABUPATEN'),
(218, 13, 'KAPUAS HULU', 'KABUPATEN'),
(219, 13, 'PONTIANAK', 'KOTA'),
(220, 13, 'SINGKAWANG', 'KOTA'),
(221, 14, 'KOTAWARINGIN BARAT', 'KABUPATEN'),
(222, 14, 'KOTAWARINGIN TIMUR', 'KABUPATEN'),
(223, 14, 'KAPUAS', 'KABUPATEN'),
(224, 14, 'BARITO SELATAN', 'KABUPATEN'),
(225, 14, 'BARITO UTARA', 'KABUPATEN'),
(226, 14, 'SUKAMARA', 'KABUPATEN'),
(227, 14, 'LAMANDAU', 'KABUPATEN'),
(228, 14, 'SERUYAN', 'KABUPATEN'),
(229, 14, 'KATINGAN', 'KABUPATEN'),
(230, 14, 'PULANG PISAU', 'KABUPATEN'),
(231, 14, 'GUNUNG MAS', 'KABUPATEN'),
(232, 14, 'BARITO TIMUR', 'KABUPATEN'),
(233, 14, 'MURUNG RAYA', 'KABUPATEN'),
(234, 14, 'PALANGKA RAYA', 'KOTA'),
(235, 15, 'TANAH LAUT', 'KABUPATEN'),
(236, 15, 'KOTABARU', 'KABUPATEN'),
(237, 15, 'BANJAR', 'KABUPATEN'),
(238, 15, 'BARITO KUALA', 'KABUPATEN'),
(239, 15, 'TAPIN', 'KABUPATEN'),
(240, 15, 'HULU SUNGAI SELATAN', 'KABUPATEN'),
(241, 15, 'HULU SUNGAI TENGAH', 'KABUPATEN'),
(242, 15, 'HULU SUNGAI UTARA', 'KABUPATEN'),
(243, 15, 'TABALONG', 'KABUPATEN'),
(244, 15, 'TANAH BUMBU', 'KABUPATEN'),
(245, 15, 'BALANGAN', 'KABUPATEN'),
(246, 15, 'BANJARMASIN', 'KOTA'),
(247, 15, 'BANJARBARU', 'KOTA'),
(248, 16, 'PASIR', 'KABUPATEN'),
(249, 16, 'KUTAI BARAT', 'KABUPATEN'),
(250, 16, 'KUTAI', 'KABUPATEN'),
(251, 16, 'KUTAI TIMUR', 'KABUPATEN'),
(252, 16, 'BERAU', 'KABUPATEN'),
(253, 16, 'MALINAU', 'KABUPATEN'),
(254, 16, 'BULUNGAN', 'KABUPATEN'),
(255, 16, 'NUNUKAN', 'KABUPATEN'),
(256, 16, 'PENAJAM PASIR UTARA', 'KABUPATEN'),
(257, 16, 'BALIKPAPAN', 'KOTA'),
(258, 16, 'SAMARINDA', 'KOTA'),
(259, 16, 'TARAKAN', 'KOTA'),
(260, 16, 'BONTANG', 'KOTA'),
(261, 17, 'BOLAANG MONGONDOW', 'KABUPATEN'),
(262, 17, 'MINAHASA', 'KABUPATEN'),
(263, 17, 'SANGIHE', 'KABUPATEN'),
(264, 17, 'TALAUD', 'KABUPATEN'),
(265, 17, 'MINAHASA SELATAN', 'KABUPATEN'),
(266, 17, 'MANADO', 'KOTA'),
(267, 17, 'BITUNG', 'KOTA'),
(268, 17, 'TOMOHON', 'KOTA'),
(269, 18, 'PULAU BANGGAI', 'KABUPATEN'),
(270, 18, 'BANGGAI', 'KABUPATEN'),
(271, 18, 'MOROWALI', 'KABUPATEN'),
(272, 18, 'POSO', 'KABUPATEN'),
(273, 18, 'DONGGALA', 'KABUPATEN'),
(274, 18, 'TOLI-TOLI', 'KABUPATEN'),
(275, 18, 'BUOL', 'KABUPATEN'),
(276, 18, 'PARIGI MOUTONG', 'KABUPATEN'),
(277, 18, 'PALU', 'KOTA'),
(278, 19, 'SELAYAR', 'KABUPATEN'),
(279, 19, 'BULUKUMBA', 'KABUPATEN'),
(280, 19, 'BANTAENG', 'KABUPATEN'),
(281, 19, 'JENEPONTO', 'KABUPATEN'),
(282, 19, 'TAKALAR', 'KABUPATEN'),
(283, 19, 'GOWA', 'KABUPATEN'),
(284, 19, 'SINJAI', 'KABUPATEN'),
(285, 19, 'MAROS', 'KABUPATEN'),
(286, 19, 'PANGKAJENE', 'KABUPATEN'),
(287, 19, 'BARRU', 'KABUPATEN'),
(288, 19, 'BONE', 'KABUPATEN'),
(289, 19, 'SOPPENG', 'KABUPATEN'),
(290, 19, 'WAJO', 'KABUPATEN'),
(291, 19, 'SIDENRENG RAPPANG', 'KABUPATEN'),
(292, 19, 'PINRANG', 'KABUPATEN'),
(293, 19, 'ENREKANG', 'KABUPATEN'),
(294, 19, 'LUWU', 'KABUPATEN'),
(295, 19, 'TANA TORAJA', 'KABUPATEN'),
(296, 19, 'LUWU UTARA', 'KABUPATEN'),
(297, 19, 'LUWU TIMUR', 'KABUPATEN'),
(298, 19, 'MAKASSAR', 'KOTA'),
(299, 19, 'PARE-PARE', 'KOTA'),
(300, 19, 'PALOPO', 'KOTA'),
(301, 20, 'BUTON', 'KABUPATEN'),
(302, 20, 'MUNA', 'KABUPATEN'),
(303, 20, 'KENDARI', 'KABUPATEN'),
(304, 20, 'KOLAKA', 'KABUPATEN'),
(305, 20, 'KENDARI', 'KOTA'),
(306, 20, 'BAU-BAU', 'KOTA'),
(307, 20, 'KONAWE SELATAN', 'KOTA'),
(308, 21, 'MALUKU TENGGARA BARA', 'KABUPATEN'),
(309, 21, 'MALUKU TENGGARA', 'KABUPATEN'),
(310, 21, 'MALUKU TENGAH', 'KABUPATEN'),
(311, 21, 'BURU', 'KABUPATEN'),
(312, 21, 'AMBON', 'KOTA'),
(313, 22, 'JEMBRANA', 'KABUPATEN'),
(314, 22, 'TABANAN', 'KABUPATEN'),
(315, 22, 'BADUNG', 'KABUPATEN'),
(316, 22, 'GIANYAR', 'KABUPATEN'),
(317, 22, 'KLUNGKUNG', 'KABUPATEN'),
(318, 22, 'BANGLI', 'KABUPATEN'),
(319, 22, 'KARANG ASEM', 'KABUPATEN'),
(320, 22, 'BULELENG', 'KABUPATEN'),
(321, 22, 'DENPASAR', 'KOTA'),
(322, 23, 'LOMBOK BARAT', 'KABUPATEN'),
(323, 23, 'LOMBOK TENGAH', 'KABUPATEN'),
(324, 23, 'LOMBOK TIMUR', 'KABUPATEN'),
(325, 23, 'SUMBAWA', 'KABUPATEN'),
(326, 23, 'DOMPU', 'KABUPATEN'),
(327, 23, 'BIMA', 'KABUPATEN'),
(328, 23, 'MATARAM', 'KOTA'),
(329, 23, 'BIMA', 'KOTA'),
(330, 24, 'SUMBA BARAT', 'KABUPATEN'),
(331, 24, 'SUMBA TIMUR', 'KABUPATEN'),
(332, 24, 'KUPANG', 'KABUPATEN'),
(333, 24, 'TIMOR TENGAH SELATAN', 'KABUPATEN'),
(334, 24, 'TIMOR TENGAH UTARA', 'KABUPATEN'),
(335, 24, 'BELU', 'KABUPATEN'),
(336, 24, 'ALOR', 'KABUPATEN'),
(337, 24, 'LEMBATA', 'KABUPATEN'),
(338, 24, 'FLORES TIMUR', 'KABUPATEN'),
(339, 24, 'SIKKA', 'KABUPATEN'),
(340, 24, 'ENDE', 'KABUPATEN'),
(341, 24, 'NGADA', 'KABUPATEN'),
(342, 24, 'MANGGARAI', 'KABUPATEN'),
(343, 24, 'ROTE NDAO', 'KABUPATEN'),
(344, 24, 'MANGGARAI BARAT', 'KABUPATEN'),
(345, 24, 'KUPANG', 'KOTA'),
(346, 25, 'MERAUKE', 'KABUPATEN'),
(347, 25, 'JAYA WIJAYA', 'KABUPATEN'),
(348, 25, 'JAYAPURA', 'KABUPATEN'),
(349, 25, 'NABIRE', 'KABUPATEN'),
(350, 25, 'YAPEN WAROPEN', 'KABUPATEN'),
(351, 25, 'BIAK NUMFOR', 'KABUPATEN'),
(352, 25, 'PANIAI', 'KABUPATEN'),
(353, 25, 'PUNCAK JAYA', 'KABUPATEN'),
(354, 25, 'MIMIKA', 'KABUPATEN'),
(355, 25, 'BOVEN DIGOEL', 'KABUPATEN'),
(356, 25, 'MAPPI', 'KABUPATEN'),
(357, 25, 'ASMAT', 'KABUPATEN'),
(358, 25, 'YAKUHIMO', 'KABUPATEN'),
(359, 25, 'GUNUNG BINTANG', 'KABUPATEN'),
(360, 25, 'TOLIKARA', 'KABUPATEN'),
(361, 25, 'SARMI', 'KABUPATEN'),
(362, 25, 'KEEROM', 'KABUPATEN'),
(363, 25, 'WAROPEN', 'KABUPATEN'),
(364, 25, 'JAYAPURA', 'KOTA'),
(365, 26, 'BENGKULU SELATAN', 'KABUPATEN'),
(366, 26, 'REJANG LEBONG', 'KABUPATEN'),
(367, 26, 'BENGKULU UTARA', 'KABUPATEN'),
(368, 26, 'KAUR', 'KABUPATEN'),
(369, 26, 'SELUMA', 'KABUPATEN'),
(370, 26, 'MUKO-MUKO', 'KABUPATEN'),
(371, 26, 'BENGKULU', 'KOTA'),
(372, 27, 'PANDEGLANG', 'KABUPATEN'),
(373, 27, 'LEBAK', 'KABUPATEN'),
(374, 27, 'TANGERANG', 'KABUPATEN'),
(375, 27, 'SERANG', 'KABUPATEN'),
(376, 27, 'TANGERANG', 'KOTA'),
(377, 27, 'CILEGON', 'KOTA'),
(378, 28, 'HALMAHERA BARAT', 'KABUPATEN'),
(379, 28, 'HALMAHERA TENGAH', 'KABUPATEN'),
(380, 28, 'PULAU SULA', 'KABUPATEN'),
(381, 28, 'HALMAHERA SELATAN', 'KABUPATEN'),
(382, 28, 'HALMAHERA UTARA', 'KABUPATEN'),
(383, 28, 'HALMAHERA TIMUR', 'KABUPATEN'),
(384, 28, 'TERNATE', 'KOTA'),
(385, 28, 'TIDORE', 'KOTA'),
(386, 29, 'BANGKA', 'KABUPATEN'),
(387, 29, 'BELITUNG', 'KABUPATEN'),
(388, 29, 'BANGKA SELATAN', 'KABUPATEN'),
(389, 29, 'BANGKA TENGAH', 'KABUPATEN'),
(390, 29, 'BANGKA BARAT', 'KABUPATEN'),
(391, 29, 'BELITUNG TIMUR', 'KABUPATEN'),
(392, 29, 'PANGKALPINANG', 'KOTA'),
(393, 30, 'BOALEMO', 'KABUPATEN'),
(394, 30, 'GORONTALO', 'KABUPATEN'),
(395, 30, 'PUHUWATO', 'KABUPATEN'),
(396, 30, 'BONE BOLANGO', 'KABUPATEN'),
(397, 30, 'GORONTALO', 'KOTA'),
(398, 31, 'FAK-FAK', 'KABUPATEN'),
(399, 31, 'SORONG', 'KABUPATEN'),
(400, 31, 'MANOKWARI', 'KABUPATEN'),
(401, 31, 'KAIMANA', 'KABUPATEN'),
(402, 31, 'SORONG SELATAN', 'KABUPATEN'),
(403, 31, 'RAJA AMPAT', 'KABUPATEN'),
(404, 31, 'TELUK BINTUNI', 'KABUPATEN'),
(405, 31, 'TELUK WONDANA', 'KABUPATEN'),
(406, 31, 'SORONG', 'KOTA'),
(407, 32, 'KARIMUN', 'KABUPATEN'),
(408, 32, 'KEPULAUAN RIAU', 'KABUPATEN'),
(409, 32, 'NATUNA', 'KABUPATEN'),
(410, 32, 'BATAM', 'KOTA'),
(411, 32, 'TANJUNG PINANG', 'KOTA'),
(412, 33, 'MAMUJU', 'KABUPATEN'),
(413, 33, 'MAMUJU UTARA', 'KABUPATEN'),
(414, 33, 'MAJENE', 'KABUPATEN'),
(415, 33, 'POLEWALI MANDAR', 'KABUPATEN'),
(416, 33, 'MAMASA', 'KOTA'),
(417, 11, 'OGAN ILIR', 'KABUPATEN'),
(418, 11, 'OKU TIMUR', 'KABUPATEN'),
(419, 11, 'OKU SELATAN', 'KABUPATEN'),
(420, 21, 'SERAM BAGIAN BARAT', 'KABUPATEN'),
(421, 21, 'SERAM BARAT TIMUR', 'KABUPATEN'),
(422, 21, 'ARU', 'KABUPATEN'),
(423, 17, 'MINAHASA UTARA', 'KABUPATEN'),
(424, 25, 'SUPIORI', 'KOTA');

-- --------------------------------------------------------

--
-- Table structure for table `member`
--

CREATE TABLE `member` (
  `id_member` int(11) NOT NULL auto_increment,
  `id_kota` int(11) NOT NULL,
  `nama_member` varchar(50) NOT NULL,
  `alamat_member` text NOT NULL,
  `telp_member` varchar(20) NOT NULL,
  `kodepos_member` varchar(6) NOT NULL,
  `email_member` varchar(50) NOT NULL,
  `password_member` varchar(32) NOT NULL,
  `verificationcode_member` varchar(32) NOT NULL,
  `status_member` enum('0','1') NOT NULL,
  PRIMARY KEY  (`id_member`),
  UNIQUE KEY `email_member` (`email_member`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='Tabel Member' AUTO_INCREMENT=3 ;

--
-- Dumping data for table `member`
--

INSERT INTO `member` (`id_member`, `id_kota`, `nama_member`, `alamat_member`, `telp_member`, `kodepos_member`, `email_member`, `password_member`, `verificationcode_member`, `status_member`) VALUES
(1, 25, 'bud igutama', 'bxjsk', '025223456787', '12345', 'budi@localhost', 'e807f1fcf82d132f9bb018ca6738a19f', 'c786d56895d16299e00f10035f80add3', '1'),
(2, 24, 'gutama', 'vajhsv', '9872345656788', '12345', 'gutama@localhost', 'e807f1fcf82d132f9bb018ca6738a19f', '21a12933108a8494b35f7da09b68ee7f', '1');

-- --------------------------------------------------------

--
-- Table structure for table `ongkir`
--

CREATE TABLE `ongkir` (
  `id_ongkir` int(11) NOT NULL auto_increment,
  `id_kota` int(11) NOT NULL,
  `id_jenispengiriman` int(11) NOT NULL,
  `harga_ongkir` int(11) NOT NULL,
  PRIMARY KEY  (`id_ongkir`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='Tabel Ongkos Kirim' AUTO_INCREMENT=66 ;

--
-- Dumping data for table `ongkir`
--

INSERT INTO `ongkir` (`id_ongkir`, `id_kota`, `id_jenispengiriman`, `harga_ongkir`) VALUES
(1, 1, 1, 12000),
(2, 2, 1, 8000),
(3, 3, 1, 8000),
(4, 4, 1, 8000),
(5, 5, 1, 8000),
(6, 6, 1, 8000),
(7, 7, 1, 8000),
(8, 8, 1, 9000),
(9, 9, 1, 10000),
(10, 10, 1, 5000),
(11, 11, 1, 7000),
(12, 12, 1, 8000),
(13, 13, 1, 10000),
(14, 14, 1, 14000),
(15, 15, 1, 14000),
(16, 16, 1, 14000),
(17, 17, 1, 14000),
(18, 18, 1, 17000),
(19, 19, 1, 9000),
(20, 20, 1, 8000),
(21, 21, 1, 12000),
(22, 22, 1, 10000),
(23, 23, 1, 8000),
(24, 24, 1, 9000),
(25, 25, 1, 100),
(26, 26, 1, 9000),
(27, 27, 1, 9000),
(28, 28, 1, 10000),
(29, 29, 1, 7000),
(30, 30, 1, 8000),
(31, 1, 2, 12000),
(32, 2, 2, 12000),
(33, 3, 2, 12000),
(34, 4, 2, 12000),
(35, 5, 2, 12000),
(36, 6, 2, 12000),
(37, 7, 2, 14000),
(38, 8, 2, 14000),
(39, 9, 2, 14000),
(40, 10, 2, 10000),
(41, 11, 2, 10000),
(42, 12, 2, 10000),
(43, 13, 2, 12000),
(44, 14, 2, 20500),
(45, 15, 2, 16000),
(46, 16, 2, 14000),
(47, 17, 2, 10500),
(48, 18, 2, 20500),
(49, 19, 2, 10500),
(50, 20, 2, 10500),
(51, 21, 2, 18000),
(52, 22, 2, 14000),
(53, 23, 2, 14000),
(54, 24, 2, 14000),
(55, 25, 2, 9000),
(57, 26, 2, 16000),
(58, 27, 2, 14000),
(59, 28, 2, 16000),
(60, 29, 2, 7000),
(61, 30, 1, 10000),
(63, 32, 3, 21000),
(64, 10, 9, 7000),
(65, 25, 9, 8000);

-- --------------------------------------------------------

--
-- Table structure for table `pembelian`
--

CREATE TABLE `pembelian` (
  `id_pembelian` int(11) NOT NULL auto_increment,
  `session_id` varchar(32) NOT NULL,
  `tgl_beli` datetime NOT NULL,
  `tgl_bayar` datetime NOT NULL,
  `status` enum('pesan','bayar','konfirmasi','kirim','terima') NOT NULL,
  `id_member` int(11) NOT NULL,
  `pembayaran` enum('transfer','paypal','cod') NOT NULL,
  `kirim_nama` varchar(50) NOT NULL,
  `kirim_alamat` text NOT NULL,
  `kirim_telp` varchar(12) NOT NULL,
  `kirim_kota` int(11) NOT NULL,
  `kirim_kdpos` varchar(6) NOT NULL,
  `kirim_ongkos` int(32) NOT NULL,
  `kirim_id` int(11) NOT NULL,
  `kirim_resi` varchar(15) NOT NULL,
  `transfer_bank` varchar(20) NOT NULL,
  `transfer_no` varchar(30) NOT NULL,
  `transfer_jumlah` double NOT NULL,
  `id_rekening` int(11) NOT NULL,
  `totalbayar` double NOT NULL,
  PRIMARY KEY  (`id_pembelian`),
  KEY `kota_FK` (`kirim_kota`),
  KEY `member_FK` (`id_member`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `pembelian`
--

INSERT INTO `pembelian` (`id_pembelian`, `session_id`, `tgl_beli`, `tgl_bayar`, `status`, `id_member`, `pembayaran`, `kirim_nama`, `kirim_alamat`, `kirim_telp`, `kirim_kota`, `kirim_kdpos`, `kirim_ongkos`, `kirim_id`, `kirim_resi`, `transfer_bank`, `transfer_no`, `transfer_jumlah`, `id_rekening`, `totalbayar`) VALUES
(1, '3mu6eup34bja88ijkkuafa28o3', '2011-05-31 19:54:54', '2011-05-31 21:43:47', 'terima', 2, 'transfer', 'gutama', 'vajhsv', '987234565678', 24, '12345', 9000, 1, '123456789876', 'BCA', '12345678987', 79000, 2, 79000),
(2, '3mu6eup34bja88ijkkuafa28o3', '2011-05-31 21:35:29', '2011-05-31 23:09:58', 'terima', 2, 'transfer', 'gutama', 'vajhsv', '987234565678', 24, '12345', 9000, 1, '56465465546364', 'BCA', '123456789', 72000, 1, 72000),
(3, '3mu6eup34bja88ijkkuafa28o3', '2011-05-31 21:40:11', '2011-05-31 23:31:59', 'bayar', 2, 'transfer', 'gutama', 'vajhsv', '987234565678', 24, '12345', 14000, 2, '', 'Mandiri', '12345678987', 99000, 2, 99000),
(4, '3mu6eup34bja88ijkkuafa28o3', '2011-05-31 22:00:30', '2011-05-31 23:33:53', 'bayar', 2, 'transfer', 'gutama', 'vajhsv', '987234565678', 24, '12345', 14000, 2, '', 'Mandiri', '12345678987', 99000, 2, 99000),
(5, '3mu6eup34bja88ijkkuafa28o3', '2011-05-31 22:06:01', '2011-05-31 23:48:10', 'bayar', 2, 'transfer', 'gutama', 'vajhsv', '987234565678', 24, '12345', 14000, 2, '', 'ada aja', '12345678987', 77000, 3, 77000),
(6, 'l4pf197r6ej4e5c4gsob3q52j7', '2011-06-01 21:07:37', '2011-06-01 22:08:49', 'terima', 1, 'transfer', 'bud igutama', 'bxjsk', '025223456787', 25, '12345', 9000, 2, '1234567890987', 'Mandiri', '12345678904', 173000, 2, 173000),
(7, 'l4pf197r6ej4e5c4gsob3q52j7', '2011-06-02 10:31:02', '2011-06-02 11:40:31', 'terima', 1, 'transfer', 'bud igutama', 'bxjsk', '025223456787', 25, '12345', 9000, 2, '12345676545678', 'Mandiri', '12345678987', 94000, 2, 94000),
(8, 'l4pf197r6ej4e5c4gsob3q52j7', '2011-06-02 10:34:52', '0000-00-00 00:00:00', 'pesan', 1, '', 'bud igutama', 'bxjsk', '025223456787', 25, '12345', 100, 1, '', '', '', 0, 0, 342300),
(9, 'oppora1g66il982upa5uc83am7', '2011-06-07 22:49:41', '0000-00-00 00:00:00', 'pesan', 2, '', 'gutama', 'vajhsv', '987234565678', 24, '12345', 14000, 2, '', '', '', 0, 0, 77000);

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `id_produk` int(4) unsigned zerofill NOT NULL auto_increment,
  `id_kategori` int(11) NOT NULL,
  `nama_produk` varchar(100) NOT NULL,
  `harga_produk` int(11) NOT NULL,
  `deskripsi_produk` text NOT NULL,
  `diskon_produk` int(11) NOT NULL,
  `rating_produk` float NOT NULL,
  `voterrating_produk` int(11) NOT NULL,
  `viewcounter_produk` int(11) NOT NULL,
  PRIMARY KEY  (`id_produk`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Tabel Produk' AUTO_INCREMENT=5 ;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`id_produk`, `id_kategori`, `nama_produk`, `harga_produk`, `deskripsi_produk`, `diskon_produk`, `rating_produk`, `voterrating_produk`, `viewcounter_produk`) VALUES
(0001, 1, 'under construction', 70000, 'Tshirt Under Contrution', 10, 0, 0, 25),
(0002, 1, 'wall Climbing', 85000, 'T-Shirt gaul bergambar Wall Climbing sangat cocok dipakai anak muda gaul..', 0, 0, 0, 30),
(0003, 1, 'Acta Surya', 70000, 'Kaos Acta surya Bagus deh..', 0, 0, 0, 9),
(0004, 2, 'panjang', 120000, 'celana bagus', 10, 0, 0, 2);

-- --------------------------------------------------------

--
-- Table structure for table `provinsi`
--

CREATE TABLE `provinsi` (
  `id_provinsi` int(10) NOT NULL auto_increment,
  `nama_provinsi` varchar(30) default NULL,
  KEY `id_prov` (`id_provinsi`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=34 ;

--
-- Dumping data for table `provinsi`
--

INSERT INTO `provinsi` (`id_provinsi`, `nama_provinsi`) VALUES
(1, 'DKI JAKARTA'),
(2, 'JAWA BARAT'),
(3, 'JAWA TENGAH'),
(4, 'D I YOGYAKARTA'),
(5, 'JAWA TIMUR'),
(6, 'ACEH DARUSSALAM'),
(7, 'SUMATERA UTARA'),
(8, 'SUMATERA BARAT'),
(9, 'RIAU'),
(10, 'JAMBI'),
(11, 'SUMATERA SELATAN'),
(12, 'LAMPUNG'),
(13, 'KALIMANTAN BARAT'),
(14, 'KALIMANTAN TENGAH'),
(15, 'KALIMANTAN SELATAN'),
(16, 'KALIMANTAN TIMUR'),
(17, 'SULAWESI UTARA'),
(18, 'SULAWESI TENGAH'),
(19, 'SULAWESI SELATAN'),
(20, 'SULAWESI TENGGARA'),
(21, 'MALUKU'),
(22, 'BALI'),
(23, 'NUSA TENGGARA BARAT'),
(24, 'NUSA TENGGARA TIMUR'),
(25, 'PAPUA'),
(26, 'BENGKULU'),
(27, 'BANTEN'),
(28, 'MALUKU UTARA'),
(29, 'BANGKA BELITUNG'),
(30, 'GORONTALO'),
(31, 'IRIAN JAYA BARAT'),
(32, 'KEPULAUAN RIAU'),
(33, 'SULAWESI BARAT');

-- --------------------------------------------------------

--
-- Table structure for table `rekening`
--

CREATE TABLE `rekening` (
  `id_rekening` int(11) NOT NULL auto_increment,
  `nama_rekening` varchar(50) NOT NULL,
  `bank_rekening` varchar(50) NOT NULL,
  `cabang_rekening` varchar(100) NOT NULL,
  `no_rekening` varchar(32) NOT NULL,
  `gambar_rekening` varchar(200) NOT NULL,
  PRIMARY KEY  (`id_rekening`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='Tabel Rekening' AUTO_INCREMENT=6 ;

--
-- Dumping data for table `rekening`
--

INSERT INTO `rekening` (`id_rekening`, `nama_rekening`, `bank_rekening`, `cabang_rekening`, `no_rekening`, `gambar_rekening`) VALUES
(1, 'Gunawan', 'BCA', 'Bandung', '1023912510214', 'icon_bca.gif'),
(2, 'Gunawan', 'Mandiri', 'Bandung', '1248096320915', 'icon_mandiri.gif'),
(3, 'Gunawan', 'BNI', 'Bandung', '10235210938', 'icon_bni.gif');

-- --------------------------------------------------------

--
-- Table structure for table `retur`
--

CREATE TABLE `retur` (
  `id_retur` int(11) NOT NULL auto_increment,
  `id_member` int(11) NOT NULL,
  `session_id` varchar(120) NOT NULL,
  `jasa_kirim` varchar(30) NOT NULL,
  `no_kirim` varchar(20) NOT NULL,
  `tgl_retur` date NOT NULL,
  `total_retur` int(11) NOT NULL,
  `status_retur` varchar(10) NOT NULL,
  PRIMARY KEY  (`id_retur`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `retur`
--


-- --------------------------------------------------------

--
-- Table structure for table `temp_pemesanan`
--

CREATE TABLE `temp_pemesanan` (
  `id_temp` int(11) NOT NULL auto_increment,
  `id_detailproduk` int(11) NOT NULL,
  `session_id` varchar(32) NOT NULL,
  `qty` int(11) NOT NULL,
  `berat` float NOT NULL,
  `temp_hargadiskon` int(20) NOT NULL,
  `tanggal_pesan` datetime NOT NULL,
  PRIMARY KEY  (`id_temp`),
  KEY `produk_FK` (`id_detailproduk`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `temp_pemesanan`
--


-- --------------------------------------------------------

--
-- Table structure for table `temp_retur`
--

CREATE TABLE `temp_retur` (
  `id_tempretur` int(11) NOT NULL auto_increment,
  `id_detailproduk` int(11) NOT NULL,
  `session_id` varchar(32) NOT NULL,
  `jumlah_retur` int(11) NOT NULL,
  `id_pembelian` int(11) NOT NULL,
  `komplain` text NOT NULL,
  `tgl_retur` datetime NOT NULL,
  PRIMARY KEY  (`id_tempretur`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `temp_retur`
--

INSERT INTO `temp_retur` (`id_tempretur`, `id_detailproduk`, `session_id`, `jumlah_retur`, `id_pembelian`, `komplain`, `tgl_retur`) VALUES
(1, 1, 'eplhnip7i16bd98am2cs1gbpg1', 1, 2, 'chbc', '2011-06-06 12:31:14');

-- --------------------------------------------------------

--
-- Table structure for table `testi_produk`
--

CREATE TABLE `testi_produk` (
  `id_testi` int(11) NOT NULL auto_increment,
  `id_produk` int(8) NOT NULL,
  `id_member` int(11) NOT NULL,
  `testimoni` text NOT NULL,
  `status_testi` enum('1','0') NOT NULL,
  `tgl_testi` datetime NOT NULL,
  PRIMARY KEY  (`id_testi`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `testi_produk`
--

INSERT INTO `testi_produk` (`id_testi`, `id_produk`, `id_member`, `testimoni`, `status_testi`, `tgl_testi`) VALUES
(1, 2, 1, 'testiii', '1', '2011-06-01 23:31:57'),
(2, 1, 1, 'tesstiiimonii', '1', '2011-06-01 23:54:10'),
(8, 2, 0, 'terima kasih', '1', '2011-06-02 01:03:52'),
(9, 2, 1, 'sgdDfgvdsVFGsdfv', '1', '2011-06-02 01:29:30'),
(10, 1, 1, 'gfvuygygiuhiuu hbhbi', '1', '2011-06-02 10:46:21'),
(11, 1, 0, 'terima lkasihhh', '1', '2011-06-02 10:47:53');

-- --------------------------------------------------------

--
-- Table structure for table `ukuran`
--

CREATE TABLE `ukuran` (
  `id_ukuran` int(11) NOT NULL auto_increment,
  `nama_ukuran` varchar(10) NOT NULL,
  `kategori` varchar(20) NOT NULL,
  `spek1` int(11) NOT NULL,
  `spek2` int(11) NOT NULL,
  `spek3` int(11) NOT NULL,
  `spek4` int(11) NOT NULL,
  PRIMARY KEY  (`id_ukuran`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Tabel Ukuran' AUTO_INCREMENT=7 ;

--
-- Dumping data for table `ukuran`
--

INSERT INTO `ukuran` (`id_ukuran`, `nama_ukuran`, `kategori`, `spek1`, `spek2`, `spek3`, `spek4`) VALUES
(1, 'S', 'TSHIRT', 120, 140, 150, 70),
(2, 'M', 'TSHIRT', 120, 111, 90, 67),
(3, 'L', 'TSHIRT', 0, 0, 0, 0),
(4, 'XL', 'TSHIRT', 111, 100, 112, 90),
(5, 'ALL SIZE', 'CELANA', 44, 56, 0, 0),
(6, 'XXL', 'TSHIRT', 60, 120, 100, 90);

-- --------------------------------------------------------

--
-- Table structure for table `warna`
--

CREATE TABLE `warna` (
  `id_warna` int(11) NOT NULL auto_increment,
  `nama_warna` varchar(50) NOT NULL,
  PRIMARY KEY  (`id_warna`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Tabel Warna' AUTO_INCREMENT=9 ;

--
-- Dumping data for table `warna`
--

INSERT INTO `warna` (`id_warna`, `nama_warna`) VALUES
(1, 'HITAM'),
(2, 'BIRU'),
(3, 'COKLAT'),
(4, 'MERAH'),
(5, 'PUTIH'),
(6, 'ABU-ABU'),
(7, 'KUNING'),
(8, 'MAGENTA');
