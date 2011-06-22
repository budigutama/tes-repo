<script type="text/javascript" language="javascript">
function sizepack() {
  var selected = $("#kategori").val();
  document.getElementById("HeaderSpek").innerHTML="___________Spesifikasi Ukuran___________";
  if (selected =="TSHIRT" || selected =="JAKET" || selected =="SWEATER"){
  	document.getElementById("lSpek1").innerHTML="Lebar Bahu :";
	document.getElementById("tSpek1").innerHTML="<input type=text name=spek1 id=spek1 size=5/> Cm";
  	document.getElementById("lSpek2").innerHTML="Lingkar Dada :";
	document.getElementById("tSpek2").innerHTML="<input type=text name=spek2 id=spek2 size=5/> Cm";
  	document.getElementById("lSpek3").innerHTML="Lingkar Pinggang :";
	document.getElementById("tSpek3").innerHTML="<input type=text name=spek3 id=spek3 size=5/> Cm";
  	document.getElementById("lSpek4").innerHTML="Tinggi :";
	document.getElementById("tSpek4").innerHTML="<input type=text name=spek4 id=4 size=5/> Cm";
  }
  else if (selected =="CELANA"){
  	document.getElementById("lSpek1").innerHTML="Lingkar Pinggang :";
	document.getElementById("tSpek1").innerHTML="<input type=text name=spek1 id=spek1 size=5/> Cm";
  	document.getElementById("lSpek2").innerHTML="Panjang :";
	document.getElementById("tSpek2").innerHTML="<input type=text name=spek2 id=spek2 size=5/> Cm";
  	document.getElementById("lSpek3").innerHTML="";
	document.getElementById("tSpek3").innerHTML="";
  	document.getElementById("lSpek4").innerHTML="";
	document.getElementById("tSpek4").innerHTML="";
  }
  else if (selected =="SENDAL" || selected =="SEPATU"){
  	document.getElementById("lSpek1").innerHTML="Panjang :";
	document.getElementById("tSpek1").innerHTML="<input type=text name=spek1 id=spek1 size=5/> Cm";
  	document.getElementById("lSpek2").innerHTML="Lebar :";
	document.getElementById("tSpek2").innerHTML="<input type=text name=spek2 id=spek2 size=5/> Cm";
  	document.getElementById("lSpek3").innerHTML="";
	document.getElementById("tSpek3").innerHTML="";
  	document.getElementById("lSpek4").innerHTML="";
	document.getElementById("tSpek4").innerHTML="";
  }
  else{
  	document.getElementById("lSpek1").innerHTML="";
	document.getElementById("tSpek1").innerHTML="";
  	document.getElementById("lSpek2").innerHTML="";
	document.getElementById("tSpek2").innerHTML="";
  	document.getElementById("lSpek3").innerHTML="";
	document.getElementById("tSpek3").innerHTML="";
  	document.getElementById("lSpek4").innerHTML="";
	document.getElementById("tSpek4").innerHTML="";
    document.getElementById("HeaderSpek").innerHTML="";
  }
};
</script>

<?php 
function desc($idu){
$cariukuran=mysql_query("SELECT * FROM ukuran WHERE id_ukuran=$idu");
$ukuran=mysql_fetch_array($cariukuran);
$kategori=$ukuran['2'];
$s1=$ukuran['3'];
$s2=$ukuran['4'];
$s3=$ukuran['5'];
$s4=$ukuran['6'];
	if ($kategori=="TSHIRT" or $kategori=="JAKET" or $kategori=="SWEATER"){
		$deskripsi="Lebar Bahu : $s1 <br>
					Lingkar Dada :$s2 <br>
					Lingkar Pinggang : $s3<br>
					Tinggi : $s4. ";
	}
	else if ($kategori=="CELANA"){
		$deskripsi="Lingkar Pinggang : $s1<br>
					Panjang : $s2 . ";
	}
	else if ($kategori=="SEPATU" OR $kategori=="SENDAL"){
		$deskripsi="Panjang : $s1<br>
					Lebar : $s2 . ";
	}
	else{
		$deskripsi="Proporsional Size";
	}
	return $deskripsi;
} 

function spesifikasiview($kat){
$kategori=strtoupper($kat);
$carispek=mysql_query("SELECT * FROM ukuran WHERE kategori='$kat'");?>
	<span style="font-size:14px; color:#000000; padding:1px;"><b><center>
	Spesifikasi Ukuran <?php echo $kat;?>
	</center></b></span>
	<table width="280" cellspacing="0" style="font-size:12px">
<?php 
	if ($kategori=="TSHIRT" or $kategori=="JAKET" or $kategori=="SWEATER"){?>
	  <tr style="font-weight:bold" align="center" bgcolor="#66FFCC">
	  	<td>Ukuran</td>
	  	<td>Lebar Bahu</td>
	  	<td>Lingkar Dada</td>
	  	<td>Lingkar Pinggang</td>
	  	<td width="45">Tinggi</td>
	  </tr>
	  <?php 
	  $c=0;
	  while ($spek=mysql_fetch_array($carispek)){
	  $c++;
	  if($c%2)
		echo "<tr style='background-color:#cccccc' align='center'>";
	  else
		echo "<tr style='background-color:#eeeeee' align='center'>";
	  ?>
	  	<td><b><?php echo $spek['1'];?></b></td>
	  	<td><?php echo $spek['3'];?> Cm</td>
	  	<td><?php echo $spek['4'];?> Cm</td>
	  	<td><?php echo $spek['5'];?> Cm</td>
	  	<td><?php echo $spek['6'];?> Cm</td>
	  </tr>
	  <?php } ?> 
	</table>
<?php }
	else if ($kategori=="CELANA"){?>
	  <tr style="font-weight:bold" align="center" bgcolor="#66FFCC">
	  	<td>Ukuran</td>
	  	<td>Lingkar Pinggang</td>
	  	<td>Panjang</td>
	  </tr>
	  <?php 
	  $c=0;
	  while ($spek=mysql_fetch_array($carispek)){
	  $c++;
	  if($c%2)
		echo "<tr style='background-color:#cccccc' align='center'>";
	  else
		echo "<tr style='background-color:#eeeeee' align='center'>";
	  ?>
	  	<td><b><?php echo $spek['1'];?></b></td>
	  	<td><?php echo $spek['3'];?> Cm</td>
	  	<td><?php echo $spek['4'];?> Cm</td>
	  </tr>
	  <?php } ?> 
	</table>
<?php }
	else if ($kategori=="SEPATU" OR $kategori=="SENDAL"){?>
	  <tr style="font-weight:bold" align="center" bgcolor="#66FFCC">
	  	<td>Ukuran</td>
	  	<td>Panjang</td>
	  	<td>Lebar</td>
	  </tr>
	  <?php 
	  $c=0;
	  while ($spek=mysql_fetch_array($carispek)){
	  $c++;
	  if($c%2)
		echo "<tr style='background-color:#cccccc' align='center'>";
	  else
		echo "<tr style='background-color:#eeeeee' align='center'>";
	  ?>
	  	<td><b><?php echo $spek['1'];?></b></td>
	  	<td><?php echo $spek['3'];?> Cm</td>
	  	<td><?php echo $spek['4'];?> Cm</td>
	  </tr>
	  <?php } ?> 
	</table>
<?php }
	else {?>
	  <tr style="font-weight:bold" align="center" bgcolor="#66FFCC">
	  	<td>Ukuran</td>
	  	<td>Keterangan</td>
	  </tr>
	  <?php 
	  $c=0;
	  while ($spek=mysql_fetch_array($carispek)){
	  $c++;
	  if($c%2)
		echo "<tr style='background-color:#cccccc' align='center'>";
	  else
		echo "<tr style='background-color:#eeeeee' align='center'>";
	  ?>
	  	<td><b><?php echo $spek['1'];?></b></td>
	  	<td>Proporsional</td>
	  </tr>
	  <?php } ?> 
	</table>
<?php }?>
* Spesifikasi ukuran yang ditampilkan adalah untuk laki-laki<br />
* Spesifikasi ukuran untuk perempuan adalah spesifikasi ukuran laki-laki dikurangi 2cm

<?php }?>
