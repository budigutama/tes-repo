<script type="text/javascript">
		$(document).ready(function(){
			$("#registrationform").validate({
				rules: {
					jasapengiriman : "required"
				},
			
				messages: {
						jasapengiriman: {
							required: '. Nama harus di isi',
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
	$jkirim=trim($_POST['jasapengiriman']);
	$qcek = mysql_query("SELECT * FROM jasapengiriman WHERE UPPER(nama_jasapengiriman) = '".strtoupper($jkirim)."'");
	if(mysql_num_rows($qcek) == 0){
		mysql_query("INSERT INTO jasapengiriman VALUES(null,'$jkirim','$_POST[deskripsi]')");
		?>
        <form name="redirect">
 			<input type="hidden" name="redirect2">
  		</form>
		<script>
		loading('Data Sedang Disimpan', 'Loading');  
		var targetURL="?page=jasapengiriman"
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
  		echo "<h3>Jasa Pengiriman Tidak Boleh Sama !!</h3>";
	}
}

if(isset($_POST['update'])){
	$id_jasapengiriman = $_POST['id_jasapengiriman'];
	$jkirim=trim($_POST['jasapengiriman']);
	$qcek = mysql_query("SELECT * FROM jasapengiriman 
						WHERE UPPER(nama_jasapengiriman) = '".strtoupper($jkirim)."' AND id_jasapengiriman != $id_jasapengiriman");
	if(mysql_num_rows($qcek) == 0){
		mysql_query("UPDATE jasapengiriman SET nama_jasapengiriman = '$jkirim', deskripsi_jasapengiriman = '$_POST[deskripsi]' WHERE id_jasapengiriman = $id_jasapengiriman");
		?>
        <form name="redirect">
 			<input type="hidden" name="redirect2">
  		</form>
		<script>
		loading('Data Sedang Diupdate', 'Loading');  
		var targetURL="?page=jasapengiriman"
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
  		echo "<h3>Jasa Pengiriman Tidak Boleh Sama !!</h3>";
	}
}
?>
<h2>Pengolahan Data Jasa Pengiriman</h2> 

<?php
if(isset($_GET['act'])){
	if($_GET['act'] == 'add'){
	?>
    <form action="" method="post" class="niceform" id="registrationform">
        <table>
        	<tr>
            	<td><label for="jasapengiriman">Nama Jenis Pengiriman :</label></td>
            	<td><input type="text" name="jasapengiriman" id="jasapengiriman" size="49" maxlength="128" /></td>
            </tr>
            <tr>
                <td><label for="deskripsi">Deskripsi :</label></td>
                <td colspan="2"><textarea name="deskripsi" id="deskripsi" rows="5" cols="60"></textarea></td>
            </tr>
        	<tr>
            	<td colspan="2" align="center"><input type="submit" name="save" value="Simpan" /><input type="reset" name="reset" value="Batal" onClick="window.location = '?page=jasapengiriman';" /></td>
            </tr>
        </table>
	</form>
    <?php
	}
	elseif($_GET['act'] == 'edit'){
	$id_jasapengiriman = addslashes($_GET['idp']);
	$qjenis = mysql_query("SELECT * FROM jasapengiriman WHERE id_jasapengiriman = '$id_jasapengiriman'");
	$djenis = mysql_fetch_array($qjenis);
	?>
    <form action="" method="post" class="niceform" onSubmit="return conf();" id="registrationform">
        <table>
        	<tr>
            	<td><label for="jasapengiriman">Nama jasapengiriman :</label></td>
            	<td>
                	<input type="hidden" name="id_jasapengiriman" value="<?php echo $djenis['id_jasapengiriman']; ?>" />
                	<input type="text" name="jasapengiriman" id="jasapengiriman" size="49" maxlength="128" value="<?php echo $djenis['nama_jasapengiriman']; ?>" />
                    </td>
            </tr>
            <tr>
                <td><label for="deskripsi">Deskripsi :</label></td>
                <td colspan="2">
                	<textarea name="deskripsi" id="deskripsi" rows="5" cols="60">
						<?php echo $djenis['deskripsi_jasapengiriman']; ?>
                    </textarea>
                </td>
            </tr>
        	<tr>
            	<td colspan="2" align="center"><input type="submit" name="update" value="Ubah" /><input type="reset" name="reset" value="Batal" onClick="window.location = '?page=jasapengiriman';" /></td>
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
	<a href="?page=jasapengiriman&act=add" class="buton">Tambah Jasa Pengiriman</a>
    </td>
    <td align="right" width="70%">
    <form method="post" action="">
    	<input type="text" name="textcari" class="newsletter_input" value="<?php if(isset($_POST['textcari'])){ echo $_POST['textcari']; } else { echo "Kata Kunci..."; }?>" onBlur="if(this.value=='') this.value='Kata Kunci...';" onFocus="if(this.value=='Kata Kunci...') this.value='';" /><input type="image" src="../images/search.png" name="cari" style="margin-bottom:-4px;"/>
    </form>
    </td></tr>
    </table>
    <?php
	if(isset($_POST['textcari'])){
		$sqlquery = "AND nama_jasapengiriman LIKE '%$_POST[textcari]%'";
	}
	else{
		$sqlquery = "";	
	}
?>         
              
<table width="592" id="rounded-corner">
    <thead>
    	<tr>
        	<th width="51" class="rounded-company" scope="col">No</th>
            <th width="444" class="rounded" scope="col">Nama Jenis Pengiriman</th>
            <th width="444" class="rounded" scope="col">Deskripsi</th>
            <th width="32" class="rounded" scope="col">Ubah</th>
            <th width="45" class="rounded-q4" scope="col">Hapus</th>
        </tr>
    </thead>
        <tfoot>
    </tfoot>
    <tbody>
    	<?php
		$no = 0;
		$qjasapengiriman = mysql_query("SELECT * FROM jasapengiriman
										  WHERE 1
										  $sqlquery
										  LIMIT $posisi,$batas");
		$kolom=1;
		$i=0;
		$no = $posisi+1;
		while($djasapengiriman = mysql_fetch_array($qjasapengiriman)){
			if ($i >= $kolom){
				echo "<tr class='row$djasapengiriman[id_jasapengiriman]'>";
			}
		?>
        	<td><?php echo $no; ?></td>
            <td><?php echo $djasapengiriman['nama_jasapengiriman']; ?></td>
            <td><?php echo $djasapengiriman['deskripsi_jasapengiriman']; ?></td>
            <td><a href="?page=jasapengiriman&act=edit&idp=<?php echo $djasapengiriman['id_jasapengiriman']; ?>"><img src="images/user_edit.png" alt="" title="" border="0" /></a></td>
            <td width="45">
            	<a href="<?php echo $djasapengiriman['id_jasapengiriman']; ?>" id="jasapengiriman" class="ask">
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
	$tampil2 = mysql_query("SELECT * FROM jasapengiriman
							WHERE 1
							$sqlquery");
	$jmldata = mysql_num_rows($tampil2);
	$jmlhal  = ceil($jmldata/$batas);
		
	echo "<div class=paging>";
	// Link ke halaman sebelumnya (previous)
	if($halaman > 1){
		$prev=$halaman-1;
		echo "<span class=prevnext><a href='$_SERVER[PHP_SELF]?page=jasapengiriman&halaman=$prev'>Prev</a></span> ";
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
	  $angka .= "<a href='$_SERVER[PHP_SELF]?page=jasapengiriman&halaman=$i'>$i</a> ";
	}
	
	$angka .= "<span class=current>$halaman</span> ";
	for($i=$halaman+1;$i<($halaman+3);$i++)
	{
	  if ($i > $jmlhal) 
		  break;
	  $angka .= "<a href='$_SERVER[PHP_SELF]?page=jasapengiriman&halaman=$i'>$i</a> ";
	}
	
	$angka .= ($halaman+2<$jmlhal ? " ...  
			  <a href='$_SERVER[PHP_SELF]?page=jasapengiriman&halaman=$jmlhal'>$jmlhal</a> " : " ");
	
	echo "$angka ";
	
	// Link kehalaman berikutnya (Next)
	if($halaman < $jmlhal){
		$next=$halaman+1;
		echo "<span class=prevnext><a href='$_SERVER[PHP_SELF]?page=jasapengiriman&halaman=$next'>Next</a></span>";
	}
	else{ 
		echo "<span class=disabled>Next</span>";
	}
	
	echo "</div>";
}
	?>
     
     <h2>&nbsp;</h2>
