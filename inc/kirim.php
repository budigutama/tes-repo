<div class="center_title_bar">Jenis Pengiriman</div>
<div class="prod_box_big">
	<div class="center_prod_box_big">
<?php
if(!isset($_SESSION['id_member'])){
	echo "<script>window.location = '?page=register';</script>"; 
	}
$qinvo =mysql_query("SELECT * FROM pembelian WHERE session_id ='".session_id()."' AND id_member = $_SESSION[id_member] ORDER BY id_pembelian DESC LIMIT 1");
$dinvo =mysql_fetch_array($qinvo);
$iddb= $dinvo['id_pembelian'];
$idkota=$dinvo['kirim_kota'];
$email=$_SESSION['email_member'];
if(isset($_POST['next'])){
	 if($_POST['idjenispengiriman'] == NULL){
        echo "<h3> Pilih Jenis pengiriman terlebih dahulu !!</h3>";
        }
	else {
	if($_POST['idjenispengiriman'] != 'cod'){
	echo "$_POST[idjenispengiriman]";
	mysql_query("UPDATE pembelian SET pembayaran = '' WHERE id_pembelian='$iddb'");
	$qongkos = mysql_query("SELECT * FROM ongkir WHERE id_kota = $idkota AND id_jenispengiriman=$_POST[idjenispengiriman]") or die(mysql_error());
	}
	else {
	mysql_query("UPDATE pembelian SET pembayaran = 'cod' WHERE id_pembelian='$iddb'");
	$qongkos = mysql_query("SELECT * FROM ongkir a, jenispengiriman b 
						   WHERE a.id_jenispengiriman=b.id_jenispengiriman
						   AND a.id_kota = $idkota 
						   AND b.nama_jenispengiriman = 'cod'") or die(mysql_error());		
	}
	$dongkos = mysql_fetch_array($qongkos);
	mysql_query("UPDATE pembelian	SET 
				kirim_id ='$dongkos[id_jenispengiriman]',
				kirim_ongkos = $dongkos[harga_ongkir]
				WHERE id_pembelian = '$iddb'");
	emailshipping($iddb,$email);
	$_SESSION['confirm'] = true;
	echo "<script>window.location = '?page=confirm';</script>";
} }
?>
            <a href="?page=cart&idpr=<?php echo $iddb; ?>">
            <div class="panah"><span class="step">Step 1</span><br />Keranjang Belanja</div></a>  
            <a href="?page=checkout"><div class="panah"><span class="step">Step 2</span><br />Alamat Kirim</div></a>  
            <a href="?page=kirim"><div class="panah"><span class="step">Step 3</span><br />Jenis Pengiriman</div></a>  
            <div class="panah2"><span class="step">Step 4</span><br />Pembayaran</div>
         <div class="alamat">   
            <table width="100%">
            <tr align="center"> 
            	<td colspan="2" style="font-size:14px; font-weight:bold">ALAMAT PENGIRIMAN PAKET</td></tr>
            <tr>
            	<td width="160px">Nama Penerima Paket</td>
            	<td> :<?php echo $dinvo['kirim_nama'];?></td>
            </tr>
            <tr>
            	<td width="160px">Alamat </td>
            	<td> :<?php echo $dinvo['kirim_alamat'];?></td>
            </tr>
            <tr>
            	<td width="160px"></td>
            	<td> <?php
					$kota=mysql_fetch_array(mysql_query("SELECT * FROM kota a, provinsi b
														WHERE a.id_provinsi=b.id_provinsi
														AND a.id_kota=$dinvo[kirim_kota]"));
					echo "$kota[nama_kota] - $kota[nama_provinsi]";?></td>
            </tr>
            <tr>
            	<td width="160px">Kode Pos</td>
            	<td> :<?php echo $dinvo['kirim_kdpos'];?></td>
            </tr>
            <tr>
            	<td width="160px">No. telepon</td>
            	<td> :<?php echo $dinvo['kirim_telp'];?></td>
            </tr>
            </table>  
         </div>
            <div>
<div class="alamat">   
<?php
		$qjasapengiriman = mysql_query("SELECT * FROM jasapengiriman c,jenispengiriman a, ongkir b
										WHERE a.id_jenispengiriman=b.id_jenispengiriman
										AND b.id_kota=$idkota
										AND a.id_jasapengiriman=c.id_jasapengiriman
										GROUP BY c.id_jasapengiriman");
		if (mysql_num_rows($qjasapengiriman)!=0){
?>
            <h2>Jasa Pengiriman Untuk Ke <?php echo $kota[nama_kota]; ?></h2>
            	<form method="post" action="">
				<table border="0" cellpadding="0" cellspacing="0">
				<tbody>
                <?php
						while($djasapengiriman = mysql_fetch_array($qjasapengiriman)){
							$idjp = $djasapengiriman['id_jasapengiriman'];
					?>
					<tr>
				<th colspan="2" style="background:#00CCFF; font-size:14px"> 
				.: <?php echo $djasapengiriman['nama_jasapengiriman']; ?> :.</th>
                    </tr>
					<tr>
						<td colspan="2">
					<?php
						$qjenispengiriman = mysql_query("SELECT * FROM jenispengiriman a, ongkir b
														WHERE a.id_jenispengiriman=b.id_jenispengiriman
														AND b.id_kota=$idkota
														AND a.id_jasapengiriman = $idjp
														GROUP BY a.id_jenispengiriman") or die(mysql_error());
						$cjenispengiriman = 0;
						while($djenispengiriman = mysql_fetch_array($qjenispengiriman)){
					?>
						  <input name="idjenispengiriman" class="shippingradio" id="shippingradio_<?php echo $cjenispengiriman; ?>" value="<?php echo $djenispengiriman['id_jenispengiriman']; ?>" type="radio" >
						<span style="font-size:12px;">
						<?php echo "$djenispengiriman[nama_jenispengiriman]  -  
						  Harga : <b>Rp. $djenispengiriman[harga_ongkir]</b>"; ?></span>
                       <br /><br />
						<?php
						$cjenispengiriman++;
						}
						?>
                        </td>
					</tr><?php
					} ?>
     			  <tr>
					<td colspan="2">&nbsp;</td>
      			  </tr>
					<tr>
						<td colspan="2">
							<small>-.Elitez Distro tidak bertanggung jawab jika waktu pengiriman melalui JNE lebih lama dari estimasinya.</small></td>
    			</tr>
                <tr>
						    <td colspan="2"><small> -.Untuk COD(Cash On Delivery) hanya untuk daerah Bandung dan sekitarnya.</small></td>
    			</tr>
				</tbody>
			</table>
<?php
 }
 else { ?>
        <h3>Mohon maaf Jasa Pengiriman Ke <?php echo $kota[nama_kota]; ?> Untuk saat ini belum tersedia, anda bisa memilih kota terdekat untuk melakukan pengirinan.. </h3>
        
		<a href="?page=checkout"><div align="center" class="buton">Kembali ke Alamat Kirim</div></a>  
	<?php } ?></div>
            <hr /><br>
            <input type="submit" name="next" value="Next" class="buton"/>
            </form>
        </div>
    </div>
</div>
