<?php
$batas   = 9;
if(isset($_GET['halaman']))
	$halaman = $_GET['halaman'];
	
if(empty($halaman)){
	$posisi  = 0;
	$halaman = 1;
}
else{
	$posisi = ($halaman-1) * $batas;
}

if(isset($_POST['textcari'])){
		$sqlcari = "AND nama_produk LIKE '%$_POST[textcari]%'";
}
else
	$sqlcari = "";
	
?>
    <?php
			if (isset($_POST['urutkan'])){
				$urut=$_POST['urutkan'];
				$idk=$_POST['kate'];
				if ($idk==""){	$kat=""; }
				else { $kat="AND id_kategori='$idk'"; }
					if($urut=='produk_terlaris'){
					 $title = "Produk Terlaku";
					 $sqlproduk = "SELECT *,SUM(qty) as jumlah
							  FROM detail_pembelian as a, detailproduk as b, produk as c
						  	  WHERE a.id_detailproduk = b.id_detailproduk
							  AND b.id_produk = c.id_produk
							  $kat
							  GROUP BY c.id_produk
							  ORDER BY jumlah DESC
							  LIMIT 9";
				}
					elseif($urut=='produk_diskon'){
					 $title = "Produk Diskon";
				     $sqlproduk = "SELECT * FROM produk
							 		WHERE diskon_produk > 0
						  		  	$kat
							 		GROUP BY id_produk order by diskon_produk desc
									LIMIT 9";
				}
					elseif($urut=='produk_lihat'){
					 $title = "Produk Paling Banyak Dilihat";
				     $sqlproduk = "SELECT * FROM produk
							 		WHERE viewcounter_produk > 0
						  		  	$kat
							 		GROUP BY id_produk order by viewcounter_produk desc
									LIMIT 9";
				}
					elseif($urut=='produk_terbaru'){
					 $title = "Produk Terbaru";
				     $sqlproduk = "SELECT * FROM produk a, detailproduk b 
					 				WHERE a.id_produk=b.id_produk
					 				$kat
									GROUP BY b.id_produk
									order by tanggal_detailproduk desc
									LIMIT 9";
				}
			}
			elseif (isset($_GET['size'])){
				$size=$_GET['size'];
				$title = "Produk Size $size";
				$sqlproduk = "SELECT * FROM produk a, detailproduk b, ukuran c
							  WHERE a.id_produk=b.id_produk
							  AND b.id_ukuran=c.id_ukuran
							  AND c.nama_ukuran='$size'
							  GROUP BY a.id_produk
							  LIMIT 9";
			}
			elseif (isset($_GET['warna'])){
				$warna=$_GET['warna'];
				$title = "Produk Warna $warna";
				$sqlproduk = "SELECT * FROM produk a, detailproduk b, warna c
							  WHERE a.id_produk=b.id_produk
							  AND b.id_warna=c.id_warna
							  AND c.nama_warna='$warna'
							  GROUP BY a.id_produk
							  LIMIT 9";
			}
			elseif (isset($_GET['harga'])){
				$harga=$_GET['harga'];
				$title = "Produk Harga Rp.".substr($harga,14,7)."an";
				$sqlproduk = "SELECT * FROM produk a, detailproduk b
							  WHERE a.id_produk=b.id_produk
							  AND a.$harga
							  GROUP BY a.id_produk
							  LIMIT 9";
			}
			else
			{
				$title = "Produk";
				$sqlproduk = "SELECT * FROM produk a, detailproduk b 
					 		  WHERE a.id_produk=b.id_produk
					 		  $sqlcari
							  GROUP BY b.id_produk
							  order by tanggal_detailproduk desc";
			}
	?>
   	<div class="center_title_bar" style="margin-bottom:20px;"><?php echo $title; ?></div>
    <div class="sorting"><?php include "inc/menu.php" ?></div>
    <?php
if(isset($_POST['registrasi'])){
	if($_SESSION['string'] == $_POST['code']){
		$ncari = mysql_num_rows(mysql_query("SELECT *
								 				 FROM member
												 WHERE email_member = '$_POST[email]'"));
			if($ncari == 0){
				$verifikasi = md5(uniqid());
				mysql_query("INSERT INTO member VALUES(null, $_POST[kota], '$_POST[nama]', '$_POST[alamat]',
							'$_POST[telp]', '$_POST[kodepos]', '$_POST[email]', '".md5($_POST['password'])."',
							'".$verifikasi."', '0')") or die(mysql_error());
				//email
				emailregister($_POST[email],$_POST[nama],$_POST[alamat],$_POST[kota],$_POST[telp],$_POST[kodepos],$verifikasi);
					echo "<h3>Terima Kasih Anda Telah Melakukan Registrasi,<br /> Silahkan Verifikasi Account Anda di Email..</h3>";
				}
				else{
					echo "<h3>Sebelumnya Anda Telah Terdaftar !!!</h3>";
				}
	}
	else{
		echo "<h3> Kode Captca Tidak Sesuai !!</h3>";
	}
}

if(isset($_GET['code'])){
	if(mysql_num_rows(mysql_query("SELECT * FROM member WHERE verificationcode_member = '$_GET[code]' AND status_member = '0'")) == 1){
		mysql_query("UPDATE member
					SET status_member = '1'
					WHERE verificationcode_member = '$_GET[code]'");
		echo "<h3>Verifikasi Telah Dilakukan, Silahkan Login !!</h3>";
	}
	else{
		echo "<h3>Verifikasi Gagal !!</h3>";
	}
}
	
	$tampil2 = mysql_query($sqlproduk) or die(mysql_error());
	$jmldata = mysql_num_rows($tampil2);
	$jmlhal  = ceil($jmldata/$batas);
			
			if (isset($_POST['urutkan'])){
				$urut=$_POST['urutkan'];
				$idk=$_POST['kate'];
				if ($idk==""){	$kat=""; }
				else { $kat="AND id_kategori='$idk'"; }
					if($urut=='produk_terlaris'){
					 $title = "Produk Terlaku";
					 $sqlproduk = "SELECT *,SUM(qty) as jumlah
							  FROM detail_pembelian as a, detailproduk as b, produk as c
						  	  WHERE a.id_detailproduk = b.id_detailproduk
							  AND b.id_produk = c.id_produk
							  $kat
							  GROUP BY c.id_produk
							  ORDER BY jumlah DESC
							  LIMIT 9";
				}
					elseif($urut=='produk_diskon'){
					 $title = "Produk Diskon";
				     $sqlproduk = "SELECT * FROM produk
							 		WHERE diskon_produk > 0
						  		  	$kat
							 		GROUP BY id_produk order by diskon_produk desc
									LIMIT 9";
				}
					elseif($urut=='produk_lihat'){
					 $title = "Produk Paling Banyak Dilihat";
				     $sqlproduk = "SELECT * FROM produk
							 		WHERE viewcounter_produk > 0
						  		  	$kat
							 		GROUP BY id_produk order by viewcounter_produk desc
									LIMIT 9";
				}
					elseif($urut=='produk_terbaru'){
					 $title = "Produk Terbaru";
				     $sqlproduk = "SELECT * FROM produk a, detailproduk b 
					 				WHERE a.id_produk=b.id_produk
					 				$kat
									GROUP BY b.id_produk
									order by tanggal_detailproduk desc
									LIMIT 9";
				}
			}
			elseif (isset($_GET['size'])){
				$size=$_GET['size'];
				$title = "Produk Size $size";
				$sqlproduk = "SELECT * FROM produk a, detailproduk b, ukuran c
							  WHERE a.id_produk=b.id_produk
							  AND b.id_ukuran=c.id_ukuran
							  AND c.nama_ukuran='$size'
							  GROUP BY a.id_produk
							  LIMIT 9";
			}
			elseif (isset($_GET['warna'])){
				$warna=$_GET['warna'];
				$title = "Produk Warna $warna";
				$sqlproduk = "SELECT * FROM produk a, detailproduk b, warna c
							  WHERE a.id_produk=b.id_produk
							  AND b.id_warna=c.id_warna
							  AND c.nama_warna='$warna'
							  GROUP BY a.id_produk
							  LIMIT 9";
			}
			elseif (isset($_GET['harga'])){
				$harga=$_GET['harga'];
				$title = "Produk Harga Rp.".substr($harga,14,7)."an";
				$sqlproduk = "SELECT * FROM produk a, detailproduk b
							  WHERE a.id_produk=b.id_produk
							  AND a.$harga
							  GROUP BY a.id_produk
							  LIMIT 9";
			}
			else
			{
				$title = "Produk";
				$sqlproduk = "SELECT * FROM produk a, detailproduk b 
					 		  WHERE a.id_produk=b.id_produk
					 		  $sqlcari
							  GROUP BY b.id_produk
							  order by nama_produk
							  LIMIT $posisi, $batas";
			}
					  
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
   	<div class="prod_box">
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
<div class="pagination">
    <?php

		// Link ke halaman sebelumnya (previous)
	if($halaman > 1){
		$prev=$halaman-1;
		echo "<span class=prevnext><a href='$_SERVER[PHP_SELF]?halaman=$prev'>Prev</a></span> ";
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
	  $angka .= "<a href='$_SERVER[PHP_SELF]?halaman=$i'>$i</a> ";
	}
		
	$angka .= "<span class=current>$halaman</span> ";
	for($i=$halaman+1;$i<($halaman+3);$i++)
	{
	 if ($i > $jmlhal) 
		  break;
  	$angka .= "<a href='$_SERVER[PHP_SELF]?halaman=$i'>$i</a> ";
	}
	
	$angka .= ($halaman+2<$jmlhal ? " ...  
  	<a href='$_SERVER[PHP_SELF]?halaman=$jmlhal'>$jmlhal</a> " : " ");
	
	echo "$angka ";
	
	// Link kehalaman berikutnya (Next)
	if($halaman < $jmlhal){
		$next=$halaman+1;
		echo "<span class=prevnext><a href='$_SERVER[PHP_SELF]?halaman=$next'>Next</a></span>";
	}
	else{ 
		echo "<span class=disabled>Next</span>";
	}
	?>
	</div>
