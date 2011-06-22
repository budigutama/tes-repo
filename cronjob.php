<?php
include "fungsi/koneksi.php";
include "fungsi/function.php";

$qupdate1 = mysql_query("SELECT * FROM pembelian WHERE (TIMEDIFF(now(),tgl_beli) > '00:03:00' AND status = 'pesan') OR (kirim_id='')");
while($ddel = mysql_fetch_array($qupdate1 )){

    emailhapuspembelian($ddel['id_detailpembelian']);
    mysql_query("DELETE FROM detail_pembelian WHERE idpembelian= '$ddel[id_pembelian]'");
    mysql_query("DELETE FROM pembelian WHERE id_pembelian= '$ddel[id_pembelian]'");
}
?>