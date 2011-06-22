<?php
	header("Cache-Control: no-cache, no-store, must-validate");
	$prov=$_GET['prov'];
	include("config.php");
	$sql="SELECT * FROM kota WHERE id_provinsi='$prov'";
	$res=mysql_query($sql);
	while($kota=mysql_fetch_array($res)){
		echo "$kota[id_kota];$kota[nama]|";
	}
?>