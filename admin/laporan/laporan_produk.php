<?php
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
$pdf->addText(126, 815, 16,'<b>LAPORAN  DATA PRODUK '.strtoupper($_POST['kat']).'</b>');
$pdf->addText(235, 800, 16,'<b>'.strtoupper($_POST['tampil']).'</b>');
// Garis atas untuk header
$pdf->line(10, 785, 578, 785);

// Garis bawah untuk footer
$pdf->line(10, 50, 578, 50);
// Teks kiri bawah
$pdf->addText(30,34,8,'Dicetak tgl:' . tgl_indo(date( 'Y-m-d, H:i:s')));

$pdf->closeObject();

// Tampilkan object di semua halaman
$pdf->addObject($all, 'all');
$cari = stripslashes($_POST['cmd']);
$sql = ("SELECT *,b.id_produk prod FROM detailproduk a, produk b, ukuran c, warna d, Kategori e,admin f
				  WHERE b.id_produk=a.id_produk
				  AND c.id_ukuran=a.id_ukuran
				  AND d.id_warna=a.id_warna
				  AND e.id_kategori=b.id_kategori
				  AND a.id_admin=f.id_admin
				  $cari
				  GROUP BY a.id_detailproduk
				  Order BY tanggal_detailproduk DESC");
  $qry = mysql_query($sql) or die ("Gagal query".mysql_error());
  $jml= mysql_num_rows($qry);
  $i= 1;
  
  while ($data1 =mysql_fetch_array($qry)) {
   $sub=($data1['hargabeli'] * $data1['qty']);
   $total=$total+$sub;
   $jumlah=$jumlah+$data1[qty];
   $data[$i]=array('<b>No</b>'=>$i, 
                  '<b>Id</b>'=>$data1[id_produk],
				  '<b>Tgl Release</b>'=>tgl_indo($data1[tanggal_detailproduk]),
				  '<b>Admin</b>'=>$data1[id_admin],
				  '<b>Kategori</b>'=>$data1[nama_kategori],
				  '<b>Produk</b>'=>$data1[nama_produk],
				  '<b>Size</b>'=>$data1[nama_ukuran],
				  '<b>Warna</b>'=>$data1[nama_warna],
				  '<b>Diskon</b>'=>$data1[diskon_produk]. '%',
				  '<b>Stok</b>'=>$data1[stok_detailproduk]. 'pcs',
				  '<b>Harga</b>'=>'Rp.'.number_format($data1[harga_produk]),
				  );
  $i++;
}
// Penomoran halaman
$pdf->ezStartPageNumbers(320, 15, 8);

$pdf->ezTable($data, '', '', ''); 


$pdf->ezStream();
?>
