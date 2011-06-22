<script type="text/javascript">
		$(document).ready(function(){
			$("#registrationform").validate({
				rules: {
					jenispengiriman : "required",
					jasa : "required"
				},
			
				messages: {
						jenispengiriman: {
							required: '. Nama harus di isi',
						},
						jasa: {
							required: '. Jasa Pengiriman harus di pilih',
						}
				},
				 
				 success: function(label) {
					label.text('OK!').addClass('valid');
				}
			});
		});
</script>
<?php
if(isset($_POST['save'])){
	$jskirim=trim($_POST['jenispengiriman']);
	$qcek = mysql_query("SELECT * FROM jenispengiriman WHERE nama_jenispengiriman = '".strtoupper($jskirim)."' AND id_jasapengiriman = '$_POST[jasa]'");
	if(mysql_num_rows($qcek) == 0){
		mysql_query("INSERT INTO jenispengiriman VALUES(null,'$_POST[jasa]','$jskirim','$_POST[deskripsi]')");
		?>
        <form name="redirect">
 			<input type="hidden" name="redirect2">
  		</form>
		<script>
		loading('Data Sedang Disimpan', 'Loading');  
		var targetURL="?page=jenispengiriman"
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
	  else{
		echo "<h3> Jasa dan jenis Pengiriman Suda ada!!</h3>";
	  }
}

if(isset($_POST['update'])){
	$id_jenispengiriman = $_POST['id_jenispengiriman'];
	$jskirim=trim($_POST['jenispengiriman']);
	$qcek = mysql_query("SELECT * FROM jenispengiriman WHERE nama_jenispengiriman = '".strtoupper($jskirim)."' AND id_jasapengiriman = '$_POST[jasa]' AND id_jenispengiriman != $id_jenispengiriman");
	if(mysql_num_rows($qcek) == 0){
		mysql_query("UPDATE jenispengiriman SET id_jasapengiriman = '$_POST[jasa]', nama_jenispengiriman = '$jskirim', deskripsi_jenispengiriman = '$_POST[deskripsi]' WHERE id_jenispengiriman = $id_jenispengiriman");
		?>
        <form name="redirect">
 			<input type="hidden" name="redirect2">
  		</form>
		<script>
		loading('Data Sedang Diupdate', 'Loading');  
		var targetURL="?page=jenispengiriman"
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
	  else{
		echo "<h3> Jasa dan jenis Pengiriman Sudah ada!!</h3>";
	  }
}
?>
<h2>Pengolahan Data Jenis Pengiriman</h2> 

<?php
if(isset($_GET['act'])){
	if($_GET['act'] == 'add'){
	?>
    <form action="" method="post" class="niceform" id="registrationform">
        <table>
        	<tr>
            	<td><label for="jasa">Jasa Pengiriman :</label></td>
            	<td><select size="1" name="jasa" id="jasa">
                    <option value="">-- Pilih Jasa Pengiriman --</option>
                        <?php
						$qjp = mysql_query("SELECT * FROM jasapengiriman");
						while($djp = mysql_fetch_array($qjp)){
						?>
                        <option value="<?php echo $djp['id_jasapengiriman']; ?>"><?php echo $djp['nama_jasapengiriman'];?></option>
                        <?php	
						}
						?>
                        </select>
              </td>
            </tr>
        	<tr>
            	<td><label for="jenispengiriman">Nama Jenis Pengiriman :</label></td>
            	<td><input type="text" name="jenispengiriman" id="jenispengiriman" size="49" maxlength="128" /></td>
            </tr>
            <tr>
                <td><label for="deskripsi">Deskripsi :</label></td>
                <td colspan="2"><textarea name="deskripsi" id="deskripsi" rows="5" cols="60"></textarea></td>
            </tr>
        	<tr>
            	<td colspan="2" align="center"><input type="submit" name="save" value="Simpan" /><input type="reset" name="reset" value="Batal" onClick="window.location = '?page=jenispengiriman';" /></td>
            </tr>
        </table>
	</form>
    <?php
	}
	elseif($_GET['act'] == 'edit'){
	$id_jenispengiriman = addslashes($_GET['idp']);
	$qjenis = mysql_query("SELECT * FROM jenispengiriman WHERE id_jenispengiriman = '$id_jenispengiriman'");
	$djenis = mysql_fetch_array($qjenis);
	?>
    <form action="" method="post" class="niceform" onSubmit="return conf();" id="registrationform">
        <table>
        	<tr>
            	<td><label for="jasa">Jasa Pengiriman :</label></td>
            	<td><select size="1" name="jasa" id="jasa">
                    <option value="">-- Pilih Jasa Pengiriman --</option>
                        <?php
						$qjp = mysql_query("SELECT * FROM jasapengiriman");
						while($djp = mysql_fetch_array($qjp)){
						?>
                        <option value="<?php echo $djp['id_jasapengiriman']; ?>" 
						<?php echo($djp['id_jasapengiriman'] == $djenis['id_jasapengiriman'])?"selected":""; ?>>
						<?php echo $djp['nama_jasapengiriman'];?></option>
                        <?php	
						}
						?>
                        </select>
              </td>
            </tr>
        	<tr>
            	<td><label for="jenispengiriman">Nama jenispengiriman :</label></td>
            	<td>
                	<input type="hidden" name="id_jenispengiriman" value="<?php echo $djenis['id_jenispengiriman']; ?>" />
                	<input type="text" name="jenispengiriman" id="jenispengiriman" size="49" maxlength="128" value="<?php echo $djenis['nama_jenispengiriman']; ?>" />
                    </td>
            </tr>
            <tr>
                <td><label for="deskripsi">Deskripsi :</label></td>
                <td colspan="2">
                	<textarea name="deskripsi" id="deskripsi" rows="5" cols="60">
						<?php echo $djenis['deskripsi_jenispengiriman']; ?>
                    </textarea>
                </td>
            </tr>
        	<tr>
            	<td colspan="2" align="center"><input type="submit" name="update" value="Ubah" /><input type="reset" name="reset" value="Batal" onClick="window.location = '?page=jenispengiriman';" /></td>
            </tr>
        </table>
	</form>
    <?php
	}
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
    <td align="right">
	<a href="?page=jenispengiriman&act=add" class="buton">Tambah Jenis Pengiriman</a>
    </td>
    <td align="right" width="70%">
    <form method="post" action="">
    	<select name="type" class="newsletter_input" style="height:28px; padding-top:2px;">
        	<option value="0">Semua</option>
        	<option value="1" <?php if(isset($_POST['type'])){ echo($_POST['type'] == 1)?"selected":""; } ?>>Jenis Pengiriman</option>
        	<option value="2" <?php if(isset($_POST['type'])){ echo($_POST['type'] == 2)?"selected":""; } ?>>Jasa Pengiriman</option>
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
			$sqlquery = "AND nama_jenispengiriman LIKE '%$_POST[textcari]%'";
		elseif($type == 2)
			$sqlquery = "AND nama_jasapengiriman LIKE '%$_POST[textcari]%'";
	}
	else{
		$sqlquery = "";	
	}
?>         
              
<table width="800" id="rounded-corner">
    <thead>
    	<tr>
        	<th width="50" class="rounded-company" scope="col">No</th>
            <th width="200" class="rounded" scope="col">Nama Jenis Pengiriman</th>
            <th width="200" class="rounded" scope="col">Jasa Pengiriman</th>
            <th width="380" class="rounded" scope="col">Deskripsi</th>
            <th width="35" class="rounded" scope="col">Ubah</th>
            <th width="35" class="rounded-q4" scope="col">Hapus</th>
        </tr>
    </thead>
        <tfoot>
    </tfoot>
    <tbody>
    	<?php
		$no = 0;
		$qjenispengiriman = mysql_query("SELECT * FROM jenispengiriman a, jasapengiriman b
										  WHERE a.id_jasapengiriman=b.id_jasapengiriman
										  $sqlquery
										  LIMIT $posisi,$batas");
		$kolom=1;
		$i=0;
		$no = $posisi+1;
		while($djenispengiriman = mysql_fetch_array($qjenispengiriman)){
			if ($i >= $kolom){
				echo "<tr class='row$djenispengiriman[id_jenispengiriman]'>";
			}
		?>
        	<td><?php echo $no; ?></td>
            <td><?php echo $djenispengiriman['nama_jenispengiriman']; ?></td>
            <td><?php echo $djenispengiriman['nama_jasapengiriman']; ?></td>
            <td><?php echo $djenispengiriman['deskripsi_jenispengiriman']; ?></td>
            <td><a href="?page=jenispengiriman&act=edit&idp=<?php echo $djenispengiriman['id_jenispengiriman']; ?>"><img src="images/user_edit.png" alt="" title="" border="0" /></a></td>
            <td width="45">
            	<a href="<?php echo $djenispengiriman['id_jenispengiriman']; ?>" id="jenispengiriman" class="ask">
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
	$tampil2 = mysql_query("SELECT * FROM jenispengiriman a, jasapengiriman b
							WHERE a.id_jasapengiriman=b.id_jasapengiriman
							$sqlquery");
	$jmldata = mysql_num_rows($tampil2);
	$jmlhal  = ceil($jmldata/$batas);
		
	echo "<div class=paging>";
	// Link ke halaman sebelumnya (previous)
	if($halaman > 1){
		$prev=$halaman-1;
		echo "<span class=prevnext><a href='$_SERVER[PHP_SELF]?page=jenispengiriman&halaman=$prev'>Prev</a></span> ";
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
	  $angka .= "<a href='$_SERVER[PHP_SELF]?page=jenispengiriman&halaman=$i'>$i</a> ";
	}
	
	$angka .= "<span class=current>$halaman</span> ";
	for($i=$halaman+1;$i<($halaman+3);$i++)
	{
	  if ($i > $jmlhal) 
		  break;
	  $angka .= "<a href='$_SERVER[PHP_SELF]?page=jenispengiriman&halaman=$i'>$i</a> ";
	}
	
	$angka .= ($halaman+2<$jmlhal ? " ...  
			  <a href='$_SERVER[PHP_SELF]?page=jenispengiriman&halaman=$jmlhal'>$jmlhal</a> " : " ");
	
	echo "$angka ";
	
	// Link kehalaman berikutnya (Next)
	if($halaman < $jmlhal){
		$next=$halaman+1;
		echo "<span class=prevnext><a href='$_SERVER[PHP_SELF]?page=jenispengiriman&halaman=$next'>Next</a></span>";
	}
	else{ 
		echo "<span class=disabled>Next</span>";
	}
	
	echo "</div>";
}
	?>
     
     <h2>&nbsp;</h2>
