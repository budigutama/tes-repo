<?php
			$sqldate = "";
			if(isset($_POST['carihari'])){
			$sqlkind = "";
				if(!empty($_POST['tanggal1']) && !empty($_POST['tanggal2'])){
					list($tanggal1,$bulan1,$tahun1) = explode('/',$_POST['tanggal1']);
					list($tanggal2,$bulan2,$tahun2) = explode('/',$_POST['tanggal2']);
					$tanggal1ex = $tahun1."-".$bulan1."-".$tanggal1;
					$tanggal2ex = $tahun2."-".$bulan2."-".$tanggal2;
					$sqldate = "AND ( DATE(tgl_beli) BETWEEN '$tanggal1ex' AND '$tanggal2ex')";
				}
				if(!empty($_POST['pembayaran_hari'])){
					$sqlkind = " AND f.pembayaran = '$_POST[pembayaran_hari]'";	
				}
				$sqldate = $sqldate.$sqlkind;
				$tampil="Tanggal ".tgl_indo($tanggal1ex)." s.d. ".tgl_indo($tanggal2ex)."";
				$per="Hari";
				echo "<h2>Laporan Penjualan Harian $_POST[pembayaran_hari] </h2>";
				echo "<h3>$tampil</h3>";
			}
			elseif(isset($_POST['caribulan'])){
				$sqlkind = "";
				if(!empty($_POST['tahun']) && !empty($_POST['bulan'])){
					$tahun = addslashes($_POST['tahun']);
					$bulan = addslashes($_POST['bulan']);
					$sqldate = "AND YEAR(tgl_beli) = '$tahun'
								AND MONTH(tgl_beli) = '$bulan'";
				}
				if(!empty($_POST['pembayaran_bulan'])){
					$sqlkind = " AND f.pembayaran = '$_POST[pembayaran_bulan]'";	
				}
				$sqldate = $sqldate.$sqlkind;
				$tampil="Bulan ".getBulan($bulan)." ".$tahun."";
				$per="Bulan";
				echo "<h2>Laporan Penjualan Bulanan $_POST[pembayaran_bulan]</h2>";
				echo "<h3>$tampil</h3>";
			}
			elseif(isset($_POST['caritahun'])){
				$sqlkind = "";
				if(!empty($_POST['tahun'])){
					$tahun = addslashes($_POST['tahun']);
					$sqldate = "AND YEAR(tgl_beli) = '$tahun'";
				}
				if(!empty($_POST['pembayaran'])){
					$sqlkind = " AND f.pembayaran = '$_POST[pembayaran]'";	
				}
				$sqldate = $sqldate.$sqlkind;
				$tampil="Tahun ".$tahun."";
				$per="Tahun";
				echo "<h2>Laporan Penjualan Pertahun $_POST[pembayaran] </h2>";
				echo "<h3>$tampil</h3>";
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
				<th width="100" class="rounded" scope="col">Id Pembelian</th>
				<th width="80" class="rounded" scope="col">Tanggal</th>
				<th width="120" class="rounded" scope="col">Nama Member</th>
				<th width="160" class="rounded" scope="col">Produk</th>
				<th width="70" class="rounded" scope="col">Size</th>
				<th width="60" class="rounded" scope="col">Warna</th>
				<th width="60" class="rounded" scope="col">Qty</th>
				<th width="120" class="rounded" scope="col">Harga Satuan</th>
			</tr>
		</thead>
		<tfoot>
		</tfoot>
		<tbody>
			<?php
			$no = 0;
			$qlapmingguan = mysql_query("SELECT * FROM detail_pembelian a, produk b, detailproduk c, ukuran d, warna e, 
				  pembelian f, member g
				  WHERE a.idpembelian=f.id_pembelian
				  AND a.id_detailproduk=c.id_detailproduk
				  AND f.id_member=g.id_member
				  AND b.id_produk=c.id_produk
				  AND c.id_ukuran=d.id_ukuran
				  AND c.id_warna=e.id_warna
				  AND f.status='terima'
				  $sqldate
				  Order BY id_pembelian DESC
				  LIMIT $posisi,$batas") or die(mysql_error());
			$kolom=1;
			$i=0;
			$no = $posisi+1;
			while($dlapmingguan = mysql_fetch_array($qlapmingguan)){
				if ($i >= $kolom){
					echo "<tr class='row$dlapmingguan[id_pembelian]'>";
				}
			?>
				<td align="center"><?php echo $no; ?></td>
				<td align="center"><?php echo $dlapmingguan['id_pembelian']; ?></td>
				<td><?php echo tgl_indo($dlapmingguan['tgl_beli']); ?></td>
				<td><?php echo $dlapmingguan['nama_member']; ?></td>
				<td><?php echo $dlapmingguan['nama_produk']; ?></td>
				<td><?php echo $dlapmingguan['nama_ukuran']; ?></td>
				<td><?php echo $dlapmingguan['nama_warna']; ?></td>
				<td><?php echo $dlapmingguan['qty']; ?> Pcs</td>
				<td>Rp. <?php echo number_format($dlapmingguan['hargabeli'],'2','.',','); ?></td>
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
	$perintah = "SELECT * FROM detail_pembelian a, pembelian f
				 WHERE a.idpembelian=f.id_pembelian
				 AND status = 'terima' 
				 $sqldate";
	$tampil2 = mysql_query($perintah);
	$jmldata = mysql_num_rows($tampil2);
	$jmlhal  = ceil($jmldata/$batas);
			
	echo "<div class=paging>";
	// Link ke halaman sebelumnya (previous)
	if($halaman > 1){
		$prev=$halaman-1;
		echo "<span class=prevnext><a href='$_SERVER[PHP_SELF]?page=lap_review&halaman=$prev'>Prev</a></span> ";
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
	  $angka .= "<a href='$_SERVER[PHP_SELF]?page=lap_review&halaman=$i'>$i</a> ";
	}
		
	$angka .= "<span class=current>$halaman</span> ";
	for($i=$halaman+1;$i<($halaman+3);$i++)
	{
	 if ($i > $jmlhal) 
		  break;
  	$angka .= "<a href='$_SERVER[PHP_SELF]?page=lap_review&halaman=$i'>$i</a> ";
	}
	
	$angka .= ($halaman+2<$jmlhal ? " ...  
  	<a href='$_SERVER[PHP_SELF]?page=lap_review&halaman=$jmlhal'>$jmlhal</a> " : " ");
	
	echo "$angka ";
	
	// Link kehalaman berikutnya (Next)
	if($halaman < $jmlhal){
		$next=$halaman+1;
		echo "<span class=prevnext><a href='$_SERVER[PHP_SELF]?page=lap_review&halaman=$next'>Next</a></span>";
	}
	else{ 
		echo "<span class=disabled>Next</span>";
	}
		
	echo "</div>";
?>
<h2>&nbsp;</h2>
<form method="post" action="laporan/laporantransaksi.php" target="_blank">
	<input type="hidden" name="cmd" value="<?php echo $sqldate; ?>" />
	<input type="hidden" name="tampil" value="<?php echo $tampil; ?>" />
    <input type="hidden" name="per" value="<?php echo $per; ?>" />
	<input type="image" src="images/cetakpdf.png" />
</form>
