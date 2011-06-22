<?php
include("../../fungsi/koneksi.php");
include("../../fungsi/function.php");
include ('class.ezpdf.php');
	$iddp = $_POST['iddp'];
	
$pdf = new Cezpdf(a6,potrait);
// Set margin dan font
$pdf->ezSetCmMargins(3, 3, 3, 3);
$pdf->selectFont('fonts/Courier.afm');

$all = $pdf->openObject();

// Tampilkan logo
$pdf->setStrokeColor(0, 0, 0, 1);
$pdf->addJpegFromFile('../images/logo.jpg',14,335,34);
$pdf->addText(180,335,8,'Tanggal:' . tgl_indo(date( 'Y-m-d ')));

// Teks di tengah atas untuk judul header
		$sql = mysql_query("SELECT * FROM pembelian a, kota b
							WHERE a.kirim_kota=b.id_kota
							AND a.id_pembelian = $iddp");
		$r = mysql_fetch_array($sql);
// Garis atas untuk header
$pdf->line(10, 365, 290, 365);
$pdf->line(10, 333, 290, 333);
$pdf->line(10, 365, 10, 235);
$pdf->line(290, 365, 290,235);
$pdf->line(10, 235, 290, 235);

$pdf->addText(76, 320, 15,'<b>Pengiriman Barang</b>');
$pdf->addText(13, 300, 12,'<b>Kepada Yth. '.$r['kirim_nama'].'</b>');
$pdf->addText(13, 290, 10,'<b>               </b>');
$pdf->addText(13, 280, 10,'<b>Alamat     :'.substr($r['kirim_alamat'],0,26).'</b>');
$pdf->addText(13, 270, 10,'<b>            '.substr($r['kirim_alamat'],26,60).'</b>');
$pdf->addText(13, 260, 10,'<b>            '.$r['nama_kota'].'</b>');
$pdf->addText(13, 250, 10,'<b>Kode Pos   :'.$r['kirim_kdpos'].'</b>');
$pdf->addText(13, 240, 10,'<b>Telepon    :'.$r['kirim_telp'].'</b>');

$pdf->line(10, 225, 290, 225);
$pdf->line(10, 225, 10, 170);
$pdf->line(290, 225, 290,170);
$pdf->line(10, 170, 290, 170);

$pdf->addText(13, 213, 12,'<b>Dari :</b>');
$pdf->addText(13, 205, 10,'<b>Elitez Distro Bandung</b>');
$pdf->addText(13, 195, 10,'<b>Alamat   : Jl. buah batu no. 238 Bandung</b>');
$pdf->addText(13, 185, 10,'<b>           Jawa Barat</b>');
$pdf->addText(13, 175, 10,'<b>Telpon   : 022 - 6976 0117</b>');

$pdf->closeObject();

// Tampilkan object di semua halaman
$pdf->addObject($all, 'all');


$pdf->ezStream();
?>
