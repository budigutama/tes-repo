<?php
			$sqldate = "";
			if(isset($_POST['cariproduk'])){
				$sqlkind = "";
				if(!empty($_POST['kategori'])){
					$sqlkind = " AND e.nama_kategori = '$_POST[kategori]'";
					$kat="Kategori $_POST[kategori]";
				}
				else{
					$kat="Semua Kategori";
				}
				echo "<h2>Laporan Data Produk $kat</h2>";
				if(!empty($_POST['tahun']) && !empty($_POST['bulan'])){
					$tahun = addslashes($_POST['tahun']);
					$bulan = addslashes($_POST['bulan']);
					$sqldate = "AND YEAR(tanggal_detailproduk) = '$tahun'
								AND MONTH(tanggal_detailproduk) = '$bulan'";
				$tampil="Bulan ".getBulan($bulan)." ".$tahun."";
				echo "<h3>$tampil</h3>";
				}
				$sqldate = $sqldate.$sqlkind;
			}

	$batas   = 10;
	if(isset($_GET['halaman']))
		$halaman = $_GET['halaman'];
		
	if(empty($halaman)){
		$posisi  = 0;
		$halaman = 1;
	}
	else{
		$posisi = ($halaman-1) * $batas;
	}
	?>
	<table width="592" id="rounded-corner">
		<thead>
			<tr>
				<th width="40" class="rounded-company" scope="col">No</th>
				<th width="50" class="rounded" scope="col">Id</th>
				<th width="80" class="rounded" scope="col">Tanggal Release</th>
				<th width="80" class="rounded" scope="col">Kategori</th>
				<th width="120" class="rounded" scope="col">Nama Produk</th>
				<th width="70" class="rounded" scope="col">Size</th>
				<th width="60" class="rounded" scope="col">Warna</th>
				<th width="60" class="rounded" scope="col">Diskon</th>
				<th width="60" class="rounded" scope="col">Stok</th>
				<th width="120" class="rounded" scope="col">Harga</th>
				<th width="80" class="rounded" scope="col">Gambar</th>
			</tr>
		</thead>
		<tfoot>
		</tfoot>
		<tbody>
			<?php
			$no = 0;
			$qlapproduk = mysql_query("SELECT *,b.id_produk prod FROM detailproduk a, produk b, ukuran c, warna d, Kategori e,gambar f
				  WHERE b.id_produk=a.id_produk
				  AND c.id_ukuran=a.id_ukuran
				  AND d.id_warna=a.id_warna
				  AND e.id_kategori=b.id_kategori
				  AND b.id_produk=f.id_produk
				  $sqldate
				  GROUP BY a.id_detailproduk
				  Order BY tanggal_detailproduk DESC
				  LIMIT $posisi,$batas") or die(mysql_error());
			$kolom=1;
			$i=0;
			$no = $posisi+1;
			while($dlapproduk = mysql_fetch_array($qlapproduk)){
				if ($i >= $kolom){
					echo "<tr class='row$dlapproduk[id_pembelian]'>";
				}
			?>
				<td align="center"><?php echo $no; ?></td>
				<td align="center"><?php echo $dlapproduk['prod']; ?></td>
				<td><?php echo tgl_indo($dlapproduk['tanggal_detailproduk']); ?></td>
				<td><?php echo $dlapproduk['nama_kategori']; ?></td>
				<td><?php echo $dlapproduk['nama_produk']; ?></td>
				<td><?php echo $dlapproduk['nama_ukuran']; ?></td>
				<td><?php echo $dlapproduk['nama_warna']; ?></td>
				<td><?php echo $dlapproduk['diskon_produk']; ?></td>
				<td><?php echo $dlapproduk['stok_detailproduk']; ?> Pcs</td>
				<td>Rp. <?php echo number_format($dlapproduk['harga_produk'],'2','.',','); ?></td>
				<td><img src="../images/product/<?php echo $dlapproduk['nama_gambar'] ?>" height="80px" width="80px" border="0" /></td>
			<?php
			$i++;
			$no++;
				if($i >= $kolom){
					echo "</tr>";	
				}
			}
			?>
		</tbody>
	</table>
	<div class="pagination">
	<?php
	$perintah = "SELECT * FROM detailproduk a, produk b, kategori e
				 WHERE a.id_produk=b.id_produk
				 AND b.id_kategori=e.id_kategori
				 $sqldate";
	$tampil2 = mysql_query($perintah);
	$jmldata = mysql_num_rows($tampil2);
	$jmlhal  = ceil($jmldata/$batas);
			
	echo "<div class=paging>";
	// Link ke halaman sebelumnya (previous)
	if($halaman > 1){
		$prev=$halaman-1;
		echo "<span class=prevnext><a href='$_SERVER[PHP_SELF]?page=laporanproduk&halaman=$prev'>Prev</a></span> ";
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
	  $angka .= "<a href='$_SERVER[PHP_SELF]?page=laporanproduk&halaman=$i'>$i</a> ";
	}
		
	$angka .= "<span class=current>$halaman</span> ";
	for($i=$halaman+1;$i<($halaman+3);$i++)
	{
	 if ($i > $jmlhal) 
		  break;
  	$angka .= "<a href='$_SERVER[PHP_SELF]?page=laporanproduk&halaman=$i'>$i</a> ";
	}
	
	$angka .= ($halaman+2<$jmlhal ? " ...  
  	<a href='$_SERVER[PHP_SELF]?page=laporanproduk&halaman=$jmlhal'>$jmlhal</a> " : " ");
	
	echo "$angka ";
	
	// Link kehalaman berikutnya (Next)
	if($halaman < $jmlhal){
		$next=$halaman+1;
		echo "<span class=prevnext><a href='$_SERVER[PHP_SELF]?page=laporanproduk&halaman=$next'>Next</a></span>";
	}
	else{ 
		echo "<span class=disabled>Next</span>";
	}
		
	echo "</div>";
?>
<h2>&nbsp;</h2>
<form method="post" action="laporan/laporan_produk.php" target="_blank">
	<input type="hidden" name="cmd" value="<?php echo $sqldate; ?>" />
    <input type="hidden" name="tampil" value="<?php echo $tampil; ?>" />
    <input type="hidden" name="kat" value="<?php echo $kat; ?>" />
	<input type="image" src="images/cetakpdf.png" />
</form>
