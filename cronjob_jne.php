<?php
include "fungsi/koneksi.php";
include "fungsi/function.php";

$qupdate = mysql_query("SELECT * FROM pembelian WHERE status = 'kirim'");
while($dupdate = mysql_fetch_array($qupdate)){
	get_jnestates($dupdate['kirim_resi']);
}
?>