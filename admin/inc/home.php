<h2>Selamat Datang <?php echo $_SESSION['nama_admin']; ?></h2>

<?php
$qnoti=mysql_query("SELECT count(*) total FROM pembelian WHERE status='bayar'");
$d=mysql_fetch_array($qnoti);
if ($d['total']!=0){ echo "
<div class=notif>
<a href=?page=datatransaksi><img src=images/noti.png height=20px> Anda Mempunyai <b>$d[total]</b> Transaksi yang belum dikonfirmasi</a> 
</div>";
}
$h=mysql_query("SELECT count(*) noti FROM hubungi WHERE status_hubungi='0'");
$dh=mysql_fetch_array($h);
if ($dh['noti']!=0){ echo "
<div class=notif>
<a href=?page=kontak><img src=images/comment.png height=20px> Anda Mempunyai <b>$dh[noti]</b> pesan kontak yang belum dibaca</a>
</div>"; 
}
?>
<img src="images/profil.jpg" width="800" align="middle" style="padding-top:20px; padding-bottom:20px"/>