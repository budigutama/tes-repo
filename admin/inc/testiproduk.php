<h2>Pengolahan Testimoni Produk</h2> 
<?php
if(isset($_POST['balas'])){
mysql_query("INSERT INTO testi_produk VALUES (NULL,'$_POST[idp]','0','$_POST[balasan_testi]','1',now())");
mysql_query("UPDATE testi_produk SET status_testi = '1'
			 WHERE id_testi = $_POST[idtesti]") or die(mysql_error());

?><form name="redirect">
			<input type="hidden" name="redirect2">
		</form>
		<script>
		loading('Balas Testimoni Berhasil', 'Loading');  
		var targetURL="?page=testiproduk"
		var countdownfrom=3
		var currentsecond=document.redirect.redirect2.value=countdownfrom+1
		function countredirect(){
		  if (currentsecond!=1){
			currentsecond-=1
			document.redirect.redirect2.value=currentsecond
		  }
		  else{
			window.location=targetURL
			return
		  }
		  setTimeout("countredirect()",1000)
		}
		countredirect()
		  </script>        
  <?php
}
if(isset($_POST['publish'])){
mysql_query("UPDATE testi_produk SET status_testi = '1'
			 WHERE id_testi = $_POST[idtesti]") or die(mysql_error());
}

if(isset($_POST['unpublish'])){
mysql_query("UPDATE testi_produk SET status_testi = '0'
			 WHERE id_testi = $_POST[idtesti]") or die(mysql_error());
}

if($_GET['act']=="balas"){
	$id_testi = addslashes($_GET['idt']);
	$qtesti = mysql_query("SELECT * FROM testi_produk WHERE id_testi = '$id_testi'");
	$dtesti = mysql_fetch_array($qtesti);
	?>
    <form action="" method="post" class="niceform">
        <table>
        	<tr><input type="hidden" name="idp" value="<?php echo $dtesti['id_produk']; ?>" />
            	<td><label for="harga">Tanggal :</label></td>
            	<td><input type="text" readonly="readonly" value="<?php echo tgl_indo($dtesti['tgl_testi']); ?>" /></td>
            </tr>
        	<tr>
            	<td><label for="deskripsi">Testimoni :</label></td>
            	<td><textarea name="isi" rows="5" cols="60" readonly="readonly"><?php echo $dtesti['testimoni']; ?></textarea></td>
            </tr>
        	<tr>
            	<td colspan="2" style="font-size:16px"><strong>Balasan</strong></td>
            </tr>
        	<tr>
            	<td><label for="deskripsi"> Balas Testimoni :</label></td>
            	<td><textarea name="balasan_testi" rows="5" cols="60"></textarea></td>
            </tr>
            <tr><input type="hidden" name="idtesti" value="<?php echo $id_testi; ?>" />
            	<td colspan="2" align="center"><input type="submit" name="balas" value="Balas" />
                <?php if ($dtesti['status_testi']==0){ echo "
                <input type=submit name=publish value=Tampilkan />"; 
				}
				else {echo "
                <input type=submit name=unpublish value=Semunyikan />"; 	
				}?>
                <input type="reset" name="reset" value="Kembali" onClick="window.location = '?page=testiproduk';" /></td>
            </tr>
        </table>
	</form>
    <?php
}
else {
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
    <td align="right">
    </td>
    <td align="right" width="70%">
    <form method="post" action="">
    	<select name="type" class="newsletter_input" style="height:28px; padding-top:2px;">
        	<option value="0">Semua</option>
            <option value="2" <?php if(isset($_POST['type'])){ echo($_POST['type'] == 2)?"selected":""; } ?>>Produk</option>
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
		elseif($type == 2)
			$sqlquery = "AND nama_produk LIKE '%$_POST[textcari]%'";
	}
	else{
		$sqlquery = "";	
	}
?>         
              
<table width="592" id="rounded-corner">
    <thead>
    	<tr>
        	<th width="51" class="rounded-company" scope="col">No</th>
            <th width="100" class="rounded" scope="col">Member</th>
            <th width="120" class="rounded" scope="col">Produk</th>
            <th width="100" class="rounded" scope="col">Tanggal</th>
            <th width="444" class="rounded" scope="col">Testimoni</th>
            <th width="32" class="rounded" scope="col">Status</th>
            <th width="32" class="rounded" scope="col">Lihat</th>
            <th width="45" class="rounded" scope="col">Hapus</th>
        </tr>
    </thead>
        <tfoot>
    </tfoot>
    <tbody>
    	<?php
		$no = 0;
		$qtestiproduk = mysql_query("SELECT * FROM testi_produk a, produk b
								  WHERE a.id_produk=b.id_produk
								  $sqlquery
								  ORDER BY tgl_testi DESC
								  LIMIT $posisi,$batas");
		$kolom=1;
		$i=0;
		$no = $posisi+1;
		while($dtesti = mysql_fetch_array($qtestiproduk)){
			if ($i >= $kolom){
				echo "<tr class='row$dtesti[id_testiproduk]'>";
			}
		?>
        	<td><?php echo $no; ?></td>
            <td>
			<?php $member=mysql_fetch_array(mysql_query("SELECT nama_member nama FROM member WHERE id_member=$dtesti[id_member]"));
				if ($dtesti['id_member']==0){
					$nama="Admin";
				}
				else {
					$nama=$member['nama'];	
				}
				echo $nama; ?></td>
            <td><?php echo $dtesti['nama_produk']; ?></td>
            <td><?php echo tgl_indo($dtesti['tgl_testi']); ?></td>
            <td><?php echo $dtesti['testimoni']; ?></td>
            <td>
				<?php
                if($dtesti['status_testi'] == 0){
                    echo "<img src='images/publish_t.png' border='0' alt='Disembunyikan' title='Disembunyikan' />";
                }
                elseif($dtesti['status_testi'] == 1){
                    echo "<img src='images/publish_y.png' border='0' alt='Ditampilkan' title='Ditampilkan' />";
                }
                ?>
            </td>
            <td>
            	<a href="?page=testiproduk&act=balas&idt=<?php echo $dtesti['id_testi']; ?>">
                	<img src="images/user_edit.png" alt="" title="" border="0" />
                </a>
            </td>
            <td width="45">
            	<a href="<?php echo $dtesti['id_testi']; ?>" id="testiproduk" class="ask">
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
	$tampil2 = mysql_query("SELECT * FROM testi_produk a, produk b, member c
							WHERE a.id_produk=b.id_produk
							AND a.id_member=c.id_member
							$sqlquery");
	$jmldata = mysql_num_rows($tampil2);
	$jmlhal  = ceil($jmldata/$batas);
		
	echo "<div class=paging>";
	// Link ke halaman sebelumnya (previous)
	if($halaman > 1){
		$prev=$halaman-1;
		echo "<span class=prevnext><a href='$_SERVER[PHP_SELF]?page=testiproduk&halaman=$prev'>Prev</a></span> ";
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
	  $angka .= "<a href='$_SERVER[PHP_SELF]?page=testiproduk&halaman=$i'>$i</a> ";
	}
	
	$angka .= "<span class=current>$halaman</span> ";
	for($i=$halaman+1;$i<($halaman+3);$i++)
	{
	  if ($i > $jmlhal) 
		  break;
	  $angka .= "<a href='$_SERVER[PHP_SELF]?page=testiproduk&halaman=$i'>$i</a> ";
	}
	
	$angka .= ($halaman+2<$jmlhal ? " ...  
			  <a href='$_SERVER[PHP_SELF]?page=testiproduk&halaman=$jmlhal'>$jmlhal</a> " : " ");
	
	echo "$angka ";
	
	// Link kehalaman berikutnya (Next)
	if($halaman < $jmlhal){
		$next=$halaman+1;
		echo "<span class=prevnext><a href='$_SERVER[PHP_SELF]?page=testiproduk&halaman=$next'>Next</a></span>";
	}
	else{ 
		echo "<span class=disabled>Next</span>";
	}
	
	echo "</div>";
}?>
     
     <h2>&nbsp;</h2>
<script>
function conf(){
	var yesno = confirm('Apakah Anda Yakin Ingin Mengubah Data ?','confimation message');
	if(yesno){
		return true;
	}
	else{
		return false;
	}
}
</script>