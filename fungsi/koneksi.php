<?php
	$host = "localhost";
	$username = "root";
	$password = "";
	$database = "elitezdb";
mysql_connect($host, $username, $password) or die("koneksi gagal.");
mysql_select_db($database) or die("database tidak ditemukan.");
?>
