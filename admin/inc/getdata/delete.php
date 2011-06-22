<?php
session_start();
include "../../../fungsi/koneksi.php";

if(isset($_GET['id']))
	$id = addslashes($_GET['id']);
	
if(isset($_GET['table']))
{
	switch($_GET['table']){
		case "kategori"	: $table = "kategori"; $field = "id_kategori = $id";break;
		case "member"	: $table = "member"; $field = "id_member = $id";break;
		case "admin"	: $table = "admin";break;
		case "kota"	: $table = "kota"; $field = "id_kota = $id";break;
		case "provinsi"	: $table = "provinsi"; $field = "id_provinsi = $id";break;
		case "produk"	: $table = "produk"; $field = "id_produk = $id";break;
		case "detailproduk"	: $table = "detailproduk"; $field = "id_detailproduk = $id";break;
		case "warna"	: $table = "warna"; $field = "id_warna = $id";break;
		case "ukuran"	: $table = "ukuran"; $field = "id_ukuran = $id";break;
		case "testiproduk"	: $table = "testi_produk"; $field = "id_testi = $id";break;
		case "rekening"	: $table = "rekening"; $field = "id_rekening = $id";break;
		case "ongkir"	: $table = "ongkir"; $field = "id_ongkir = $id";break;
		case "laporan"	: $table = "detailpembelian"; $field = "id_detailpembelian = $id";break;
		case "jenispengiriman"	: $table = "jenispengiriman"; $field = "id_jenispengiriman = $id";break;
		case "jasapengiriman"	: $table = "jasapengiriman"; $field = "id_jasapengiriman = $id";break;
		case "hubungi"	: $table = "hubungi"; $field = "id_hubungi = $id";break;
	}
	
	if($table == 'admin'){
	$cek=mysql_num_rows(mysql_query("select * from admin where id_admin='$id' AND email_admin='$_SESSION[email_admin]'"));
  		if($cek==0)
  		{
  			mysql_query("DELETE FROM admin WHERE id_admin='$id'")or die(mysql_error());
 		 }
 		else
 		 {
			echo "<script>pesan('ADMIN dengan id $id Sedang Aktif Tidak bisa dihapus','Peringatan');</script>";	
 		 }	
	}
	elseif($table == 'produk'){
			mysql_query("DELETE FROM detailproduk WHERE id_produk = $id");
			mysql_query("DELETE FROM produk WHERE id_produk = $id");
		$queryunlink = mysql_query("SELECT * FROM gambar WHERE id_produk = '$id'");
  		while ($dataunlink = mysql_fetch_array($queryunlink))
		{
	  		$imageunlink = "../images/product/".$dataunlink['nama_gambar'];
	  		unlink($imageunlink);
	  		mysql_query("DELETE FROM gambar WHERE id_gambar = '$dataunlink[id_gambar]'");
		}
	}
	elseif ($table != 'admin'){
	mysql_query("DELETE FROM $table
				WHERE $field") or die(mysql_error());
	
	if($table == 'provinsi'){
			mysql_query("DELETE FROM kota WHERE id_provinsi = $id");
	}
}
}
			
?>