<?php
include("../../connect.php");
include ('class.ezpdf.php');
$pdf = new Cezpdf(a6,potrait);
// Set margin dan font
$pdf->ezSetCmMargins(3, 3, 3, 3);
$pdf->selectFont('fonts/Courier.afm');

$all = $pdf->openObject();

// Tampilkan logo
$pdf->setStrokeColor(0, 0, 0, 1);
$pdf->addJpegFromFile('../images/logo.jpg',20,335,130);
$pdf->addText(200,335,8,'Tanggal:' . date( 'd-m-Y '));

// Teks di tengah atas untuk judul header
// Garis atas untuk header
$pdf->line(10, 365, 290, 365);
$pdf->line(10, 333, 290, 333);
$pdf->line(10, 365, 10, 160);
$pdf->line(290, 365, 290,160);
$pdf->line(10, 160, 290, 160);

$pdf->addText(76, 320, 15,'<b>Pengiriman Barang</b>');
$pdf->addText(13, 300, 12,'<b>Kepada Yth.</b>');
$pdf->addText(13, 290, 10,'<b>'.$_POST['nama'].'</b>');
$pdf->addText(13, 280, 10,'<b>Alamat     :'.substr($_POST['alamat'],0,26).'</b>');
$pdf->addText(13, 270, 10,'<b>            '.substr($_POST['alamat'],26,60).'</b>');
$pdf->addText(13, 250, 10,'<b>Telepon    :'.$_POST['telp'].'</b>');
$pdf->addText(200, 210, 10,'<b>Diterima Oleh:</b>');
$pdf->addText(200, 170, 10,'<b>(            )</b>');

$pdf->closeObject();

// Tampilkan object di semua halaman
$pdf->addObject($all, 'all');


$pdf->ezStream();
?>
