<script src="js/prety/js/jquery.prettyPhoto.js" type="text/javascript" charset="utf-8"></script>
<?php
if(isset($_GET['idb']))
	$idb = addslashes($_GET['idb']);
	$qdetail = mysql_query("SELECT * FROM detail_pembelian a, detailproduk b, produk c, warna d, ukuran f
						   WHERE a.id_detailproduk = $idb
						   AND a.id_detailproduk=b.id_detailproduk
						   AND b.id_warna=d.id_warna
						   AND b.id_ukuran=f.id_ukuran
						   AND b.id_produk=c.id_produk
						   GROUP BY a.id_detailproduk");
	$ddetail = mysql_fetch_array($qdetail);
?>
   	<div class="center_title_bar">Detail Produk Retur <?php echo $ddetail['nama_produk']; ?></div>
    <div style="width:100%; text-align:center;clear:both">
            <div class="panah"><a href="?page=retur"><span class="step">Step 1</span><br />Pilih Produk Retur</a></div>  
            <div class="panah"><span class="step">Step 2</span><br />Detail retur</div>  
            <div class="panah2"><a href="?page=returcart"><span class="step">Step 3</span><br />Keranjang Retur</a></div>  
            <div class="panah2"><span class="step">Step 4</span><br />Kirim Retur</div> 
     </div> 

    	<div class="prod_box_big">
            <div class="center_prod_box_big">
<?php if(isset($_POST['cartretur'])){
	$jml=$_POST['jml'];
	$iddb=$_POST['iddb'];
	$idp=$_POST['idp'];
	if($jml != '-'){
		$qcek = mysql_query("SELECT * FROM detail_pembelian 
							 WHERE idpembelian = $idp 
							 AND id_detailproduk=$iddb");
		$cek = mysql_fetch_array($qcek);
		$qty=$cek['qty']-$cek['retur_qty'];
		if ($jml<=$qty){
		$ncart = mysql_num_rows(mysql_query("SELECT * FROM temp_retur
											WHERE session_id = '".session_id()."'
											AND id_detailproduk = $iddb"));
		if($ncart != 0){
			mysql_query("UPDATE temp_retur SET jumlah_retur = jumlah_retur + $jml
						WHERE session_id = '".session_id()."'
						AND id_detailproduk = $iddb");
		echo "<script>window.location = '?page=returcart';</script>";
		}
		else{
			mysql_query("INSERT INTO temp_retur VALUES(null,$iddb,'".session_id()."',$jml,$idp,'$_POST[komplain]',now())") or die(mysql_error());
		echo "<script>window.location = '?page=returcart';</script>";
		}
	}
	else{
		echo "<h3> tidak mencukupi, Silahkan Ulangi Kembali. $idp $iddb </h3>";
	}
	} 
	else{
		echo "<h3>Anda Belum Memilih jumlah retur, Silahkan Ulangi Kembali.</h3>";
	}
}
 ?>

  <div class="detail_img">
		<div class="gallery clearfix" style="width:306px; float:right;">
				 <?php 
				$qgbr=mysql_query("SELECT * from gambar 
								  WHERE id_produk=$ddetail[id_produk]");
				$i=0;
				while($dgbr=mysql_fetch_array($qgbr)){
				$i++;
				$gbr=$dgbr['nama_gambar'];
				?>
			<span style="display:inline-block; float:right;"><a href="images/product/<?php echo $gbr; ?>" rel="prettyPhoto[gallery1]" title="<?php echo $ddetail['deskripsi_produk']; ?>">
			<img src="images/product/<?php echo $gbr; ?>" width="100" height="120" title="Klik untuk Zoom gambar" alt="<?php echo "$ddetail[nama_produk] - $i"; ?>" /></a></span>
			<?php } ?>
		<script type="text/javascript" charset="utf-8">
		$(document).ready(function(){
			$(".gallery:first a[rel^='prettyPhoto']").prettyPhoto({animationSpeed:'slow',theme:'light_square',slideshow:5000, autoplay_slideshow: true});
			$(".gallery:gt(0) a[rel^='prettyPhoto']").prettyPhoto({animationSpeed:'fast',slideshow:10000});
			
			$("#custom_content a[rel^='prettyPhoto']:first").prettyPhoto({
				custom_markup: '<div id="map_canvas" style="width:260px; height:265px"></div>',
				changepicturecallback: function(){ initialize(); }
			});

			$("#custom_content a[rel^='prettyPhoto']:last").prettyPhoto({
				custom_markup: '<div id="bsap_1237859" class="bsarocks bsap_d49a0984d0f377271ccbf01a33f2b6d6" style="height:260px"></div><div id="bsap_1251710" class="bsarocks bsap_d49a0984d0f377271ccbf01a33f2b6d6"></div>',
				changepicturecallback: function(){ _bsap.exec(); }
			});
		});
		</script>
		<div align="right" style="clear:both;">
             <div style="color:#333; font-size:16px; font-weight:bold;">
			 Rp. <?php echo number_format($ddetail['hargabeli'],"2",".",","); ?>             
			</div>                 
			</div>                 
 		</div>
                </div>
              <div class="details_big_box">
                       <form method="post" action="" >
                         <table>
                         	<tr>
                            	<td colspan="2" style="font-size:14px; font-weight:bold; color:#069;">
								<?php echo $ddetail['nama_produk']; ?></td>
                            </tr>
                         	<tr>
                            	<td width="100px">Id_Produk</td>
                            	<td>: <?php echo $ddetail['id_produk']; ?></td>
                            </tr>
                         	<tr>
                            	<td>Warna</td>
                            	<td>: <?php echo $ddetail['nama_warna']; ?></td>
                            </tr>
                         	<tr>
                            	<td>Size</td>
                            	<td>: <?php echo $ddetail['nama_ukuran']; ?></td>
                            </tr>
                         	<tr>
                            	<td>Jumlah</td>
                            	<td>: <select name="jml">
                                	<option value="-">Jumlah Retur</option>
                                    <?php
									$q =0; 
									while($q < $ddetail['qty']){
									$q++;
									?>
                                    <option value="<?php echo $q; ?>"><?php echo $q; ?></option>
                                    <?php
									}
									?>
                                </select>
                                </span></td>
                            </tr>
                         	<tr>
                            	<td>Komplain</td>
                            	<td><textarea name="komplain" cols="25" rows="3"></textarea></td>
                            </tr>
                         </table>
                         </div>
                         <br />
                         <input type="hidden"  name="iddb" value="<?php echo $ddetail['id_detailproduk']; ?>" />
                         <input type="hidden"  name="idp" value="<?php echo $ddetail['idpembelian']; ?>" />
                         <input type="submit" name="cartretur" value="Keranjang Retur" class="buton">
                     </form>
          <br /><br />           
            </div>
        </div>
