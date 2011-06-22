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
		$qberat = mysql_query("SELECT * FROM detailproduk
							 WHERE id_produk = $idb
							 AND id_warna = $idw
							 AND id_ukuran = $idu") or die(mysql_error());
		$dberat = mysql_fetch_array($qberat);
			echo $dberat['berat_detailproduk'];	
		}
		elseif($idu == '-'){
			$qberat = mysql_query("SELECT * FROM detailproduk
								 WHERE id_produk = $idb
								 AND id_warna = $idw");
			$dberat = mysql_fetch_array($qberat);
				echo $dberat['berat_detailproduk'];	
		}
	}
	elseif($idw != '-'){
		if(!isset($_GET['idu'])){
			$qberat = mysql_query("SELECT * FROM detailproduk
								 WHERE id_produk = $idb
								 AND id_warna = $idw");
			$dberat = mysql_fetch_array($qberat);
				echo $dberat['berat_detailproduk'];	
		}
	}
	else{
		$qberat = mysql_query("SELECT * FROM detailproduk
							 WHERE id_produk = $idb");
		$dberat = mysql_fetch_array($qberat);
			echo $dberat['berat_detailproduk'];
	}
}
elseif($idw == 'undefined'){
	if($idu != '-'){
	$qberat = mysql_query("SELECT * FROM detailproduk
						 WHERE id_produk = $idb
						 AND id_ukuran = $idu");
	$dberat = mysql_fetch_array($qberat);
		echo $dberat['berat_detailproduk'];	
	}
	else{
	$qberat = mysql_query("SELECT * FROM detailproduk WHERE id_produk = $idb");
	$dberat = mysql_fetch_array($qberat);
		echo $dberat['berat_detailproduk'];
	}
}
else{
	$qberat = mysql_query("SELECT * FROM detailproduk WHERE id_produk = $idb");
	$dberat = mysql_fetch_array($qberat);
		echo $dberat['berat_detailproduk'];	
}
?>