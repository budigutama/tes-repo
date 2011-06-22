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
$pdf->addText(230, 815, 16,'<b>LAPORAN RETUR PRODUK</b>');
$pdf->addText(200, 800, 16,'<b>TGL '.substr($_POST['tgl'],8,2).'/'.substr($_POST['tgl'],5,2).'/'.substr($_POST['tgl'],2,2).' S.D. '.substr($_POST['tgl2'],8,2).'/'.substr($_POST['tgl2'],5,2).'/'.substr($_POST['tgl2'],2,2). '</b>');
// Garis atas untuk header
$pdf->line(10, 785, 578, 785);

// Garis bawah untuk footer
$pdf->line(10, 50, 578, 50);
// Teks kiri bawah
$pdf->addText(30,34,8,'Dicetak tgl:' . date( 'd-m-Y, H:i:s'));

$pdf->closeObject();

// Tampilkan object di semua halaman
$pdf->addObject($all, 'all');
$tgl=$_POST['tgl'];
$tgl2=$_POST['tgl2'];
$urut=$_POST['urut'];
  if ($urut=='') $urut="d.id_retur";
  $sql = ("select *,d.harga h, d.diskon as dis, day(p.tgl_retur) as dd from  produk as pr, detail_retur as d, retur as p,member as m WHERE d.id_retur=p.id_retur AND m.id_member=p.id_member AND pr.id_produk=d.idproduk AND (left(tgl_retur,10) BETWEEN '$tgl' and '$tgl2') AND p.status_retur='terima' order by $urut DESC");
  $qry = mysql_query($sql) or die ("Gagal query".mysql_error());
  $jml= mysql_num_rows($qry);
  $i= 1;
  
  while ($data1 =mysql_fetch_array($qry)) {
   $diskon=$data1['h']*($data1['dis']/100);
   $sub=($data1['h'] - $diskon)*$data1[qty];
   $total=$total+$sub;
   $jumlah=$jumlah+$data1[qty];
   $data[$i]=array('<b>No</b>'=>$i, 
                  '<b>Id</b>'=>$data1[id_retur],
				  '<b>Tgl</b>'=>substr($data1[tgl_retur],8,2).'/'.substr($data1[tgl_retur],5,2).'/'.substr($data1[tgl_retur],2,2),
				  '<b>Nama Member</b>'=>'['.$data1[id_member].']'.$data1[nama],
				  '<b>Produk</b>'=>$data1[produk],
				  '<b>Harga</b>'=>'Rp.'.number_format($data1[h]),
				  '<b>Size</b>'=>$data1[size],
				  '<b>qty</b>'=>$data1[qty]. 'pcs',
				  '<b>Diskon</b>'=>$data1[dis].'  %',
				  '<b>total</b>'=>'Rp.'.number_format($sub),
				  );
  $i++;
}
// Penomoran halaman
$pdf->ezStartPageNumbers(320, 15, 8);

$pdf->ezTable($data, '', '', ''); 
$pdf->addText(400, 55, 8,'<b>Total Penjualan      : Rp.'.number_format($total). ',-</b>');
$pdf->addText(400, 65, 8,'<b>Jumlah Produk terjual: '.$jumlah. ' Pcs</b>');


$pdf->ezStream();
?>
