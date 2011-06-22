<?php
session_start();
include("../../fungsi/koneksi.php");
include("../../fungsi/function.php");
include ('class.ezpdf.php');
$pdf = new Cezpdf();
// Set margin dan font
$pdf->ezSetCmMargins(3, 3, 3, 3);
$pdf->selectFont('fonts/Courier.afm');

$all = $pdf->openObject();

// Tampilkan logo
$pdf->setStrokeColor(0, 0, 0, 1);
$pdf->addJpegFromFile('../images/logo.jpg',20,790,60);

// Teks di tengah atas untuk judul header
$pdf->addText(220, 815, 16,'<b>LAPORAN  PENJUALAN</b>');
if ($_POST['per']=="Hari"){
$pdf->addText(120, 800, 16,'<b>'.$_POST['tampil']. '</b>'); }
if ($_POST['per']=="Bulan"){
$pdf->addText(235, 800, 16,'<b>'.$_POST['tampil'].'</b>'); }
if ($_POST['per']=="Tahun"){
$pdf->addText(258, 800, 16,'<b>'.$_POST['tampil']. '</b>'); }
// Garis atas untuk header
$pdf->line(10, 785, 578, 785);

// Garis bawah untuk footer
$pdf->line(10, 50, 578, 50);
// Teks kiri bawah
$pdf->addText(30,34,8,'Dicetak tgl:' . tgl_indo(date( 'Y-m-d, H:i:s')));
$pdf->addText(30, 55, 8,'<b>Dicetak Oleh  : '.$_SESSION['nama_admin']. ',-</b>');

$pdf->closeObject();

// Tampilkan object di semua halaman
$pdf->addObject($all, 'all');
$tgl=$_POST['tgl'];
$cari = stripslashes($_POST['cmd']);
$sql = ("SELECT * FROM detail_pembelian a, produk b, detailproduk c, ukuran d, warna e, pembelian f, member g
				  WHERE a.idpembelian=f.id_pembelian
				  AND a.id_detailproduk=c.id_detailproduk
				  AND f.id_member=g.id_member
				  AND b.id_produk=c.id_produk
				  AND c.id_ukuran=d.id_ukuran
				  AND c.id_warna=e.id_warna
				  AND f.status='terima'
				  $cari");
  $qry = mysql_query($sql) or die ("Gagal query".mysql_error());
  $jml= mysql_num_rows($qry);
  $i= 1;
  
  while ($data1 =mysql_fetch_array($qry)) {
   $sub=($data1['hargabeli'] * $data1['qty']);
   $total=$total+$sub;
   $jumlah=$jumlah+$data1[qty];
   $data[$i]=array('<b>No</b>'=>$i, 
                  '<b>Id</b>'=>$data1[id_pembelian],
				  '<b>Tgl</b>'=>tgl_indo($data1[tgl_beli]),
				  '<b>Nama Member</b>'=>'['.$data1[id_member].']'.$data1[nama_member],
				  '<b>Produk</b>'=>$data1[nama_produk],
				  '<b>Size</b>'=>$data1[nama_ukuran],
				  '<b>Warna</b>'=>$data1[nama_warna],
				  '<b>qty</b>'=>$data1[qty]. 'pcs',
				  '<b>Harga Satuan</b>'=>'Rp.'.number_format($data1[hargabeli]),
				  );
  $i++;
}
// Penomoran halaman
$pdf->ezStartPageNumbers(320, 15, 8);

$pdf->ezTable($data, '', '', ''); 
$pdf->addText(400, 55, 8,'<b>Total Penjualan      : Rp.'.number_format($total). ',-</b>');
$pdf->addText(400, 65, 8,'<b>Total Penjualan      : $ '.round(konversikedolar($total),2). ' </b>');
$pdf->addText(400, 75, 8,'<b>Jumlah Produk terjual: '.$jumlah. ' Pcs</b>');


$pdf->ezStream();
?>
