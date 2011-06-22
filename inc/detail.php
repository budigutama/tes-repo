<script src="js/star/jquery.tools.min.js" type="text/javascript"></script>
<script type="text/javascript" src="js/star/jquery-ui.custom.min.js"></script>
<script type="text/javascript" src="js/star/jquery.ui.stars.js"></script>
<script src="js/prety/js/jquery.prettyPhoto.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" src="lib/jquery.easing.1.3.js"></script>
<script type="text/javascript" src="lib/jquery.hrzAccordion.js"></script>
<script type="text/javascript" src="lib/jquery.hrzAccordion.examples.js"></script>
<?php

viewcounter($_GET['idb']);

if(isset($_GET['idb']))
	$idb = addslashes($_GET['idb']);
	$qdetail = mysql_query("SELECT * FROM produk a, kategori b
						   WHERE a.id_produk = $idb
						   AND a.id_kategori=b.id_kategori");
	$ddetail = mysql_fetch_array($qdetail);
	$dtstok = mysql_query("SELECT SUM(stok_detailproduk) stok FROM detailproduk
						   WHERE id_produk = $idb");
	$stok = mysql_fetch_array($dtstok);
?>
   	<div class="center_title_bar">Detail Produk <?php echo $ddetail['nama_produk']; ?></div>
    	<div class="prod_box_big">
            <div class="center_prod_box_big">
<?php
if(isset($_POST['submitvote']))
updatevote($_POST['idb'],$_POST['vote']);

 if(isset($_POST['addcart'])){
	$jml=$_POST['jml'];
	if($_POST['warna'] != '-' && $_POST['ukuran'] != '-' && $jml){
		$qproduk = mysql_query("SELECT * FROM detailproduk WHERE id_produk = $_POST[produk] AND id_warna = $_POST[warna] AND id_ukuran = $_POST[ukuran]");
		$dproduk = mysql_fetch_array($qproduk);
		if ($jml<=$dproduk['stok_detailproduk']){
		$iddb = $dproduk['id_detailproduk'];
		$ncart = mysql_num_rows(mysql_query("SELECT * FROM temp_pemesanan
											WHERE session_id = '".session_id()."'
											AND id_detailproduk = $iddb"));
		if($ncart != 0){
			mysql_query("UPDATE temp_pemesanan SET qty = qty + $jml
						WHERE session_id = '".session_id()."'
						AND id_detailproduk = $iddb");
		echo "<script>window.location = '?page=cart';</script>";
		}
		else{
			$qdetail = mysql_query("SELECT * FROM detailproduk as a, produk as b
									WHERE a.id_produk = b.id_produk
									AND a.id_detailproduk = $iddb") or die(mysql_error());
			$ddetail = mysql_fetch_array($qdetail);
			$berat = $ddetail['berat_detailproduk'];
			$idmember = NULL;
			if(isset($_SESSION['id_member']))
				$idmember = $_SESSION['id_member'];
			if($ddetail['diskon_produk'] !=0)
				$hargaproduk = hargadiskon($ddetail['id_produk']);
			else
				$hargaproduk = $ddetail['harga_produk'];
			mysql_query("INSERT INTO temp_pemesanan VALUES(null,$iddb,'".session_id()."',$jml,$berat,$hargaproduk,now())") or die(mysql_error());
		echo "<script>window.location = '?page=cart';</script>";
		}
	}
	else{
		echo "<h3> Maaf, Stok tidak mencukupi, Silahkan Ulangi Kembali.</h3>";
	}
	} 
	else{
		echo "<h3> Anda Belum Memilih Warna, Ukuran, maupun Jumlah. Silahkan Ulangi Kembali.</h3>";
	}
}
 ?>
  <div class="detail_img" style="display:block">
		<div class="gallery clearfix" style="width:600px;" align="right">
		<ul class="test">
				 <?php 
				$qgbr=mysql_query("SELECT * from gambar 
								  WHERE id_produk='$idb'");
				$i=0;
				while($dgbr=mysql_fetch_array($qgbr)){
				$i++;
				$gbr=$dgbr['nama_gambar'];
				?>
  <li><div class="handle"></div>
<div align="center"><a href="images/product/<?php echo $gbr; ?>" rel="prettyPhoto[gallery1]" title="<?php echo $ddetail['deskripsi_produk']; ?>">
	<img src="images/product/<?php echo $gbr; ?>" height="300" title="Klik untuk Zoom gambar" alt="<?php echo "$ddetail[nama_produk] - $i"; ?>" /></a></div>
  </li>
			<?php } ?>
		</ul></div>
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

		<div align="left">
					<?php
		if($ddetail['diskon_produk'] != 0){
			$col="#C36";
			$harga="<span style=text-decoration:line-through>Rp.".number_format($ddetail['harga_produk'],"2",".",",")."</span> - Dis ".$ddetail['diskon_produk']."% = Rp.".number_format(hargadiskon($ddetail['id_produk']),"2",".",",");
		}
		else {
			$col="#666";
			$harga="Rp.".number_format(hargadiskon($ddetail['id_produk']),"2",".",",")."";
		}?>
             <div align="center" style="color:<?php echo $col;?>; font-size:16px; font-weight:bold;">
			 <?php echo $harga; ?>             
			</div>                 
                 	<script>
						function fbs_click() {
							u=location.href;
							t=document.title;
							window.open('http://www.facebook.com/sharer.php?u='+encodeURIComponent(u)+'&t='+encodeURIComponent(t),'sharer','toolbar=0,status=0,width=626,height=436');
							return false;
						}
                    </script>
                    <a href="http://www.facebook.com/share.php?u='http:/<?php echo $_SERVER['PHP_SELF']; ?>?page=detail&idb=<?php echo $_GET['idb']; ?>'&t='<?php echo $ddetail['nama_produk']; ?>'" onclick="return fbs_click()" target="_blank" title="Shar On Facebook">
                      	<img src="images/fb2.png" alt="Share on Facebook" border="0"/></a>
                    <a href="http://twitter.com/home?status='<?php echo $ddetail['nama_produk']; ?>'" title="Click to share this post on Twitter"><img src="images/twitter2.png" alt="Share on Twitter" border="0"></a></div>
                 </div>
				 
	<div class="spesifikasiukuran">
	<?php spesifikasiview($ddetail['nama_kategori']); ?>
	</div>
<div style="display:block; width:260px;">
<form method="post" action="" >
                         <table style="font-size:12px">
                         	<tr>
                            	<td><strong>Stok</strong></td>
                            	<td>: <span id="stok"><?php echo $stok['stok']; ?></span></td>
                            </tr>
                         	<tr>
                            	<td><strong>Berat</strong></td>
                            	<td>: <span id="berat"><?php echo $ddetail['berat_detailproduk']; ?></span><span> Kg</span></td>
                            </tr>
                         	<tr>
                            	<td><strong>Warna</strong></td>
                            	<td>: <span>
								<select name="warna" id="warna">
                                	<option value="-">-- Pilih Warna --</option>
                                    <?php
									$qwarna = mysql_query("SELECT * FROM detailproduk as a, warna as b
														   WHERE a.id_warna = b.id_warna
														   AND a.id_produk = $idb
														   GROUP BY a.id_warna");
									while($dwarna = mysql_fetch_array($qwarna)){
									?>
                                    <option value="<?php echo $dwarna['id_warna']; ?>"><?php echo $dwarna['nama_warna']; ?></option>
                                    <?php
									}
									?>
                                </select>
                                </span></td>
                            </tr>
                         	<tr>
                            	<td><strong>Ukuran</strong></td>
                            	<td>: <span class="blue">
                                	<select name="ukuran" id="ukuran">
                                    	<option value="-">-- Pilih Ukuran --</option>
                                    </select></span>
                                </td>
                            </tr>
                            <tr>
                            	<td><strong>Jumlah</strong></td>
                            	<td>: <input name="jml" type="text" size="2" /> Pcs
                                </td>
                            </tr>
                         	<tr>
                            	<td><strong>Diskon</strong></td>
                            	<td>: <span class="red"><?php echo $ddetail['diskon_produk']; ?> %</span></td>
                            </tr>
                         	<tr>
                            	<td colspan="2"><strong>Deskripsi :</strong></td>
							</tr>
							<tr>
                            	<td colspan="2"><?php echo $ddetail['deskripsi_produk']; ?></td>
                            </tr>
                         </table>
                         <br />
                         <input type="hidden" id="produk" name="produk" value="<?php echo $ddetail['id_produk']; ?>" />
                         <input type="submit" name="addcart" value="Masuk Keranjang" class="buton">
                     </form>
                     </div>
<table width="210" border="0">
<tr><td>
   <script type="text/javascript">
	$(function(){
	$("#starify").children().not(":input").hide();
	$("#starify").stars({
	  cancelShow: false
	});
	});
	</script>
<?php
	$qvote = mysql_query("SELECT * FROM produk WHERE id_produk = $_GET[idb]");
	$dvote = mysql_fetch_array($qvote);
	$rate = (int)ceil($dvote['rating_produk']);
?>
                                <br />
<form class="uniForm" action="" method="post">
<div class="multiField" id="starify">
<input type="radio" name="vote" id="vote1" value="1" <?php if($rate==1) echo "checked=checked"; ?> />
<input type="radio" name="vote" id="vote2" value="2" <?php if($rate==2) echo "checked=checked"; ?> />
<input type="radio" name="vote" id="vote3" value="3" <?php if($rate==3) echo "checked=checked"; ?> />
<input type="radio" name="vote" id="vote4" value="4" <?php if($rate==4) echo "checked=checked"; ?> />
<input type="radio" name="vote" id="vote5" value="5" <?php if($rate==5) echo "checked=checked"; ?> />
</div>
<div class="buttonHolder">
<input type="hidden" name="idb" value="<?php echo $idb; ?>" />&nbsp;
<input type="submit" class="buton" name="submitvote" value="Pilih" style="margin-top:5px;">
</div></form>
                            </td>
                        </tr>
                     </table><br />

<div class="center_title_bar" style="width:540px;">Anda Mungkin Menginginkan Produk Sejenis Yang Lainnya...</div>
<?php 
	$qproduk = mysql_query("SELECT * FROM produk a, gambar b
							WHERE id_kategori=$ddetail[id_kategori]
							AND a.id_produk=b.id_produk
							GROUP BY a.id_produk
							LIMIT 6") or die(mysql_error());
	
	$no = 0;
	$kolom=1;
	$i=0;
	$no = $posisi+1;
	while($dproduk = mysql_fetch_array($qproduk)){
				$qgbr=mysql_fetch_array(mysql_query("SELECT nama_gambar gbr from gambar 
												   WHERE id_produk=$dproduk[id_produk]"));
				$gbr=$qgbr['gbr'];
	?>
   	<div class="prod_box" style="width:140px;">
            <div class="center_prod_box">
            <a href="?page=detail&idb=<?php echo $dproduk['id_produk']; ?>">            
             <div class="product_img">
             <img src="images/product/<?php echo $gbr; ?>" alt="" height="175" width="160"  border="0" title="Klik Untuk Melihat detil" /></div>
         <div class="frame"></div>
         <?php
		if($dproduk['diskon_produk'] != 0){
			$col="#C36";
			$harga="Rp.".number_format($dproduk['harga_produk'],"2",".",",")." - Dis ".$dproduk['diskon_produk']."%";
		}
		else {
			$col="#666";
			$harga="Rp.".number_format(hargadiskon($dproduk['id_produk']),"2",".",",")."";
		}?>
                 <div class="prod_price"><?php echo $dproduk['nama_produk']; ?></div>  
                 <div class="nama_prod" style="color:<?php echo $col;?>"><?php echo $harga; ?></div></a>  
            </div>
    </div>
    <?php
	$i++;
	$no++;
	}
	?>
 
                    
       <table width="540px;" cellpadding="0" cellspacing="0" style="display:inline-table;">
       <?php 
	   if(isset($_POST['komentar'])){
		mysql_query("INSERT INTO testi_produk VALUES (NULL,'$_POST[idp]','$_SESSION[id_member]','$_POST[testi]','0',now())");
		echo "<script>windows.location='?page=detail&idb=$_POST[idp]'</script>";
		}
	   if (isset($_SESSION['id_member'])){
	   ?>
       <tr>
       	<th align="center" colspan="3" height="40px;">
		<div class="center_title_bar" style="width:540px;">
		Sampaikan Komentar anda untuk produk <?php echo $ddetail['nama_produk']; ?></div></th>
       </tr>
       <form action="" method="post">
       <tr>
        <td colspan="3" align="center"><input type="hidden" name="idp" value="<?php echo $ddetail['id_produk']; ?>" />
        <textarea rows="2" cols="67" name="testi" style="border:thin #666 solid; -moz-border-radius: 8px;
-webkit-border-radius: 8px;padding:2px;color:#999999;"></textarea></td>
       <tr>
       <tr>
        <td align="right" colspan="3">
		<input type="submit" name="komentar" value="  Post  " class="buton"/></td>
       </tr></form>
       <?php } 
	   $qtesti=mysql_query("SELECT * FROM testi_produk a,  produk c
						  WHERE a.id_produk=c.id_produk
						  AND status_testi='1'
						  AND a.id_produk='$idb'
						  ORDER BY a.tgl_testi
						  LIMIT 20");
	   if ((mysql_num_rows($qtesti)>0) AND !isset($_SESSION['id_member'])){
	   echo "
       <tr height=20px>
        <div class=center_title_bar style=width:540px;>Testimoni Produk</div>
       </tr>";
	   }
	   while ($testi=mysql_fetch_array($qtesti)){
		$member=mysql_fetch_array(mysql_query("SELECT nama_member nama FROM member WHERE id_member=$testi[id_member]"));
		if ($testi['id_member']==0){
			$nama="Elitez Admin";?>
		<tr bgcolor="#CCCCCC">
    		<td style="font-size:13px; font-weight:bold; color:#069" valign="top" align="right" colspan="2" width="400px">
	   		<?php echo $nama; ?> </td>
   	 		<td width="55px" rowspan="3" align="right"><img src="images/logo.png" width="45" height="40"/></td>
 		</tr>
  		<tr bgcolor="#CCCCCC">
    		<td colspan="2" align="right" style="font-size:12px; letter-spacing:1;"><?php echo $testi['testimoni']; ?></td>
  		</tr>
  		<tr bgcolor="#CCCCCC">
    		<td colspan="2" style="font-size:9px; color:#F00" align="right">
            Pada Tangal: <?php echo tgl_indo($testi['tgl_testi']); ?>  jam : <?php echo substr($testi['tgl_testi'],11,8); ?></td>
  		</tr>
       <tr>
       		<td colspan="3"><hr /></td>
       </tr>
		<?php }
		else {
			$nama=$member['nama'];?>
       <tr>
       <td rowspan="3" valign="top" width="55px"><img src="images/user.jpg" /></td>
       <td style="font-size:13px; font-weight:bold; color:#069" valign="top">
	   <?php echo $nama; ?> </td>
       <td>&nbsp;</td>
       </tr>
       <tr>
       <td colspan="2" style="font-size:12px; letter-spacing:1;"><?php echo $testi['testimoni']; ?></td>
       </tr>
       <tr>
       <td colspan="2" style="font-size:9px; color:#F00">Pada Tangal: <?php echo tgl_indo($testi['tgl_testi']); ?>  jam : <?php echo substr($testi['tgl_testi'],11,8); ?></td>
       </tr>
       <tr>
       <td colspan="3"><hr /></td>
       </tr>
       <?php }} ?>
       </table>
            </div>
        </div>
        
<script type="text/javascript"> 
	$("#warna").change(function(){ 
		var idb = $("#produk").val();
		var idw = $("#warna").val();
		$.ajax({ 
			url: "inc/getdata/ukuran.php", 
			data: "idb="+idb+"&idw="+idw, 
			cache: false, 
			success: function(msg){
				$("#ukuran").html(msg); 
			} 
		 }); 
    });
	
	$("#warna").change(function(){ 
		var idb = $("#produk").val();
		var idw = $("#warna").val();
		$.ajax({ 
			url: "inc/getdata/stok.php", 
			data: "idb="+idb+"&idw="+idw, 
			cache: false, 
			success: function(msg){
				$("#stok").html(msg); 
			} 
		 }); 
    });
	
	$("#ukuran").change(function(){ 
		var idb = $("#produk").val();
		var idw = $("#warna").val();
		var idu = $("#ukuran").val();
		$.ajax({ 
			url: "inc/getdata/stok.php", 
			data: "idb="+idb+"&idw="+idw+"&idu="+idu, 
			cache: false, 
			success: function(msg){
				$("#stok").html(msg); 
			} 
		 }); 
    });
	
	$("#warna").change(function(){ 
		var idb = $("#produk").val();
		var idw = $("#warna").val();
		$.ajax({ 
			url: "inc/getdata/berat.php", 
			data: "idb="+idb+"&idw="+idw, 
			cache: false, 
			success: function(msg){
				$("#berat").html(msg); 
			} 
		 }); 
    });
	
	$("#ukuran").change(function(){ 
		var idb = $("#produk").val();
		var idw = $("#warna").val();
		var idu = $("#ukuran").val();
		$.ajax({ 
			url: "inc/getdata/berat.php", 
			data: "idb="+idb+"&idw="+idw+"&idu="+idu, 
			cache: false, 
			success: function(msg){
				$("#berat").html(msg); 
			} 
		 }); 
    });
</script>                 
