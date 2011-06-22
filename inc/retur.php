<?php
if(isset($_POST['retur']) && ($_POST['retur']!='Cari Retur')){
		$sqlcari = "AND nama_produk LIKE '%$_POST[retur]%'";
}
else
	$sqlcari = "";
	
?>
   	<div class="center_title_bar">Retur Produk</div>
    <div class="sorting" style="margin-top:-35px; width:183px;">
    <form method="post">
    <input type="text" name="retur" class="newsletter_input" value="<?php if(isset($_POST['textcari'])){ echo $_POST['textcari']; } else { echo "Cari Retur"; }?>" onblur="if(this.value=='') this.value='Cari Retur';" onfocus="if(this.value=='Cari Retur') this.value='';" /><input type="image" src="images/search.png" name="search" style="margin-bottom:-4px;"/>
     </form>  </div>
     <div style="width:100%; text-align:center;clear:both">
            <div class="panah"><span class="step">Step 1</span><br />Pilih Produk Retur</div>  
            <div class="panah2"><span class="step">Step 2</span><br />Detail retur</div>  
            <div class="panah2"><a href="?page=returcart"><span class="step">Step 3</span><br />Keranjang Retur</a></div>  
            <div class="panah2"><span class="step">Step 4</span><br />Kirim Retur</div> 
     </div> 
    <?php
	$cariretur=mysql_query("SELECT * FROM pembelian 
						   WHERE status='terima'
						   AND id_member=$_SESSION[id_member]
						   ORDER BY id_pembelian DESC");
	while ($ret=mysql_fetch_array($cariretur)){ ?>
    <div style="margin:7px; border:#0F6 2px solid; clear:both; 	-moz-border-radius: 9px;-webkit-border-radius: 9px;
">
    <div class="alamat">
     <table width="100%" cellpadding="3">
     <tr><td width="20%">Id Pembelian </td><td>: <?php echo $ret['id_pembelian'];?></td></tr>
     <tr><td>Tanggal Pembelian </td><td>: <?php echo tgl_indo($ret['tgl_bayar']);?></td></tr>
     <tr><td colspan="2">Silahkan Pilih produk yang akan direturkan</td></tr>
     </table>
     </div>
	<?php		$sqlproduk = "SELECT * FROM produk a, detailproduk b, pembelian c, detail_pembelian d, warna e, ukuran f 
					 		  WHERE a.id_produk=b.id_produk
							  AND b.id_detailproduk=d.id_detailproduk
							  AND b.id_warna=e.id_warna
							  AND b.id_ukuran=f.id_ukuran
							  AND c.id_pembelian=d.idpembelian
							  AND c.id_pembelian=$ret[id_pembelian]
					 		  $sqlcari
							  GROUP BY d.id_detailproduk
							  order by nama_produk";
	$qproduk = mysql_query($sqlproduk) or die(mysql_error());
	$no = 0;
	$kolom=1;
	$i=0;
	$no = $posisi+1;
	while($dproduk = mysql_fetch_array($qproduk)){
				$qgbr=mysql_fetch_array(mysql_query("SELECT nama_gambar gbr from gambar 
												   WHERE id_produk=$dproduk[id_produk]"));
				$gbr=$qgbr['gbr'];
	?>
    <div style="display:inline-block; clear:both;">
   	<div class="prod_box" style="width:150px;">
            <div class="center_prod_box">
            <a href="?page=dretur&idb=<?php echo $dproduk['id_detailproduk']; ?>">            
             <div class="product_img">
             <img src="images/product/<?php echo $gbr; ?>" alt="" height="175" width="160"  border="0" title="Klik Untuk Melihat detil" /></div>
         <div class="frame"></div>
                 <div class="prod_price"><?php echo $dproduk['nama_produk']; ?></div>  
                 <div class="nama_prod" style="color:#333"><?php echo $dproduk['nama_warna'].' - '.$dproduk['nama_ukuran']; ?></div></a>  
            </div>
    </div></div>
    <?php
	$i++;
	$no++;
	}	
	echo "</div>";
	}
