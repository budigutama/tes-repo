   	<div class="center_title_bar">Keranjang Retur</div>
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
		mysql_query("DELETE FROM temp_retur WHERE id_detailproduk = $iddb AND session_id = '".session_id()."'");	
	}
}

if(isset($_POST['kirimretur'])){
	$sid = session_id();
	$masukpesanan = mysql_query("INSERT INTO retur(id_retur,session_id,tgl_retur,id_member) 
						VALUES (NUll,'$sid', now(),'$_SESSION[id_member]')");
	$id=mysql_fetch_array(mysql_query("select * from retur where session_id='$sid' order by id_retur desc limit 1"));
	$temp1 = mysql_query("SELECT * FROM temp_retur WHERE session_id='$sid'");
	while ($temp = mysql_fetch_array($temp1)) 
		{
			$id_produk 			= $temp['id_detailproduk'];
			$qty 				= $temp['jumlah_retur'];
			$komplain			= $temp['komplain'];
			$idp				= $temp['id_pembelian'];
		mysql_query("INSERT INTO detail_retur VALUES('$id[id_retur]','$idp','$id_produk','$qty','$komplain')");
		mysql_query("UPDATE detail_pembelian SET qty_retur=qty_retur+$qty 
					WHERE id_detailproduk=$id_produk AND idpembelian=$idp");
		}
		$hapus = mysql_query("DELETE FROM temp_retur WHERE session_id ='$sid'");
		echo "<script>window.location = '?page=returkirim';</script>";
	}

if(isset($_POST['update'])){
	$item = $_POST['qty'];
	$id = $_POST['iddb'];
	$jumlah = count($id);
	for($i=0;$i<$jumlah;$i++){
	$datastok = mysql_fetch_array(mysql_query("SELECT * FROM temp_retur a, detail_pembelian as b
											   WHERE a.id_pembelian = b.idpembelian
											   AND a.id_detailproduk = $id[$i]
											   AND a.session_id = '".session_id()."'
											   GROUP BY a.id_detailproduk"));
		if($item[$i] > ($datastok['qty']-$datastok['retur_qty'])){
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
			mysql_query("UPDATE temp_retur
						SET jumlah_retur = $item[$i]
						WHERE id_detailproduk = $id[$i]");
		}
	}
}
?>
    <div style="width:100%; text-align:center;clear:both">
            <div class="panah"><a href="?page=retur"><span class="step">Step 1</span><br />Pilih Produk Retur</a></div>  
            <div class="panah"><span class="step">Step 2</span><br />Detail retur</div>  
            <div class="panah"><a href="?page=returcart"><span class="step">Step 3</span><br />Keranjang Retur</a></div>  
            <div class="panah2"><span class="step">Step 4</span><br />Kirim Retur</div> 
     </div> 
<div class="prod_box_big">
	<div class="center_prod_box_big">
            <form method="post" action="">         
<div class="alamat">   
<table border="0" cellpadding="2" cellspacing="0">
  <tr height="30">
    <th width="20" bgcolor="#666" scope="col">No</th>
    <th width="94" bgcolor="#666" scope="col">Nama produk</th>
    <th width="80" bgcolor="#666" scope="col">Gambar</th>
    <th width="90" bgcolor="#666" scope="col">Harga</th>
    <th width="48" bgcolor="#666" scope="col">Jumlah</th>
    <th width="120" bgcolor="#666" scope="col">Komplain</th>
    <th width="30" bgcolor="#666" scope="col">Hapus</th>
  </tr>
  <?php
  $qcart = mysql_query("SELECT *, a.id_detailproduk idp FROM temp_retur a, detailproduk b, produk c, warna d, ukuran e, gambar f, detail_pembelian g
					   WHERE a.id_detailproduk = b.id_detailproduk
					   AND a.id_pembelian=g.idpembelian
					   AND b.id_produk = c.id_produk
					   AND b.id_produk = f.id_produk
					   AND b.id_warna = d.id_warna
					   AND b.id_ukuran = e.id_ukuran
					   AND a.session_id = '".session_id()."'
					   GROUP BY a.id_detailproduk") or die(mysql_error());
  $no = 0;
  $sub = 0;
  $total = 0;
  while($dcart = mysql_fetch_array($qcart)){
  $sub = ($dcart['jumlah_retur'] *  $dcart['hargabeli']);
  $total = $total + $sub;
  $no++;
  if($no%2)
  	echo "<tr style='background-color:#cccccc'>";
  else
  	echo "<tr style='background-color:#eeeeee'>";
  ?>
    <td><?php echo $no; ?></td>
    <td>
		<?php echo $dcart['nama_produk'].$dcart['idp']; ?><br />
		<strong><em>Warna :</em></strong>
		<?php echo $dcart['nama_warna']; ?><br />
		<strong><em>Ukuran :</em></strong>
		<?php echo $dcart['nama_ukuran']; ?>
    </td>
    <td><img src="images/product/<?php echo $dcart['nama_gambar']; ?>" height="80" width="80" /></td>
    <td>Rp. <?php echo number_format($dcart['hargabeli'],"2",".",","); ?></td>
    <td align="center">
    	<input type="hidden" name="iddb[]" value="<?php echo $dcart['idp']; ?>" />
    	<input type="text" name="qty[]" value="<?php echo $dcart['jumlah_retur']; ?>" size="1" maxlength="5" style="border-style:solid; text-align:center;" onblur="this.form.submit('update');" />
    </td>
    <td>
    <textarea name="komplain" cols="16" rows="2"><?php echo $dcart['komplain'];?></textarea></td>
    <td align="center"><a href="?page=returcart&act=del&iddb=<?php echo $dcart['idp']; ?>" onclick="if(!confirm('Yakin dihapus dari Keranjang ?')) return false;"><img src="images/trash.png" border="0" /></a></td>
  </tr>
  <?php
  }
  ?>
</table>
</div>
	<div style="padding-top:8px; text-align:right;">
    	<input type="submit" name="lanjut" value="  Retur Produk Lain  " class="buton" onclick="window.location = '?page=retur'; return false;" alt="Lanjut Pilih Produk Retur" title="Lanjut Aktifitas Belanja" />&nbsp;
    	<input type="hidden" name="update" class="buttonbtn" value="UBAH" alt="Ubah Jumlah produk di Keranjang Belanja" title="Ubah Jumlah produk di Keranjang Belanja" />&nbsp;
    	<input type="submit" name="kirimretur" value="Next" class="buton" alt="Lanjut ke Kirim Retur" title="Lanjut ke Kirim retur" />
    </div>
</form>
	</div>
</div>