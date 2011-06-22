<div class="center_title_bar">Informasi Pengiriman</div>
<div class="prod_box_big">
	<div class="center_prod_box_big">
<?php
if(!isset($_SESSION['id_member'])){
	echo "<script>window.location = '?page=register';</script>"; 
	}
$qinvo =mysql_query("SELECT * FROM pembelian WHERE session_id ='".session_id()."'ORDER BY id_pembelian DESC LIMIT 1");
$dinvo =mysql_fetch_array($qinvo);
$iddb= $dinvo['id_pembelian'];
if(isset($_POST['submit1'])){
	mysql_query("UPDATE pembelian	SET 
				kirim_nama = '$_POST[nama1]',
				kirim_alamat = '$_POST[alamat1]',
				kirim_telp = '$_POST[telp1]',
				kirim_kota = '$_POST[idkota1]',
				kirim_kdpos =  '$_POST[kodepos1]'
				WHERE id_pembelian = '$iddb'");
	echo "<script>window.location = '?page=kirim';</script>";
}
elseif(isset($_POST['submit2'])){
	mysql_query("UPDATE pembelian	SET 
				kirim_nama = '$_POST[nama2]',
				kirim_alamat = '$_POST[alamat2]',
				kirim_telp = '$_POST[telp2]',
				kirim_kota = '$_POST[idkota2]',
				kirim_kdpos =  '$_POST[kodepos2]'
				WHERE id_pembelian = '$iddb' ");
	echo "<script>window.location = '?page=kirim';</script>";
}
?>
            <a href="?page=cart&idpr=<?php echo $iddb; ?>">
			<div class="panah"><span class="step">Step 1</span><br />Keranjang Belanja</div></a> 
            <a href="?page=checkout"><div class="panah"><span class="step">Step 2</span><br />Alamat Kirim</div></a>  
            <div class="panah2"><span class="step">Step 3</span><br />Jenis Pengiriman</div>  
            <div class="panah2"><span class="step">Step 4</span><br />Pembayaran</div>  
            <div class="head">
                <h2> &nbsp;Pesanan :</h2>
            </div>
            <div>
<div class="alamat">   
   <table width="100%" cellspacing="0" cellpadding="2" align="center">
      <thead>
      <tr height="30px" bgcolor="#999999">
        <th>No</th>
        <th>Nama produk</th>
        <th>Harga</th>
        <th>Berat</th>
        <th>Qty</th>
        <th>Subtotal</th>
      </tr>
      </thead>
      <tbody>
 <?php
	$id=mysql_fetch_array(mysql_query("SELECT id_pembelian id FROM pembelian 
						WHERE session_id='".session_id()."' ORDER BY id_pembelian DESC limit 1"));
    $qcart = mysql_query("SELECT * FROM detail_pembelian as a, detailproduk as b, produk as c
                          WHERE a.id_detailproduk = b.id_detailproduk
                          AND b.id_produk = c.id_produk
                          AND a.idpembelian = '$id[id]'") or die(mysql_error());
    $i = 0;
    $subtotal = 0;
         while($dcart = mysql_fetch_array($qcart)){
	$i++;
		if($i%2)
			echo "<tr style='background-color:#cccccc'>";
  		else
  			echo "<tr style='background-color:#eeeeee'>"; ?>
    <td><?php echo $i; ?></td>
    <td>
    <b><?php echo $dcart['nama_produk']; ?></b>
    <?php
      if($dcart['id_warna'] != NULL){
        $qwarna = mysql_query("SELECT * FROM warna WHERE id_warna = $dcart[id_warna]");
        $dwarna = mysql_fetch_array($qwarna);?>
        <dt><strong><em>Warna :</em></strong><?php echo $dwarna['nama_warna']; ?></dt>
        <?php  } 
         if($dcart['id_ukuran'] != NULL){
        $qukuran = mysql_query("SELECT * FROM ukuran WHERE id_ukuran = $dcart[id_ukuran]");
        $dukuran = mysql_fetch_array($qukuran); ?>
        <dt><strong><em>Ukuran :</em></strong><?php echo $dukuran['nama_ukuran']; ?></dt>
        <?php  }  ?>
        </td>
        <td><?php if($dcart['diskon_produk']>0){
	    ?>
        <span style="text-decoration:line-through; font-weight:bold;">
    	Rp. <?php echo number_format($dcart['harga_produk'],"2",".",","); ?></span><br />
    	Diskon <?php echo "$dcart[diskon_produk] % <br /> ";  } ?>
        <span style="font-weight:bold; color:#F00;">
        Rp. <?php echo number_format($dcart['hargabeli'],"2",".",","); ?></span></td>
                                <td><?php echo $dcart['berat']; ?> Kg</td>
                                <td><?php echo $dcart['qty']; ?></td>
                                <td>Rp <?php echo number_format($dcart['hargabeli']*$dcart['qty'],"2",".",","); ?></td>
                            </tr>
                         <?php
                         $subtotal = $subtotal + ($dcart['hargabeli']*$dcart['qty']);
                         }
                         ?>
                        </tbody>
                        <tfoot>
                          <tr>
                           <td colspan="5"><strong>Total</strong></td>
                           <td><strong><span class="price">Rp <?php echo number_format($subtotal,"2",".",","); ?></span></strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        <h2>Informasi Alamat Pengiriman :</h2>
          <form method="post" action="">
            <script type="text/javascript">
                $(function() {
                    $('#container-1').tabs({ fxFade: true, fxSpeed: 'fast' });
                });
            </script>
				<div id="container-1">
					<ul>
						<li><a href="#1"><span>Sendiri</span></a></li>
						<li><a href="#2"><span>Alamat Lain</span></a></li>
					</ul>
                    	<?php
						$qmember = mysql_query("SELECT * FROM member as a, provinsi as b, kota as c
												WHERE a.id_kota = c.id_kota
												AND b.id_provinsi = c.id_provinsi
												AND a.id_member = $_SESSION[id_member]");
						$dmember = mysql_fetch_array($qmember);
						?>
				
                <div id="1" style="border-radius:10px; -moz-border-radius: 5px; -webkit-border-radius: 10px; border:1px #999999 solid;border-collapse:collapse; background:#CCCCCC">
						<table class="affiliateTable" width="100%" border="0" cellpadding="0" cellspacing="0">
							<tbody>
								<tr>
									<td><font color="black"><strong>Nama Lengkap</strong></font></td>
									<td>
										<input name="nama1" id="namaLengkap" value="<?php echo $dmember['nama_member']; ?>" size="35" class="loginText" type="text" readonly="readonly">
									</td>
								</tr>
								<tr>
									<td width="20%"><font color="black"><strong>Alamat</strong></font></td>
									<td>
										<textarea class="addressText" name="alamat1" readonly="readonly"><?php echo $dmember['alamat_member']; ?></textarea>
									</td>
								</tr>
								<tr>
									<td><font color="black"><strong>Provinsi</strong></font></td>
									<td>
										<input name="provinsi1" value="<?php echo $dmember['nama_provinsi']; ?>" class="loginText" type="text" readonly="readonly">
									</td>
								</tr>
								<tr>
									<td><font color="black"><strong>Kota</strong></font></td>
									<td>
										<input name="idkota1" value="<?php echo $dmember['id_kota']; ?>" type="hidden">
										<input name="kota1" value="<?php echo $dmember['nama_kota']; ?>" class="loginText" type="text" readonly="readonly">
									</td>
								</tr>
								<tr>
									<td><font color="black"><strong>Kodepos</strong></font></td>
									<td>
										<input name="kodepos1" value="<?php echo $dmember['kodepos_member']; ?>" class="loginText" type="text" readonly="readonly">
									</td>
								</tr>
								<tr>
									<td><font color="black"><strong>No Telp</strong></font></td>
									<td>
										<input name="telp1" value="<?php echo $dmember['telp_member']; ?>" class="loginText" type="text" readonly="readonly">
									</td>
								</tr>
								<tr>
									<td>&nbsp;</td>
									<td>
										<input name="submit1" value="Next" class="buton" type="submit">
									</td>
								</tr>
							</tbody>
						</table>
					</form>
					</div>
					<div id="2" style="border-radius:10px; -moz-border-radius: 5px; -webkit-border-radius: 10px; border:1px #999999 solid;border-collapse:collapse; background:#CCCCCC">
						<table class="affiliateTable" width="100%" border="0" cellpadding="0" cellspacing="0">
							<tbody>
								<tr>
									<td><font color="black"><strong>Nama Lengkap</strong></font></td>
									<td><input name="nama2" id="namaLengkap" size="35" class="loginText" type="text"></td>
								</tr>
								<tr>
									<td width="20%"><font color="black"><strong>Alamat</strong></font></td>
									<td>
										<textarea class="addressText" name="alamat2"></textarea>
									</td>
								</tr>
								<tr>
									<td><font color="black"><strong>Provinsi</strong></font></td>
									<td>
										<select name="provinsi" id="provinsi">
											<option value="-">-- Pilih Provinsi --</option>
											<?php
											$queryprov = mysql_query("SELECT *
																		FROM provinsi");
											while($dataprov = mysql_fetch_array($queryprov)){
											?>
												<option value="<?php echo $dataprov['id_provinsi']; ?>"><?php echo $dataprov['nama_provinsi']; ?></option>
											<?php
											}
											?>
										</select>
									</td>
								</tr>
								<tr>
									<td><font color="black"><strong>Kota</strong></font></td>
									<td>
										<select name="idkota2" id="kota"></select>
									</td>
								</tr>
								<tr>
									<td><font color="black"><strong>Kodepos</strong></font></td>
									<td>
										<input name="kodepos2" class="postcodeText" type="text" maxlength="5">
									</td>
								</tr>
								<tr>
									<td><font color="black"><strong>No Telp</strong></font></td>
									<td>
										<input name="telp2" class="loginText" type="text" maxlength="15">
									</td>
								</tr>
								<tr>
									<td>&nbsp;</td>
									<td>
										<input name="submit2" value="Next" class="buton" type="submit">
									</td>
								</tr>
							</tbody>
						</table>
					</form>
            </div>
    </div>
    </div>
</div>
<script>
	$("#provinsi").change(function(){ 
		var idprov = $("#provinsi").val();
		$.ajax({ 
				url: "inc/getdata/kota.php", 
				data: "idprov="+idprov, 
				cache: false, 
				success: function(msg){
					$("#kota").html(msg); 
				} 
		}); 
	}); 
</script>