<?php
include "../../../fungsi/koneksi.php";

$id = addslashes($_GET['id']);
$page = $_GET['page'];

if($page == "member"){
	$qcek = mysql_query("SELECT * FROM member WHERE id_member = $id");
	$dcek = mysql_fetch_array($qcek);
	if($dcek['status_member'] == 1){
	mysql_query("UPDATE member SET status_member = '0'
				WHERE id_member = $id") or die(mysql_error());
	}
	elseif($dcek['status_member'] == 0){
	mysql_query("UPDATE member SET status_member = '1'
				WHERE id_member = $id") or die(mysql_error());
	}	
}
elseif($page == "testiproduk"){
	$qcek = mysql_query("SELECT * FROM testi_produk WHERE id_testi = $id");
	$dcek = mysql_fetch_array($qcek);
	if($dcek['status_testi'] == 0){
		mysql_query("UPDATE testi_produk SET status_testi = '1'
					WHERE id_testi = $id") or die(mysql_error());
	}
	elseif($dcek['status_testi'] == 1){
		mysql_query("UPDATE testi_produk SET status_testi = '0'
					WHERE id_testi = $id") or die(mysql_error());
	}
}
?>