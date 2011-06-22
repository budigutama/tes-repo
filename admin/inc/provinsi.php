<script type="text/javascript">
		$(document).ready(function(){
			$("#registrationform").validate({
				rules: {
					provinsi : "required"
				},
			
				messages: {
						provinsi: {
							required: '. Provinsi harus di isi'
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
	$prov=strtoupper(trim($_POST['provinsi']));
	$qcek = mysql_query("SELECT * FROM provinsi WHERE UPPER(nama_provinsi) = '$prov'");
	if(mysql_num_rows($qcek) == 0){
		mysql_query("INSERT INTO provinsi VALUES(null,'$prov')");
		?>
        <form name="redirect">
 			<input type="hidden" name="redirect2">
  		</form>
		<script>
		loading('Data Sedang Disimpan', 'Loading');  
		var targetURL="?page=provinsi"
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
  		echo "<h3>Maaf, Provinsi Tidak Boleh Sama !!</h3>";
	}
}

if(isset($_POST['update'])){
	$prov=strtoupper(trim($_POST['provinsi']));
	$id_provinsi = $_POST['id_provinsi'];
	$qcek = mysql_query("SELECT * FROM provinsi WHERE UPPER(nama_provinsi) = '$prov' AND id_provinsi != $id_provinsi");
	if(mysql_num_rows($qcek) == 0){
		mysql_query("UPDATE provinsi SET nama_provinsi = '$prov' WHERE id_provinsi = $id_provinsi");
		?>
        <form name="redirect">
 			<input type="hidden" name="redirect2">
  		</form>
		<script>
		loading('Data Sedang Diupdate', 'Loading');  
		var targetURL="?page=provinsi"
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
  		echo "<h3>Maaf, Provinsi Tidak Boleh Sama !!</h3>";
	}
}
?>
<h2>Pengolahan Data Provinsi</h2> 

<?php
if(isset($_GET['act'])){
	if($_GET['act'] == 'add'){
	?>
    <form action="" method="post" class="niceform" id="registrationform">
        <table>
        	<tr>
            	<td><label for="provinsi">Nama Provinsi :</label></td>
            	<td><input type="text" name="provinsi" id="provinsi" size="49" maxlength="128" /></td>
            </tr>
        	<tr>
            	<td colspan="2" align="center"><input type="submit" name="save" value="Simpan" /><input type="reset" name="reset" value="Batal" onClick="window.location = '?page=provinsi';" /></td>
            </tr>
        </table>
	</form>
    <?php
	}
	elseif($_GET['act'] == 'edit'){
	$id_provinsi = addslashes($_GET['idp']);
	$qprov = mysql_query("SELECT * FROM provinsi WHERE id_provinsi = '$id_provinsi'");
	$dprov = mysql_fetch_array($qprov);
	?>
    <form action="" method="post" class="niceform" id="registrationform">
        <table>
        	<tr>
            	<td><label for="provinsi">Nama Provinsi :</label></td>
            	<td>
                	<input type="hidden" name="id_provinsi" value="<?php echo $dprov['id_provinsi']; ?>" />
                	<input type="text" name="provinsi" id="provinsi" size="49" maxlength="128" value="<?php echo $dprov['nama_provinsi']; ?>" />
                    </td>
            </tr>
        	<tr>
            	<td colspan="2" align="center"><input type="submit" name="update" value="Ubah" /><input type="reset" name="reset" value="Batal" onClick="window.location = '?page=provinsi';" /></td>
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
	<a href="?page=provinsi&act=add" class="buton">Tambah Provinsi</a>
    </td>
    <td align="right" width="80%">
    <form method="post" action="">
    	<input type="text" name="textcari" class="newsletter_input" value="<?php if(isset($_POST['textcari'])){ echo $_POST['textcari']; } else { echo "Kata Kunci..."; }?>" onBlur="if(this.value=='') this.value='Kata Kunci...';" onFocus="if(this.value=='Kata Kunci...') this.value='';" /><input type="image" src="../images/search.png" name="cari" style="margin-bottom:-4px;"/>
    </form>
    </td></tr>
    </table>
    <?php
	if(isset($_POST['textcari'])){
		$sqlquery = "AND nama_provinsi LIKE '%$_POST[textcari]%'";
	}
	else{
		$sqlquery = "";	
	}
?>         
              
<table width="592" id="rounded-corner">
    <thead>
    	<tr>
        	<th width="51" class="rounded-company" scope="col">No</th>
            <th width="444" class="rounded" scope="col">Nama Provinsi</th>
            <th width="32" class="rounded" scope="col">Ubah</th>
            <th width="45" class="rounded-q4" scope="col">Hapus</th>
        </tr>
    </thead>
        <tfoot>
    </tfoot>
    <tbody>
    	<?php
		$no = 0;
		$qprovinsi = mysql_query("SELECT * FROM provinsi
								  WHERE 1
								  $sqlquery
								  LIMIT $posisi,$batas");
		$kolom=1;
		$i=0;
		$no = $posisi+1;
		while($dprovinsi = mysql_fetch_array($qprovinsi)){
			if ($i >= $kolom){
				echo "<tr class='row$dprovinsi[id_provinsi]'>";
			}
		?>
        	<td><?php echo $no; ?></td>
            <td><?php echo $dprovinsi['nama_provinsi']; ?></td>
            <td><a href="?page=provinsi&act=edit&idp=<?php echo $dprovinsi['id_provinsi']; ?>"><img src="images/user_edit.png" alt="" title="" border="0" /></a></td>
            <td width="45">
            	<a href="<?php echo $dprovinsi['id_provinsi']; ?>" id="provinsi" class="ask">
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
	$tampil2 = mysql_query("SELECT * FROM provinsi
							WHERE 1
							$sqlquery");
	$jmldata = mysql_num_rows($tampil2);
	$jmlhal  = ceil($jmldata/$batas);
		
	echo "<div class=paging>";
	// Link ke halaman sebelumnya (previous)
	if($halaman > 1){
		$prev=$halaman-1;
		echo "<span class=prevnext><a href='$_SERVER[PHP_SELF]?page=provinsi&halaman=$prev'>Prev</a></span> ";
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
	  $angka .= "<a href='$_SERVER[PHP_SELF]?page=provinsi&halaman=$i'>$i</a> ";
	}
	
	$angka .= "<span class=current>$halaman</span> ";
	for($i=$halaman+1;$i<($halaman+3);$i++)
	{
	  if ($i > $jmlhal) 
		  break;
	  $angka .= "<a href='$_SERVER[PHP_SELF]?page=provinsi&halaman=$i'>$i</a> ";
	}
	
	$angka .= ($halaman+2<$jmlhal ? " ...  
			  <a href='$_SERVER[PHP_SELF]?page=provinsi&halaman=$jmlhal'>$jmlhal</a> " : " ");
	
	echo "$angka ";
	
	// Link kehalaman berikutnya (Next)
	if($halaman < $jmlhal){
		$next=$halaman+1;
		echo "<span class=prevnext><a href='$_SERVER[PHP_SELF]?page=provinsi&halaman=$next'>Next</a></span>";
	}
	else{ 
		echo "<span class=disabled>Next</span>";
	}
	
	echo "</div>";
}
	?>
     
     <h2>&nbsp;</h2>
