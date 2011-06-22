 <div class="border_box">
   		<div class="shopping_cart">
        	<div class="title_box">Keranjang Belanja</div>
            
            <div class="cart_details">
            <?php
			$qcart = mysql_query("SELECT * FROM temp_pemesanan
								 WHERE session_id = '".session_id()."'");
			
			$qty = 0;
			$total = 0;
			while($dcart = mysql_fetch_array($qcart)){
				$qty   = $qty + $dcart['qty'];
				$total = $total + ($dcart['temp_hargadiskon'] * $dcart['qty']);
			}
			echo $qty; ?> items <br />
            <span class="border_cart"></span>
            Total: <span class="price">Rp. <?php echo number_format($total,"2",".",","); ?></span>
            </div>
            
            <div class="cart_icon"><a href="?page=cart" title=""><img src="images/shoppingcart.png" alt="" title="" width="35" height="35" border="0" /></a></div>
        </div>
        </div>
      
     <div class="border_box">
     <div class="title_box">Produk Terlaris</div>  
     <?php
	 $qmostview = mysql_query("SELECT *,SUM(a.qty) as jumlah
							FROM detail_pembelian as a, detailproduk as b, produk as c, gambar d, pembelian e
							WHERE a.id_detailproduk = b.id_detailproduk
							AND e.status='terima'
							AND b.id_produk = c.id_produk
							AND e.id_pembelian = a.idpembelian
							AND d.id_produk = c.id_produk
							GROUP BY c.id_produk
							ORDER BY jumlah DESC LIMIT 1") or die(mysql_error());
	 $randomproduk = mysql_query("SELECT *
							FROM  produk c,gambar d
							WHERE d.id_produk = c.id_produk
							GROUP BY c.id_produk
							ORDER BY c.id_produk DESC LIMIT 1") or die(mysql_error());
	 if (mysql_num_rows($qmostview)!=0){
	 $dmostview = mysql_fetch_array($qmostview); }
	 else {
	 $dmostview = mysql_fetch_array($randomproduk); }
	 ?>
        <div style="margin-left:10px;">  
         <a href="?page=detail&idb=<?php echo $dmostview['id_produk']; ?>">
         <div class="product_img" style="margin-top:3px">
        <img src="images/product/<?php echo $dmostview['nama_gambar']; ?>" alt="" width="160" height="175" border="0" title="" /></div>
         <div class="frame"></div>
         <?php
		if($dmostview['diskon_produk'] != 0){
			$col="#C36";
			$harga="Rp.".number_format($dmostview['harga_produk'],"2",".",",")." - Dis ".$dmostview['diskon_produk']."%";
		}
		else {
			$col="#666";
			$harga="Rp.".number_format(hargadiskon($dmostview['id_produk']),"2",".",",")."";
		}?>
               <div class="prod_price"><?php echo $dmostview['nama_produk']; ?></div>  
               <div class="nama_prod" style="color:<?php echo $col;?>"><?php echo $harga; ?></div></a>
     </div> 
     </div>

     <div class="border_box">
     <div class="title_box">Produk Diskon </div> 
     <?php
	 $qproduk = mysql_query("SELECT * FROM produk c,gambar d
							WHERE d.id_produk = c.id_produk
							ORDER BY diskon_produk DESC LIMIT 1") or die(mysql_error());
	 $cproduk = 1;
	 while($dproduk = mysql_fetch_array($qproduk)){
	 ?> 
        <div style="margin-left:10px;">            
         <a href="?page=detail&idb=<?php echo $dproduk['id_produk']; ?>">
         <div class="product_img" style="margin-top:5px">
		 <img src="images/product/<?php echo $dproduk['nama_gambar']; ?>" alt="" width="160" height="170" border="0" title="" /></div>
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
               <div class="prod_price"><?php echo $dmostview['nama_produk']; ?></div>  
               <div class="nama_prod" style="color:<?php echo $col;?>"><?php echo $harga; ?></div></a>
     <?php
	 $cproduk++;
	 }
	 ?>
</div>
</div>
