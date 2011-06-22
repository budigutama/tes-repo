<?php
$idn = addslashes($_GET['idn']);
if(isset($_POST['retur'])){
	$qty_retur = $_POST['qty_retur'];
	$komplain = $_POST['komplain'];
	$detailbarang = $_POST['iddb'];
	$hargaretur=$_POST['hargaretur'];
	$cek_d_ret=mysql_query("SELECT * FROM detail_retur WHERE idpembelian=$idn AND id_barangdetail=$detailbarang");
	if (mysql_num_rows($cek_d_ret)==0){
	mysql_query("INSERT INTO detail_retur VALUES
				(0,'$idn','$detailbarang','$qty_retur','$hargaretur','$komplain','".session_id()."')");
	}
	else {
	mysql_query("UPDATE detail_retur SET qty_retur=qty_retur+$qty_retur, komplain='$komplain'
				WHERE idpembelian=$idn AND id_barangdetail=$detailbarang");	
	}
	mysql_query("UPDATE detail_pembelian SET status_produk='retur', retur_qty=retur_qty+$qty_retur 
				WHERE idpembelian='$idn' AND id_barangdetail='$detailbarang'");
}
if(isset($_POST['konfirm'])){
	$idbd = $_POST['iddb'];
	$ck_rt=mysql_query("SELECT * FROM retur a, detail_retur b 
					   WHERE a.id_retur=b.id_retur
					   AND b.idpembelian=$idn
					   AND b.id_barangdetail=$idbd");
	if(mysql_num_rows($ck_rt)==0){
    mysql_query("INSERT INTO retur VALUES ('','$_SESSION[id_member]','','',now(),'','kirim')");
	$qidr=mysql_fetch_array(mysql_query("SELECT id_retur FROM retur 
										WHERE id_member='$_SESSION[id_member]'
										ORDER BY id_retur DESC"));
	$idr=$qidr['id_retur'];
	mysql_query("UPDATE detail_retur SET id_retur='$idr' 
				WHERE session_id='".session_id()."'
				AND idpembelian='$idn'
				AND id_retur=0");
	}
		echo "<script>window.location = '?page=retur';</script>";	
} 
?>
<div class="center_title_bar">Detail Pembelian - <?php echo $idn; ?></div>
<div class="prod_box_big">
	<div class="center_prod_box_big">
                <?php
                    $qlastdetail_pembelian = mysql_query("SELECT *
                                                 FROM detail_pembelian as a, pembelian as b
                                                 WHERE a.idpembelian = b.id_pembelian
                                                 AND b.id_member = $_SESSION[id_member]
                                                 AND b.id_pembelian = $idn
                                                 GROUP BY b.id_pembelian
                                                 ORDER BY b.id_pembelian DESC LIMIT 1");
                    $datacustom = mysql_fetch_array($qlastdetail_pembelian);
                ?>
                    <table align="left">
                        <tr align="left">
                            <td><h3>No Resi Anda </h3></td>
                            <td><h3>: <?php echo $datacustom['kirim_resi']; ?></h3></td>
                        </tr>
                        <tr align="left">
                            <td>Nama Pemesan</td>
                            <td>: <?php echo $datacustom['kirim_nama']; ?></td>
                        </tr>
                        <tr align="left">
                            <td>Alamat</td>
                            <td>: <?php echo $datacustom['kirim_alamat']; ?></td>
                        </tr>
                        <tr align="left">
                            <td>Kodepos</td>
                            <td>: <?php echo $datacustom['kirim_kdpos']; ?></td>
                        </tr>
                        <tr align="left">
                            <td>No Telp</td>
                            <td>: <?php echo $datacustom['kirim_telp']; ?></td>
                        </tr>
                    </table>    
                <table border="0" cellpadding="2" cellspacing="3" width="100%" style="font-size:12px; border:#444 dotted 1px;">
                <tr>
                    <td align="center"><strong>No</strong></td>
                    <td align="center"><strong>Nama Barang</strong></td>
                    <td align="center"><strong>Jumlah</strong></td>
                    <td align="center"><strong>Keterangan</strong></td>
                </tr>
                <?php
                $querycart = mysql_query("SELECT * 
										FROM pembelian as a, detail_pembelian as b, barangdetail as c, barang as d, warna e, ukuran f
                                        WHERE a.id_pembelian = b.idpembelian
                                        AND b.id_barangdetail = c.id_barangdetail
                                        AND c.id_barang = d.id_barang
                                        AND c.id_warna=e.id_warna
										AND c.id_ukuran=f.id_ukuran
										AND a.id_member = $_SESSION[id_member]
                                        AND a.id_pembelian = $idn");
				$no=0;
                while($datacart = mysql_fetch_array($querycart)){
					$idbd=$datacart['id_barangdetail'];
					$cstok=$datacart['qty'];
					$no++;
					$qcek = mysql_query("SELECT * FROM detail_retur WHERE idpembelian = $idn AND id_barangdetail = $idbd");
					$dcek = mysql_fetch_array($qcek);
					$qty_ret=$dcek['qty_retur'];
                ?>
                <tr>
                    <td><?php echo $no; ?></td>
                    <td><?php echo "$datacart[nama_barang] ($datacart[nama_ukuran] - $datacart[nama_warna])"; ?></td>
                    <td align="center">
                <form method="post" action=""> 
                    	<select name="qty_retur">
                        <?php
						$i=1;
						$nstok = $cstok-$qty_ret;
						while($i<=$nstok){
							?>
							<option value="<?php echo $i; ?>" <?php echo($cstok == $qty_ret)?"disabled":""; ?> <?php echo($qty_ret == $i)?"selected":""; ?>><?php echo $i; ?></option>	
							<?php
							$i++;
						}
						?>
                        </select>
                    </td>
                    <td align="center">
                    	<textarea name="komplain" <?php echo($cstok == $qty_ret)?"disabled":""; ?> cols="25" rows="3"><?php echo $dcek['komplain']; ?></textarea>
                    </td>
                    <td align="center">
                    	<input type="hidden" name="iddb" value="<?php echo $idbd; ?>" />
                    	<input type="hidden" name="hargaretur" value="<?php echo $datacart['hargabeli']; ?>" />
			        <?php
					  if($cstok != $qty_ret){
			  		?>
              			<input type="submit" name="retur" value="Retur" />
              		<?php
			  		}?>
              </form>
                    </td>
                </tr>
                <?php
                } //end while
				$qrtr=mysql_query("SELECT * FROM detail_pembelian a, detail_retur b, retur c
							   WHERE a.idpembelian = $idn
							   AND a.idpembelian=b.idpembelian
							   AND b.id_retur=c.id_retur
							   AND id_barangdetail = $idbd");
                if (mysql_num_rows($qrtr) == 0){
				?>
                <tr><td colspan="4">
              	<form method="post">
                <input type="hidden" name="iddb" value="<?php echo $idbd; ?>" />
                <input type="submit" name="konfirm" value="Selesai" /></form>
                </td></tr>
                <?php
			  		}
				else {
					?>
                <tr><td colspan="4">
                 barang sudah di returkan
                </td></tr>
                <?php
			  		} ?>
              </table>
    </div>
</div>