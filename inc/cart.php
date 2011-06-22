<?php
$idpr=$_GET['idpr'];
if(isset($idpr)){
	$det = mysql_query("SELECT * FROM detail_pembelian WHERE idpembelian='$idpr'");
	while ($dp = mysql_fetch_array($det)) 
		{
			$id_produk 			= $dp['id_detailproduk'];
			$qty 				= $dp['qty'];
			$harga				= $dp['hargabeli'];
			$berat				= $dp['berat'];
		mysql_query("INSERT INTO temp_pemesanan VALUES(null,'$id_produk','".session_id()."','$qty','$berat','$harga',now())");
		mysql_query("UPDATE detailproduk SET stok_detailproduk=stok_detailproduk+$qty 
					WHERE id_detailproduk=$id_produk");
		}
		$hapus = mysql_query("DELETE FROM detail_pembelian WHERE idpembelian ='$idpr'");
		$hapusp = mysql_query("DELETE FROM pembelian WHERE id_pembelian ='$idpr'");
}

if(isset($_GET['act'])){
	if($_GET['act'] == 'del'){
		$iddb = addslashes($_GET['iddb']);
		mysql_query("DELETE FROM temp_pemesanan WHERE id_detailproduk = $iddb AND session_id = '".session_id()."'");	
	}
}

if(isset($_POST['checkout'])){
	if(isset($_SESSION['id_member'])){
	$sid = session_id();
	$masukpesanan = mysql_query("INSERT INTO pembelian(session_id,tgl_beli,status,id_member,pembayaran) 
						VALUES ('$sid', now(),'pesan', '$_SESSION[id_member]','')");
	$id=mysql_fetch_array(mysql_query("select * from pembelian where session_id='$sid' order by id_pembelian desc limit 1"));
	$temp1 = mysql_query("SELECT * FROM temp_pemesanan WHERE session_id='$sid'");
	while ($temp = mysql_fetch_array($temp1)) 
		{
			$id_produk 			= $temp['id_detailproduk'];
			$qty 				= $temp['qty'];
			$harga				= $temp['temp_hargadiskon'];
			$berat				= $temp['berat'];
		mysql_query("INSERT INTO detail_pembelian VALUES('$id[id_pembelian]','$harga','$id_produk','$qty','$berat','')");
		mysql_query("UPDATE detailproduk SET stok_detailproduk=stok_detailproduk-$qty 
					WHERE id_detailproduk=$id_produk");
		}
		$hapus = mysql_query("DELETE FROM temp_pemesanan WHERE session_id ='$sid'");
		echo "<script>window.location = '?page=checkout';</script>";
	}
	else{
		echo "<script>window.location = '?page=register';</script>";
	}
}

if(isset($_POST['update'])){
	$item = $_POST['qty'];
	$id = $_POST['iddb'];
	$jumlah = count($id);
	for($i=0;$i<$jumlah;$i++){
	$datastok = mysql_fetch_array(mysql_query("SELECT *
											   FROM temp_pemesanan a, detailproduk as b
											   WHERE a.id_detailproduk = b.id_detailproduk
											   AND a.id_detailproduk = $id[$i]
											   AND a.session_id = '".session_id()."'"));
		if($item[$i] > $datastok['stok_detailproduk']){
			?>
			<h3>Stok Tidak Mencukupi</h3>
			<?php
		}
		elseif($item[$i] <= 0){
			?>
			<h3>Jumlah Tidak Boleh Kosong</h3>
			<?php
		}
		else{
			mysql_query("UPDATE temp_pemesanan
						SET qty = $item[$i]
						WHERE id_detailproduk = $id[$i]");
		}
	}
}
?>
   	<div class="center_title_bar">Keranjang Belanja</div>
    	<div class="prod_box_big">
            <div class="center_prod_box_big"> 
            <div class="panah"><span class="step">Step 1</span><br />Keranjang Belanja</div>  
            <div class="panah2"><span class="step">Step 2</span><br />Alamat Kirim</div>  
            <div class="panah2"><span class="step">Step 3</span><br />Jenis Pengiriman</div>  
            <div class="panah2"><span class="step">Step 4</span><br />Pembayaran</div>  
            <form method="post" action="">         
<div class="alamat">   
<table width="100%" border="0" cellpadding="2" cellspacing="0">
  <tr height="30">
    <th width="20" bgcolor="#666" scope="col">No</th>
    <th width="94" bgcolor="#666" scope="col">Nama produk</th>
    <th width="80" bgcolor="#666" scope="col">Gambar</th>
    <th width="102" bgcolor="#666" scope="col">Harga Satuan</th>
    <th width="48" bgcolor="#666" scope="col">Jumlah</th>
    <th width="73" bgcolor="#666" scope="col">Sub Total</th>
    <th width="48" bgcolor="#666" scope="col">Hapus</th>
  </tr>
  <?php
  $qcart = mysql_query("SELECT * FROM temp_pemesanan as a, detailproduk as b, produk as c, warna as d, ukuran as e, gambar f
					   WHERE a.id_detailproduk = b.id_detailproduk
					   AND b.id_produk = c.id_produk
					   AND b.id_produk = f.id_produk
					   AND b.id_warna = d.id_warna
					   AND b.id_ukuran = e.id_ukuran
					   AND a.session_id = '".session_id()."'
					   GROUP BY b.id_detailproduk") or die(mysql_error());
  $no = 0;
  $sub = 0;
  $total = 0;
  while($dcart = mysql_fetch_array($qcart)){
  $sub = ($dcart['qty'] *  $dcart['temp_hargadiskon']);
  $total = $total + $sub;
  $no++;
  if($no%2)
  	echo "<tr style='background-color:#cccccc'>";
  else
  	echo "<tr style='background-color:#eeeeee'>";
  ?>
    <td><?php echo $no; ?></td>
    <td>
		<?php echo $dcart['nama_produk']; ?><br />
		<strong><em>Warna :</em></strong>
		<?php echo $dcart['nama_warna']; ?><br />
		<strong><em>Ukuran :</em></strong>
		<?php echo $dcart['nama_ukuran']; ?>
    </td>
    <td><img src="images/product/<?php echo $dcart['nama_gambar']; ?>" height="80" width="80" /></td>
    <td><?php if($dcart['diskon_produk']>0){
	    ?>
        <span style="text-decoration:line-through; font-weight:bold;">
    	Rp. <?php echo number_format($dcart['harga_produk'],"2",".",","); ?></span><br />
    	Diskon <?php echo "$dcart[diskon_produk] % <br /> ";  } ?>
        <span style="font-weight:bold; color:#F00;">
        Rp. <?php echo number_format($dcart['temp_hargadiskon'],"2",".",","); ?></span></td>
    <td align="center">
    	<input type="hidden" name="iddb[]" value="<?php echo $dcart['id_detailproduk']; ?>" />
    	<input type="text" name="qty[]" value="<?php echo $dcart['qty']; ?>" size="1" maxlength="5" style="border-style:solid; text-align:center;" onblur="this.form.submit('update');" />
    </td>
     <td>Rp. <?php echo number_format($sub,"2",".",","); ?></td>
   <td align="center"><a href="?page=cart&act=del&iddb=<?php echo $dcart['id_detailproduk']; ?>" onclick="if(!confirm('Yakin dihapus dari Keranjang ?')) return false;"><img src="images/trash.png" border="0" /></a></td>
  </tr>
  <?php
  }
  ?>
   <tr>
    <td colspan="5" align="left"><strong><font color="#FF0000">Total</font></strong></td>
    <td><strong><font color="#FF0000">Rp. <?php echo number_format($total,"2",".",","); ?></font></strong></td>
    <td>&nbsp;</td>
  </tr>
</table>
</div>
	<div style="padding-top:8px; text-align:right;">
    	<input type="submit" name="lanjut" value="Belanja Lagi" class="buton" onclick="window.location = '?page=home'; return false;" alt="Lanjut Aktifitas Belanja" title="Lanjut Aktifitas Belanja" />&nbsp;
    	<input type="hidden" name="update" class="buttonbtn" value="UBAH" alt="Ubah Jumlah produk di Keranjang Belanja" title="Ubah Jumlah produk di Keranjang Belanja" />&nbsp;
    	<input type="submit" name="checkout" value="Next" class="buton" alt="Lanjut ke Pembayaran" title="Lanjut ke Pembayaran" />
    </div>
</form>
	</div>
</div>