<?php
include "../../fungsi/koneksi.php";

if(isset($_GET['idb']))
	$idb = addslashes($_GET['idb']);
	
if(isset($_GET['idw']))
	$idw = addslashes($_GET['idw']);

if(isset($_GET['idu']))
	$idu = addslashes($_GET['idu']);

if($idw != 'undefined'){
	if(isset($_GET['idw']) && (isset($_GET['idu']))){
		if(($idw != '-') && ($idu !='-')){
		$qstok = mysql_query("SELECT * FROM detailproduk
							 WHERE id_produk = $idb
							 AND id_warna = $idw
							 AND id_ukuran = $idu") or die(mysql_error());
		$dstok = mysql_fetch_array($qstok);
			echo $dstok['stok_detailproduk'];	
		}
		elseif($idu == '-'){
			$qstok = mysql_query("SELECT SUM(stok_detailproduk) as stok FROM detailproduk
								 WHERE id_produk = $idb
								 AND id_warna = $idw");
			$dstok = mysql_fetch_array($qstok);
				echo $dstok['stok'];	
		}
	}
	elseif($idw != '-'){
		if(!isset($_GET['idu'])){
			$qstok = mysql_query("SELECT SUM(stok_detailproduk) as stok FROM detailproduk
								 WHERE id_produk = $idb
								 AND id_warna = $idw");
			$dstok = mysql_fetch_array($qstok);
				echo $dstok['stok'];	
		}
	}
	else{
		$qstok = mysql_query("SELECT SUM(stok_detailproduk) as stok FROM detailproduk
							 WHERE id_produk = $idb");
		$dstok = mysql_fetch_array($qstok);
			echo $dstok['stok'];
	}
}
elseif($idw == 'undefined'){
	if($idu != '-'){
	$qstok = mysql_query("SELECT * FROM detailproduk
						 WHERE id_produk = $idb
						 AND id_ukuran = $idu");
	$dstok = mysql_fetch_array($qstok);
		echo $dstok['stok_detailproduk'];	
	}
	else{
	$qstok = mysql_query("SELECT SUM(stok_detailproduk) as stok FROM detailproduk WHERE id_produk = $idb");
	$dstok = mysql_fetch_array($qstok);
		echo $dstok['stok'];
	}
}
else{
	$qstok = mysql_query("SELECT SUM(stok_detailproduk) as stok FROM detailproduk WHERE id_produk = $idb");
	$dstok = mysql_fetch_array($qstok);
		echo $dstok['stok'];	
}
?>