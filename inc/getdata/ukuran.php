<?php
include "../../fungsi/koneksi.php";

if(isset($_GET['idb']))
	$idb = addslashes($_GET['idb']);
	
if(isset($_GET['idw']))
	$idw = addslashes($_GET['idw']);

if(isset($_GET['idw'])){
	if($idw != '-'){
		$qukuran = mysql_query("SELECT * FROM detailproduk as a, ukuran as b
							  WHERE a.id_ukuran = b.id_ukuran
							  AND a.id_produk = $idb
							  AND a.id_warna = $idw");
		echo "<option value='-'>-- Pilih Ukuran --</option>";
		while($dukuran = mysql_fetch_array($qukuran)){
			echo "<option value='$dukuran[id_ukuran]'>$dukuran[nama_ukuran]</option>";	
		}
	}
	else{
		echo "<option value='-'>-- Pilih Ukuran --</option>";
	}
}
else{
		$qukuran = mysql_query("SELECT * FROM detailproduk as a, ukuran as b
							  WHERE a.id_ukuran = b.id_ukuran
							  AND a.id_produk = $idb");
		echo "<option value='-'>-- Pilih Ukuran --</option>";
		while($dukuran = mysql_fetch_array($qukuran)){
			echo "<option value='$dukuran[id_ukuran]'>$dukuran[nama_ukuran]</option>";	
		}	
}
?>