<?php
include("../../connect.php");
include ('class.ezpdf.php');
$pdf = new Cezpdf();
// Set margin dan font
$pdf->ezSetCmMargins(3, 3, 3, 3);
$pdf->selectFont('fonts/Courier.afm');

$all = $pdf->openObject();

// Tampilkan logo
$pdf->setStrokeColor(0, 0, 0, 1);
$pdf->addJpegFromFile('../images/logo.jpg',20,800,130);

// Teks di tengah atas untuk judul header
$pdf->addText(200, 810, 16,'<b>LAPORAN DATA PRODUK </b>');
// Garis atas untuk header
$pdf->line(10, 785, 578, 785);

// Garis bawah untuk footer
$pdf->line(10, 50, 578, 50);
// Teks kiri bawah
$pdf->addText(30,34,8,'Dicetak tgl:' . date( 'd-m-Y, H:i:s'));

$pdf->closeObject();

// Tampilkan object di semua halaman
$pdf->addObject($all, 'all');
$urut=$_POST['urut'];
  if ($urut=='') $urut="p.id_produk";
  $sql = ("select * From produk as p, merek as m, kategori as k, detail_produk as dp where dp.id_produk=p.id_produk AND m.id_merek=p.id_merek AND k.id_kategori=p.id_kategori order by $urut");
  $qry = mysql_query($sql) or die ("Gagal query".mysql_error());
  $jml= mysql_num_rows($qry);
  $i= 1;
  while ($data1 =mysql_fetch_array($qry)) {
   $data[$i]=array('<b>No</b>'=>$i, 
                  '<b>Id_Produk</b>'=>$data1[id_produk],
				  '<b>Merek</b>'=>$data1[merek],
				  '<b>Kategori</b>'=>$data1[kategori],
				  '<b>Produk</b>'=>$data1[produk],
				  '<b>Harga</b>'=>'Rp.'.number_format($data1[harga]),
				  '<b>Diskon</b>'=>$data1[diskon].' %',
				  '<b>Size</b>'=>$data1[size],
				  '<b>Stok</b>'=>$data1[stok],
				  );
  $i++;
}
// Penomoran halaman
$pdf->ezStartPageNumbers(320, 15, 8);
$pdf->ezTable($data, '', '', '');

$ambildatamerek=mysql_query('select * from merek');
  while($datamerek=mysql_fetch_array($ambildatamerek))
  {
	$merek=$datamerek['id_merek'];
	$hasil1=mysql_query("SELECT count(*) as total FROM produk as p , detail_produk as dp where dp.id_produk=p.id_produk AND id_merek=$merek");
	$merek1=mysql_fetch_array($hasil1);
	$l=$l+10;
$pdf->addText(73, 45+$l, 8,'<b>* Merek ' .$datamerek['merek']. '( '.$merek1['total']. ' ) produk</b>');
 }
$pdf->addText(13, 45+$l, 8,'<b>Total ( '.$jml.' )</b>');

$data=mysql_fetch_array(mysql_query("Select MAX(harga) as max, MIN(harga) as min from produk"));
$pdf->addText(233, 75, 8,'<b>Produk Temahal	 : Rp.'.number_format($data['max']).',-</b>');
$pdf->addText(233, 65, 8,'<b>Produk Termurah : Rp.'.number_format($data['min']).',-</b>');

$pdf->ezStream();
?>
