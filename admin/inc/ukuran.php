<?php include "DetSize.php" ?>
<script type="text/javascript">
		$(document).ready(function(){
			$("#registrationform").validate({
				rules: {
					ukuran: "required",
					kategori: "required",
				},
				messages: { 
						ukuran: {
							required: '. Ukuran harus di isi'
						},
						kategori: {
							required: '. Kategori harus di isi'
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
	$size=strtoupper(trim($_POST['ukuran']));
	$qcek = mysql_query("SELECT * FROM ukuran WHERE UPPER(nama_ukuran) = '$size' AND kategori='$_POST[kategori]'");
	if(mysql_num_rows($qcek) == 0){
		mysql_query("INSERT INTO ukuran VALUES(null,'$size','$_POST[kategori]',$_POST[spek1], $_POST[spek2], $_POST[spek3], $_POST[spek4])");
		?>
        <form name="redirect">
 			<input type="hidden" name="redirect2">
  		</form>
		<script>
		loading('Data Sedang Disimpan', 'Loading');  
		var targetURL="?page=ukuran"
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
		echo "<h3>Ukuran Sudah Ada Sebelumnya !!!</h3>";	
	}
}

if(isset($_POST['update'])){
	$id_ukuran = $_POST['id_ukuran'];
	if ($_POST[spek1]=='') {$s1=0;} else {$s1=$_POST[spek1];} 
	if ($_POST[spek2]=='') {$s2=0;} else {$s2=$_POST[spek2];} 
	if ($_POST[spek3]=='') {$s3=0;} else {$s3=$_POST[spek3];} 
	if ($_POST[spek4]=='') {$s4=0;} else {$s4=$_POST[spek4];}
	
	$size=strtoupper(trim($_POST['ukuran']));
	$qcek = mysql_query("SELECT * FROM ukuran WHERE UPPER(nama_ukuran) = '$size' AND kategori='$_POST[kategori]' AND id_ukuran != $id_ukuran");
	if(mysql_num_rows($qcek) == 0){
		mysql_query("UPDATE ukuran SET nama_ukuran = '$size',spek1=$s1, spek2=$s2, spek3=$s3,spek4=$s4
					 WHERE id_ukuran = $id_ukuran");
		?>
        <form name="redirect">
 			<input type="hidden" name="redirect2">
  		</form>
		<script>
		loading('Data Sedang Diupdate', 'Loading');  
		var targetURL="?page=ukuran"
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
		echo "<h3>Ukuran Sudah Ada Sebelumnya !!!</h3>";	
	}
}
?>
<?php

?>
<h2>Pengolahan Data ukuran</h2> 

<?php
if(isset($_GET['act'])){
	if($_GET['act'] == 'add'){
	?>
    <form action="" method="post" class="niceform" id="registrationform">
        <table>
        	<tr>
            	<td><label for="kategori">Kategori :</label></td>
            	<td>
                <select name="kategori" id="kategori" onchange="sizepack()">
                <option value="">--Pilih Kategori--</option>        
						<?php
						$qproduk = mysql_query("SELECT * FROM kategori");
						while($dproduk = mysql_fetch_array($qproduk)){
						?>
                        	<option value="<?php echo strtoupper($dproduk['nama_kategori']); ?>">
							<?php echo strtoupper($dproduk['nama_kategori']); ?></option>
                        <?php	
						}
						?>
                        </select>
                </td>
            </tr>
        	<tr>
            	<td><label for="ukuran">Nama Ukuran :</label></td>
            	<td><input type="text" name="ukuran" id="ukuran" size="20" maxlength="128" /></td>
            </tr>
            <tr>
                <td colspan="2"><strong><span id="HeaderSpek"></span></strong></td>    
            </tr>
            <tr>
                <td><label for="Spek1"><span id="lSpek1"></span></label></td>    
                <td><span id="tSpek1"></span></td>
            </tr>
            <tr>
                <td><label for="Spek2"><span id="lSpek2"></span></label></td>    
                <td><span id="tSpek2"></span></td>
            </tr>
            <tr>
                <td><label for="Spek3"><span id="lSpek3"></span></label></td>    
                <td><span id="tSpek3"></span></td>
            </tr>
            <tr>
                <td><label for="Spek4"><span id="lSpek4"></span></label></td>    
                <td><span id="tSpek4"></span></td>
            </tr>
        	<tr>
       <td colspan="2" align="center"><input type="submit" name="save" value="Simpan" /><input type="reset" name="reset" value="Batal" onClick="window.location = '?page=ukuran';" /></td>
            </tr>
        </table>
	</form>
<strong style="color:#333333"><pre>
Ket : * Spesifikasi ukuran yang dimasukan adalah untuk laki-laki
      * Spesifikasi ukuran untuk perempuan adalah spesifikasi ukuran laki-laki dikurangi 2cm
</pre></strong>

    <?php
	}
	elseif($_GET['act'] == 'edit'){
	$id_ukuran = addslashes($_GET['idu']);
	$qukuran = mysql_query("SELECT * FROM ukuran WHERE id_ukuran = '$id_ukuran'");
	$dukuran = mysql_fetch_array($qukuran);
	$kategori=$dukuran['kategori'];
	?>
    <form action="" method="post" class="niceform" id="registrationform">
        <table>
        	<tr>
            	<td><label for="kategori">Kategori :</label></td>
            	<td><input type="text" name="kategori" value="<?php echo $kategori ?>" readonly="readonly" />
                </td>
            </tr>
        	<tr>
            	<td><label for="ukuran">Nama ukuran :</label></td>
            	<td>
                	<input type="hidden" name="id_ukuran" value="<?php echo $dukuran['id_ukuran']; ?>" />
                	<input type="text" name="ukuran" id="ukuran" size="49" maxlength="128" value="<?php echo $dukuran['nama_ukuran']; ?>" />
                </td>
            </tr>
           <?php 
	       if ($kategori=="TSHIRT" or $kategori=="JAKET" or $kategori=="SWEATER"){?>
        	<tr>
            	<td><label for="ukuran">Lebar Bahu :</label></td>
            	<td>
                	<input type="text" name="spek1" id="spek1" size="5" value="<?php echo $dukuran['spek1']; ?>" /> Cm
                </td>
            </tr>
        	<tr>
            	<td><label for="ukuran">Lingkar Dada :</label></td>
            	<td>
                	<input type="text" name="spek2" id="spek2" size="5" value="<?php echo $dukuran['spek2']; ?>" /> Cm
                </td>
            </tr>
        	<tr>
            	<td><label for="ukuran">Lingkar Pinggang :</label></td>
            	<td>
                	<input type="text" name="spek3" id="spek3" size="5" value="<?php echo $dukuran['spek3']; ?>" /> Cm
                </td>
            </tr>
        	<tr>
            	<td><label for="ukuran">Tinggi :</label></td>
            	<td>
                	<input type="text" name="spek4" id="spek4" size="5" value="<?php echo $dukuran['spek4']; ?>" /> Cm
                </td>
            </tr>
           <?php }
		   else if ($kategori=="CELANA"){?>
        	<tr>
            	<td><label for="ukuran">Lingkar Pinggang :</label></td>
            	<td>
                	<input type="text" name="spek1" id="spek1" size="5" value="<?php echo $dukuran['spek1']; ?>" /> Cm
                </td>
            </tr>
        	<tr>
            	<td><label for="ukuran">Panjang :</label></td>
            	<td>
                	<input type="text" name="spek2" id="spek2" size="5" value="<?php echo $dukuran['spek2']; ?>" /> Cm
                </td>
            </tr>
           <?php }
		   else if ($kategori=="SEPATU" OR $kategori=="SENDAL"){?>
        	<tr>
            	<td><label for="ukuran">Panjang :</label></td>
            	<td>
                	<input type="text" name="spek1" id="spek1" size="5" value="<?php echo $dukuran['spek1']; ?>" /> Cm
                </td>
            </tr>
        	<tr>
            	<td><label for="ukuran">Lebar :</label></td>
            	<td>
                	<input type="text" name="spek2" id="spek2" size="5" value="<?php echo $dukuran['spek2']; ?>" /> Cm
                </td>
            </tr>
           <?php } ?>
        	<tr>
            	<td colspan="2" align="center"><input type="submit" name="update" value="Ubah" /><input type="reset" name="reset" value="Batal" onClick="window.location = '?page=ukuran';" /></td>
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
	<a href="?page=ukuran&act=add" class="buton">Tambah Ukuran</a>
    </td>
    <td align="right" width="80%">
    <form method="post" action="">
    	<input type="text" name="textcari" class="newsletter_input" value="<?php if(isset($_POST['textcari'])){ echo $_POST['textcari']; } else { echo "Kata Kunci..."; }?>" onBlur="if(this.value=='') this.value='Kata Kunci...';" onFocus="if(this.value=='Kata Kunci...') this.value='';" /><input type="image" src="../images/search.png" name="cari" style="margin-bottom:-4px;"/>
    </form>
    </td></tr>
    </table>
    <?php
	if(isset($_POST['textcari'])){
		$sqlquery = "AND nama_ukuran LIKE '%$_POST[textcari]%'";
	}
	else{
		$sqlquery = "";	
	}
?>         
      
<table width="592" id="rounded-corner">
    <thead>
    	<tr>
        	<th width="51" class="rounded-company" scope="col">No</th>
            <th width="120" class="rounded" scope="col">Kategori</th>
            <th width="120" class="rounded" scope="col">Nama ukuran</th>
            <th width="200" class="rounded" scope="col">Deskripsi</th>
            <th width="32" class="rounded" scope="col">Ubah</th>
            <th width="45" class="rounded-q4" scope="col">Hapus</th>
        </tr>
    </thead>
        <tfoot>
    </tfoot>
    <tbody>
    	<?php
		$no = 0;
		$qukuran = mysql_query("SELECT * FROM ukuran
								WHERE 1
								$sqlquery
								LIMIT $posisi,$batas");
		$kolom=1;
		$i=0;
		$no = $posisi+1;
		while($dukuran = mysql_fetch_array($qukuran)){
			if ($i >= $kolom){
				echo "<tr class='row$dukuran[id_ukuran]'>";
			}
		?>
        	<td><?php echo $no; ?></td>
            <td><?php echo $dukuran['2']; ?></td>
            <td><?php echo $dukuran['1']; ?></td>
            <td><?php echo desc($dukuran[id_ukuran]) ?></td>
            <td><a href="?page=ukuran&act=edit&idu=<?php echo $dukuran['id_ukuran']; ?>"><img src="images/user_edit.png" alt="" title="" border="0" /></a></td>
            <td width="45">
            <?php
			$ukur=mysql_query("SELECT * FROM detailproduk WHERE id_ukuran=$dukuran[id_ukuran]");
			if (mysql_num_rows($ukur)==0){?>
            	<a href="<?php echo $dukuran['id_ukuran']; ?>" id="ukuran" class="ask">
                	<img src="images/trash.png" alt="" title="Hapus Ukuran" border="0" />
                </a>
            <?php }
			else {
				echo "<img src=images/trash2.png  title='Ukuran $dukuran[nama_ukuran] tidak bisa dihapus,digunakan tabel lain' border=0 />";
				}?>
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
	$tampil2 = mysql_query("SELECT * FROM ukuran WHERE 1 $sqlquery");
	$jmldata = mysql_num_rows($tampil2);
	$jmlhal  = ceil($jmldata/$batas);
		
	echo "<div class=paging>";
	// Link ke halaman sebelumnya (previous)
	if($halaman > 1){
		$prev=$halaman-1;
		echo "<span class=prevnext><a href='$_SERVER[PHP_SELF]?page=ukuran&halaman=$prev'>Prev</a></span> ";
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
	  $angka .= "<a href='$_SERVER[PHP_SELF]?page=ukuran&halaman=$i'>$i</a> ";
	}
	
	$angka .= "<span class=current>$halaman</span> ";
	for($i=$halaman+1;$i<($halaman+3);$i++)
	{
	  if ($i > $jmlhal) 
		  break;
	  $angka .= "<a href='$_SERVER[PHP_SELF]?page=ukuran&halaman=$i'>$i</a> ";
	}
	
	$angka .= ($halaman+2<$jmlhal ? " ...  
			  <a href='$_SERVER[PHP_SELF]?page=ukuran&halaman=$jmlhal'>$jmlhal</a> " : " ");
	
	echo "$angka ";
	
	// Link kehalaman berikutnya (Next)
	if($halaman < $jmlhal){
		$next=$halaman+1;
		echo "<span class=prevnext><a href='$_SERVER[PHP_SELF]?page=ukuran&halaman=$next'>Next</a></span>";
	}
	else{ 
		echo "<span class=disabled>Next</span>";
	}
	
	echo "</div>";
}
	?>
     
     <h2>&nbsp;</h2>
