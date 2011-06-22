<?php
if(isset($_POST['diterima'])){
	$id_detailpesanan = addslashes($_POST['id_detailpesanan']);
	mysql_query("UPDATE retur SET status_retur = 'diterima' WHERE id_detailpesanan = $id_detailpesanan");
	?>
        <form name="redirect">
 			<input type="hidden" name="redirect2">
  		</form>
		<script>
		loading('Data Retur Diterima', 'Loading');  
		var targetURL="?page=retur&act=edit&id_detailpesanan=<?php echo $id_detailpesanan; ?>"
		var countdownfrom=3
		var currentsecond=document.redirect.redirect2.value=countdownfrom+1
		function countredirect(){
		  if (currentsecond!=1){
			currentsecond-=1
			document.redirect.redirect2.value=currentsecond
		  }
		  else{
			window.location=targetURL
			return
		  }
		  setTimeout("countredirect()",1000)
		}
		countredirect()
        </script>
	<?php
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Pengolahan Data Retur</title>
</head>

<body>
<h2>Pengolahan Data Retur</h2> 
                  
<?php
if(isset($_GET['act'])){
	if($_GET['act'] == 'edit'){
	$id_detailpesanan=addslashes($_GET['id_detailpesanan']);
 	$qcek = mysql_query("SELECT * FROM detailpesanan WHERE id_detailpesanan = '$id_detailpesanan'");
	$dcek = mysql_fetch_array($qcek);
	$sqlcart = "SELECT *
				FROM detailpesanan as a, shipping as b, kota as c, provinsi as d, retur as e
				WHERE a.id_shipping = b.id_shipping
				AND b.id_kota = c.id_kota
				AND c.id_provinsi = d.id_provinsi
				AND a.id_detailpesanan = e.id_detailpesanan
				AND a.id_detailpesanan = $id_detailpesanan
				GROUP BY a.id_detailpesanan";
  $ambildata=mysql_query($sqlcart) or die(mysql_error());
  $datakirim=mysql_fetch_array($ambildata);
?>
<form action="" method="post">
	<input type="hidden" name="id_detailpesanan" value="<?php echo $id_detailpesanan;?>" />
	<table>
		<tr>
			<td>No Nota <span id="sprytextfield1"></td>
			<td>: <?php echo $id_detailpesanan;?></td>
		<tr>
        <?php
		if($datakirim['no_resi_pemesan'] != ''){
		?>
		<tr>
			<td>No Resi <span id="sprytextfield1"></td>
			<td>: <strong><?php echo $datakirim['no_resi_pemesan'];?></strong></td>
		<tr>
        <?php
		}
		?>
        <tr>
			<td>Nama <span id="sprytextfield1"></td>
			<td>: <?php echo $datakirim['nama_pemesan'];?></td>
		<tr>
		<tr>
          <td>Alamat <span id="sprytextfield1"></td>
		  <td>: <?php echo $datakirim['alamat_pemesan'];?></td>
	  </tr>
		<tr>
          <td>Kota <span id="sprytextfield1"></td>
		  <td>: <?php echo $datakirim['nama_kota'];?></td>
	  </tr>
		<tr>
          <td>No Telp <span id="sprytextfield1"></td>
		  <td>: <?php echo $datakirim['no_telp_pemesan'];?></td>
	  </tr>
		<tr>
          <td>Email <span id="sprytextfield1"></td>
		  <td>: <?php echo $datakirim['email_pemesan'];?></td>
	  </tr>
		<tr>
          <td>Status Retur</td>
		  <td>: 
		  <?php echo $datakirim['status_retur'];?>
		  </td>
	  </tr>
	</table>
    <?php
	if($datakirim['status_retur'] == 'dikonfirmasi'){
	?>
	<input type="submit" name="diterima" value="Diterima" />
    <?php
	}
	?>
</form><br />

<table width="592" id="rounded-corner">
  <thead>
  <tr>
        <th width="51" class="rounded-company" scope="col">No.</th>
        <th width="125" class="rounded" scope="col">Nama Barang</th>
        <th width="90" class="rounded" scope="col">Harga</th>
        <th width="40" class="rounded" scope="col">Jumlah</th>
        <th width="230" class="rounded-q4" scope="col">Keterangan</th>
  </tr>
  </thead><?php
  
  $sqlretur = "SELECT * FROM retur as a, detailbarang as b, barang as c, warna as d, ukuran as e, pesanan as f
  				WHERE a.id_detailbarang = b.id_detailbarang
				AND b.id_barang = c.id_barang
				AND b.id_warna = d.id_warna
				AND b.id_ukuran = e.id_ukuran
				AND a.id_detailpesanan = f.id_detailpesanan
				AND b.id_detailbarang = f.id_detailbarang
				AND a.id_detailpesanan = $id_detailpesanan
				GROUP BY b.id_detailbarang";
  
$no=0;
$ambildata=mysql_query($sqlretur) or die(mysql_error());
while($data=mysql_fetch_array($ambildata)){
  $no++;
?>
  <tr valign="top">
    <td align="center"><?php echo $no;?></td>
    <td>
    	<strong><?php echo $data['nama_barang'];?></strong><br />
		&nbsp;&nbsp;<strong>Warna</strong><br />
        &nbsp;&nbsp;&nbsp;&nbsp;<?php echo $data['nama_warna'];?><br />
        &nbsp;&nbsp;<strong>Ukuran</strong><br />
        &nbsp;&nbsp;&nbsp;&nbsp;<?php echo $data['nama_ukuran'];?>
    </td>
    <td width="22" align="right"><?php echo "Rp ".number_format($data['harga_temp'],"2",",",".");?></td>
    <td width="22" align="center"><?php echo $data['stok_retur'];?></td>
    <td width="22" align="left"><?php echo $data['keterangan_retur']; ?></td>
	</tr>
<?php
}
?>
</table><br />
<?php
		}
		elseif($_GET['act'] == 'del'){
			mysql_query("DELETE FROM barang WHERE id_barang = '$_GET[idb]'");
			mysql_query("DELETE FROM detailbarang WHERE id_barang = '$_GET[idb]'");
			mysql_query("DELETE FROM gambar WHERE id_barang = '$_GET[idb]'");
		}
}
else
{
	$batas   = 5;
	if(isset($_GET['halaman']))
		$halaman = $_GET['halaman'];
		
	if(empty($halaman)){
		$posisi  = 0;
		$halaman = 1;
	}
	else{
		$posisi = ($halaman-1) * $batas;
	}
	?>
	  
	<table width="592" id="rounded-corner">
		<thead>
        	<tr>
				<th scope="col" class="rounded-company">No</th>
				<th scope="col" class="rounded">No Detailpesanan </th>
				<th scope="col" class="rounded">Nama Member</th>
				<th scope="col" class="rounded">Tanggal pesanan </th>
				<th scope="col" class="rounded">Jenis pembayaran </th>
				<th scope="col" class="rounded">Status Pemesanan </th>
				<th scope="col" class="rounded">Status Pengiriman </th>
				<th scope="col" class="rounded">Ubah</th>
				<th scope="col" class="rounded-q4">Hapus</th>
        	</tr>
		</thead>
		<tfoot>
		</tfoot>
		<tbody>
			<?php
			$no = 0;
			$qdatapesanan = mysql_query("SELECT * FROM retur as a, detailpesanan as b, pesanan as c
										WHERE a.id_detailpesanan = b.id_detailpesanan
										AND b.id_detailpesanan = c.id_detailpesanan
										GROUP BY a.id_detailpesanan");
			$kolom=1;
			$i=0;
			$no = $posisi+1;
			while($ddatapesanan = mysql_fetch_array($qdatapesanan)){
				if ($i >= $kolom){
					echo "<tr class='row$ddatapesanan[id_detailpesanan]'>";
				}
			$i++;
			?>
					<td><?php echo $no; ?></td>
					<td><?php echo $ddatapesanan['id_detailpesanan']; ?></td>
					<td><?php echo $ddatapesanan['nama_pemesan']; ?></td>
					<td><?php echo $ddatapesanan['tanggal_detailpesanan']; ?></td>
					<td><?php echo $ddatapesanan['jenis_pembayaran']; ?></td>
					<td><?php echo $ddatapesanan['status_pemesanan']; ?></td>
					<td><?php echo $ddatapesanan['status_pengiriman']; ?></td>
					<td><a href="?page=retur&act=edit&id_detailpesanan=<?php echo $ddatapesanan['id_detailpesanan']; ?>"><img src="images/user_edit.png" alt="" title="" border="0" /></a></td>
					<td><a href="" id="<?php echo $ddatapesanan['id_detailpesanan']; ?>" class="ask"><img src="images/trash.png" alt="" title="" border="0" /></a></td>
				</tr>
			<?php
			$no++;
				if($i >= $kolom){
					echo "</tr>";	
				}
			}
			?>
		</tbody>
	</table>
	
	<div class="pagination">
	<?php
	$tampil2 = mysql_query("SELECT * FROM retur as a, detailpesanan as b, pesanan as c
							WHERE a.id_detailpesanan = b.id_detailpesanan
							AND b.id_detailpesanan = c.id_detailpesanan
							GROUP BY a.id_detailpesanan");
	$jmldata = mysql_num_rows($tampil2);
	$jmlhal  = ceil($jmldata/$batas);
			
	echo "<div class=paging>";
	// Link ke halaman sebelumnya (previous)
	if($halaman > 1){
		$prev=$halaman-1;
		echo "<span class=prevnext><a href='$_SERVER[PHP_SELF]?page=datapesanan&halaman=$prev'>Prev</a></span> ";
	}
	else{ 
		echo "<span class=disabled>Prev</span> ";
	}
		
	// Tampilkan link halaman 1,2,3 ...
	$angka=($halaman > 3 ? " ... " : " ");
	for($i=$halaman-2;$i<$halaman;$i++)
	{
	if ($i < 1) 
		  continue;
	  $angka .= "<a href='$_SERVER[PHP_SELF]?page=datapesanan&halaman=$i'>$i</a> ";
	}
		
	$angka .= "<span class=current>$halaman</span> ";
	for($i=$halaman+1;$i<($halaman+3);$i++)
	{
	 if ($i > $jmlhal) 
		  break;
  	$angka .= "<a href='$_SERVER[PHP_SELF]?page=datapesanan&halaman=$i'>$i</a> ";
	}
	
	$angka .= ($halaman+2<$jmlhal ? " ...  
  	<a href='$_SERVER[PHP_SELF]?page=datapesanan&halaman=$jmlhal'>$jmlhal</a> " : " ");
	
	echo "$angka ";
	
	// Link kehalaman berikutnya (Next)
	if($halaman < $jmlhal){
		$next=$halaman+1;
		echo "<span class=prevnext><a href='$_SERVER[PHP_SELF]?page=datapesanan&halaman=$next'>Next</a></span>";
	}
	else{ 
		echo "<span class=disabled>Next</span>";
	}
		
	echo "</div>";
} //end of else or !isset($_GET['act'])
	?>
     
     <h2>&nbsp;</h2>
</body>
</html>