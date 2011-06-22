<?php
if (isset($_SESSION['id_member'])) {
?>
<ul id="qm0" class="qmmc">
<?php
$query="FROM pembelian WHERE id_member=$_SESSION[id_member]";
//$state=mysql_query("SELECT status FROM pembelian WHERE (status!='terima' AND status!='bayar') GROUP BY status");
//$st="['konfirmasi','kirim','pesan']";
$x=0;
while ($x<3){
$x++;
  if ($x==1){$st="pesan";}
  else if ($x==2){$st="konfirmasi";}
  else if ($x==3){$st="kirim"; }
$d=mysql_fetch_array(mysql_query("SELECT count(*) total $query AND status='$st'"));
	if ($d['total']!=0){$noti="$st.png";$jml="<span class='notfjml'>$d[total]</span>";} 
	else {$noti=$st."2.png";$jml="";} ?>
	<li><a class="qmparent" href="?page=history">
	<img src="images/<?php echo $noti;?>"/><?php echo $jml;?></a>
		<ul>
			<li><strong><?php echo "$d[total] Pesanan Di$st";?></strong></li>
			<?php 
			$list=mysql_query("SELECT * $query AND status='$st'"); 
			while ($li=mysql_fetch_array($list)){ echo "
			<li><a href=?page=view&idn=$li[id_pembelian]>
			Tanggal ".tgl_indo($li['tgl_beli'])." ( ID : $li[id_pembelian] )</a></li>";
			}?>
		</ul>
	</li>
<?php } ?>	
	<li class="qmclear">&nbsp;</li>
</ul>
<script type="text/javascript">qm_create(0,false,0,500,false,false,false,false,false);</script>
<?php } ?>	
