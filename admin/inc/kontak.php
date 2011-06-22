<?php
if(isset($_POST['kirim'])){
	email_hubungi($_POST['email'],$_POST['nama'],$_POST['isi_balasan']);
	echo "<script>window.location = '?page=kontak'</script>";
}
?>
<h2>Kontak Us</h2> 

    <?php
	if($_GET['act'] == 'view'){
	$id_hubungi = addslashes($_GET['idk']);
	mysql_query("UPDATE hubungi SET status_hubungi='1' WHERE id_hubungi='$id_hubungi'");
	$qkontak = mysql_query("SELECT * FROM hubungi WHERE id_hubungi = '$id_hubungi'");
	$dkontak = mysql_fetch_array($qkontak);
	?>
    <form action="" method="post" class="niceform">
        <table>
            <?php
			if (isset($_POST['balas'])){?>
        	<tr>
            	<td colspan="2"><label for="harga">Kirim Ke :</label>
            	<?php echo "$dkontak[nama_hubungi] ( $dkontak[email_hubungi] )"; ?></td>
            </tr>
			<tr>
            	<td colspan="2">
                <textarea name="isi_balasan" rows="6" cols="80"><?php echo $dkontak['isi_hubungi']; ?> <br /></textarea>
                <input type="hidden" name="email" value="<?php echo $dkontak['email_hubungi']; ?>" />
                <input type="hidden" name="nama" value="<?php echo $dkontak['nama_hubungi']; ?>" />
                </td>
            </tr>
            <tr>
            	<td colspan="2" align="center"><input type="submit" name="kirim" value="Kirim" />
                <input type="reset" name="reset" value="Kembali" onClick="window.location = '?page=kontak';" /></td>
            </tr>
            <?php }
			else { ?>
        	<tr>
            	<td><label for="simbol">Nama :</label></td>
            	<td><input type="text" readonly="readonly" value="<?php echo $dkontak['nama_hubungi']; ?>" /></td>
            </tr>
        	<tr>
            	<td><label for="harga">Tanggal :</label></td>
            	<td><input type="text" readonly="readonly" value="<?php echo tgl_indo($dkontak['tanggal_hubungi']); ?>" /></td>
            </tr>
        	<tr>
            	<td><label for="harga">Email :</label></td>
            	<td><input type="text" readonly="readonly" value="<?php echo $dkontak['email_hubungi']; ?>" /></td>
            </tr>
        	<tr>
            	<td><label for="deskripsi">Isi Komentar :</label></td>
            	<td><textarea name="isi" rows="5" cols="60" readonly="readonly"><?php echo $dkontak['isi_hubungi']; ?></textarea></td>
            </tr>
            <tr>
            	<td colspan="2" align="center"><input type="submit" name="balas" value="Balas" />
                <input type="reset" name="reset" value="Kembali" onClick="window.location = '?page=kontak';" /></td>
            </tr>
            <?php } ?>
        </table>
	</form>
    <?php
	}
else
{
$batas   = 10;
if(isset($_GET['halaman']))
	$halaman = $_GET['halaman'];
	
if(empty($halaman)){
	$posisi  = 0;
	$halaman = 1;
}
else{
	$posisi = ($halaman-1) * $batas;
}
?>
	<table width="100%" style=" margin-top:10px;">
    <tr>
    <td align="right">&nbsp;
    </td>
    <td align="right" width="70%">
    <form method="post" action="">
    	<select name="type" class="newsletter_input" style="height:28px; padding-top:2px;">
        	<option value="0">Semua</option>
        	<option value="1" <?php if(isset($_POST['type'])){ echo($_POST['type'] == 1)?"selected":""; } ?>>Nama </option>
            <option value="2" <?php if(isset($_POST['type'])){ echo($_POST['type'] == 2)?"selected":""; } ?>>Tanggal</option>
        </select>
    	<input type="text" name="textcari" class="newsletter_input" value="<?php if(isset($_POST['textcari'])){ echo $_POST['textcari']; } else { echo "Kata Kunci..."; }?>" onBlur="if(this.value=='') this.value='Kata Kunci...';" onFocus="if(this.value=='Kata Kunci...') this.value='';" /><input type="image" src="../images/search.png" name="cari" style="margin-bottom:-4px;"/>
    </form>
    </td></tr>
    </table>
    <?php
	if(isset($_POST['type'])){
		$type = addslashes($_POST['type']);
		if($type == 0)
			$sqlquery = "";
		elseif($type == 1)
			$sqlquery = "AND nama_hubungi LIKE '%$_POST[textcari]%'";
		elseif($type == 2)
			$sqlquery = "AND tanggal_hubungi LIKE '%$_POST[textcari]%'";
	}
	else{
		$sqlquery = "";	
	}
?>         
              
<table width="592" id="rounded-corner">
    <thead>
    	<tr>
        	<th width="51" class="rounded-company" scope="col">No</th>
            <th width="444" class="rounded" scope="col">Tanggal</th>
            <th width="444" class="rounded" scope="col">Nama</th>
            <th width="444" class="rounded" scope="col">Email</th>
            <th width="32" class="rounded" scope="col">Status</th>
            <th width="32" class="rounded" scope="col">View</th>
            <th width="45" class="rounded-q4" scope="col">Hapus</th>
        </tr>
    </thead>
        <tfoot>
    </tfoot>
    <tbody>
    	<?php
		$no = 0;
		$qkontak = mysql_query("SELECT * FROM hubungi
								  WHERE 1
								  $sqlquery
								  LIMIT $posisi,$batas");
		$kolom=1;
		$i=0;
		$no = $posisi+1;
		while($dkontak = mysql_fetch_array($qkontak)){
			if ($i >= $kolom){
				echo "<tr class='row$dkontak[id_hubungi]'>";
			}
		?>
        	<td><?php echo $no; ?></td>
            <td><?php echo tgl_indo($dkontak['tanggal_hubungi']); ?></td>
            <td><?php echo $dkontak['nama_hubungi']; ?></td>
            <td><?php echo $dkontak['email_hubungi']; ?></td>
            <td><?php
					if($dkontak['status_hubungi'] == 0){
                    	echo "<img src='images/publish_t.png' border='0' alt='' title='Belum dibaca' />";
					}
					elseif($dkontak['status_hubungi'] == 1){
                    	echo "<img src='images/publish_y.png' border='0' alt='' title='Sudah dibaca' />";
					}
					?>
            </td>
            <td><a href="?page=kontak&act=view&idk=<?php echo $dkontak['id_hubungi']; ?>"><img src="images/user_edit.png" alt="" title="" border="0" /></a></td>
            <td width="45">
            	<a href="<?php echo $dkontak['id_hubungi']; ?>" id="hubungi" class="ask">
                	<img src="images/trash.png" alt="" title="" border="0" />
                </a>
            </td>
        <?php
		$i++;
		$no++;
			if($i >= $kolom){
				echo "</tr>";	
			}
		}
		?>
    </tbody>
</table>
	<div class="pagination">
	<?php
	$tampil2 = mysql_query("SELECT * FROM hubungi
							WHERE 1
							$sqlquery");
	$jmldata = mysql_num_rows($tampil2);
	$jmlhal  = ceil($jmldata/$batas);
		
	echo "<div class=paging>";
	// Link ke halaman sebelumnya (previous)
	if($halaman > 1){
		$prev=$halaman-1;
		echo "<span class=prevnext><a href='$_SERVER[PHP_SELF]?page=kontak&halaman=$prev'>Prev</a></span> ";
	}
	else{ 
		echo "<span class=disabled>Prev</span> ";
	}
	
	// Tampilkan link halaman 1,2,3 ...
	$angka=($halaman > 3 ? " ... " : " ");
	for($i=$halaman-2;$i<$halaman;$i++)
	{
	  if ($i < 1) 
		  continue;
	  $angka .= "<a href='$_SERVER[PHP_SELF]?page=kontak&halaman=$i'>$i</a> ";
	}
	
	$angka .= "<span class=current>$halaman</span> ";
	for($i=$halaman+1;$i<($halaman+3);$i++)
	{
	  if ($i > $jmlhal) 
		  break;
	  $angka .= "<a href='$_SERVER[PHP_SELF]?page=kontak&halaman=$i'>$i</a> ";
	}
	
	$angka .= ($halaman+2<$jmlhal ? " ...  
			  <a href='$_SERVER[PHP_SELF]?page=kontak&halaman=$jmlhal'>$jmlhal</a> " : " ");
	
	echo "$angka ";
	
	// Link kehalaman berikutnya (Next)
	if($halaman < $jmlhal){
		$next=$halaman+1;
		echo "<span class=prevnext><a href='$_SERVER[PHP_SELF]?page=kontak&halaman=$next'>Next</a></span>";
	}
	else{ 
		echo "<span class=disabled>Next</span>";
	}
	
	echo "</div>";
}
	?>
     
     <h2>&nbsp;</h2>
