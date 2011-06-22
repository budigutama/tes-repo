<script type="text/javascript">
		$(document).ready(function(){
			$("#registrationform").validate({
				rules: {
					nama: "required",
					alamat: "required",
				  	password: {
          	 			required: true,
						minlength: 6
          			},		
			     	cpassword: {
				    	required: true,
				      	equalTo: "#pass"
			     	},
				  	telp: {
          	 			required: false,
					   	number: true,
						minlength: 7,
						maxlength: 15
          			},
					email: {				
						required: true,
						email: true
					}
				},
			
				messages: { 
						nama: {
							required: '. Nama harus di isi'
						},
						alamat: {
							required: '. Alamat harus di isi'
						},
						password: {
							required : '. Password harus di isi',
							minlength: '. Password minimal 6 karakter'
						},
						cpassword: {
							required: '. Ulangi Password harus di isi',
							equalTo : '. Isinya harus sama dengan Password'
						},
					  	telp: {
							number  : '. Hanya boleh di isi Angka',
							minlength: '. Telepon minimal 7 Angka',
							maxlength: '. Telepon maksimal 15 Angka'
						},
						email: {
							required: '. Email harus di isi',
							email   : '. Email harus valid'
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
	if($_POST['password'] == $_POST['cpassword']){
		$password = md5($_POST['password']);
		mysql_query("INSERT INTO admin VALUES(null,'$_POST[nama]','$_POST[alamat]','$_POST[email]','$_POST[telp]','$password','$password','0',now())");
		?>
        <form name="redirect">
 			<input type="hidden" name="redirect2">
  		</form>
		<script>
		loading('Data Sedang Disimpan', 'Loading');  
		var targetURL="?page=admin"
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
		echo "<h3>Password dan Konfirmasi Tidak Cocok !!</h3>";	
	}
}
?>
<body>
<h2>Pengolahan Data Admin</h2> 
                  
<?php
if(isset($_GET['act'])){
	if($_GET['act'] == 'add'){
	?>
    <form action="" method="post" class="niceform" id="registrationform">
        <table>
            <tr>
                <td><label for="nama">Nama Admin :</label></td>
                <td><input type="text" name="nama" id="nama" size="40" maxlength="100" /></td>
            </tr>
            <tr>
                <td><label for="email">Email :</label></td>
                <td><input type="text" name="email" id="email" size="30" maxlength="50" /></td>
            </tr>
            <tr>
                <td><label for="telp">Telephone :</label></td>
                <td><input type="text" name="telp" id="telp" size="20" maxlength="15" /></td>
            </tr>
            <tr>
                <td><label for="alamat">Alamat :</label></td>
                <td><textarea name="alamat" id="alamat" rows="5" cols="60"></textarea></td>
            </tr>
            <tr>
                <td><label for="password">Password :</label></td>
                <td><input type="password" name="password" id="pass" rows="5" cols="60" /></td>
            </tr>
            <tr>
                <td><label for="password">Confirm :</label></td>
                <td><input type="password" name="cpassword" id="cpassword" rows="5" cols="60" /></td>
            </tr>
            <tr>
                <td colspan="2" align="center"><input type="submit" name="save" value="Simpan" /><input type="reset" name="reset" value="Batal" onClick="window.location = '?page=admin';" /></td>
            </tr>
        </table>
	</form>
	<?php
	}
}
else
{
	$batas   = 15;
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
	<a href="?page=admin&act=add" class="buton">Tambah Admin</a>
    </td>
    <td align="right" width="80%">
    <form method="post" action="">
    	<select name="type" class="newsletter_input" style="height:28px; padding-top:2px;">
        	<option value="0">Semua</option>
        	<option value="1" <?php if(isset($_POST['type'])){ echo($_POST['type'] == 1)?"selected":""; } ?>>Nama</option>
            <option value="2" <?php if(isset($_POST['type'])){ echo($_POST['type'] == 2)?"selected":""; } ?>>Alamat</option>
            <option value="3" <?php if(isset($_POST['type'])){ echo($_POST['type'] == 3)?"selected":""; } ?>>Telephone</option>
            <option value="4" <?php if(isset($_POST['type'])){ echo($_POST['type'] == 4)?"selected":""; } ?>>Email</option>
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
			$sqlquery = "AND nama_admin LIKE '%$_POST[textcari]%'";
		elseif($type == 2)
			$sqlquery = "AND alamat_admin LIKE '%$_POST[textcari]%'";
		elseif($type == 3)
			$sqlquery = "AND telp_admin LIKE '%$_POST[textcari]%'";
		elseif($type == 4)
			$sqlquery = "AND email_admin LIKE '%$_POST[textcari]%'";
	}
	else{
		$sqlquery = "";	
	}
	?>
	<table width="592" id="rounded-corner">
		<thead>
			<tr>
				<th width="51" class="rounded-company" scope="col">No</th>
				<th width="444" class="rounded" scope="col">Nama Member</th>
				<th width="444" class="rounded" scope="col">Alamat</th>
				<th width="444" class="rounded" scope="col">Telephone</th>
				<th width="444" class="rounded" scope="col">Email</th>
				<th width="45" class="rounded-q4" scope="col">Delete</th>
			</tr>
		</thead>
		<tfoot>
		</tfoot>
		<tbody>
			<?php
			$no = 0;
			$qadmin = mysql_query("SELECT * FROM admin
									WHERE 1
									$sqlquery LIMIT $posisi,$batas");
			$kolom=1;
			$i=0;
			$no = $posisi+1;
			while($dadmin = mysql_fetch_array($qadmin)){
				if ($i >= $kolom){
					echo "<tr class='row$dadmin[id_admin]'>";
				}
			?>
				<td><?php echo $no; ?></td>
				<td><?php echo $dadmin['nama_admin']; ?></td>
				<td><?php echo $dadmin['alamat_admin']; ?></td>
				<td><?php echo $dadmin['telp_admin']; ?></td>
				<td><?php echo $dadmin['email_admin']; ?></td>
				<td width="45" align="center">
				<?php if ($dadmin['email_admin']!=$_SESSION['email_admin']){
				?>
					<a href="<?php echo $dadmin['id_admin']; ?>" id="admin" class="ask">
						<img src="images/trash.png" alt="" title="" border="0" />
					</a>
                <?php } 
				else {
					echo "<img src=images/trash2.png  title='Admin Sedang Aktif, tidak bisa dihapus' border=0 />"; }
				?>
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
	$tampil2 = mysql_query("SELECT * FROM admin
							WHERE 1
							$sqlquery");
	$jmldata = mysql_num_rows($tampil2);
	$jmlhal  = ceil($jmldata/$batas);
			
	echo "<div class=paging>";
	// Link ke halaman sebelumnya (previous)
	if($halaman > 1){
		$prev=$halaman-1;
		echo "<span class=prevnext><a href='$_SERVER[PHP_SELF]?page=admin&halaman=$prev'>Prev</a></span> ";
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
	  $angka .= "<a href='$_SERVER[PHP_SELF]?page=admin&halaman=$i'>$i</a> ";
	}
		
	$angka .= "<span class=current>$halaman</span> ";
	for($i=$halaman+1;$i<($halaman+3);$i++)
	{
	 if ($i > $jmlhal) 
		  break;
  	$angka .= "<a href='$_SERVER[PHP_SELF]?page=admin&halaman=$i'>$i</a> ";
	}
	
	$angka .= ($halaman+2<$jmlhal ? " ...  
  	<a href='$_SERVER[PHP_SELF]?page=admin&halaman=$jmlhal'>$jmlhal</a> " : " ");
	
	echo "$angka ";
	
	// Link kehalaman berikutnya (Next)
	if($halaman < $jmlhal){
		$next=$halaman+1;
		echo "<span class=prevnext><a href='$_SERVER[PHP_SELF]?page=admin&halaman=$next'>Next</a></span>";
	}
	else{ 
		echo "<span class=disabled>Next</span>";
	}
		
	echo "</div>";
} //end of else or !isset($_GET['act'])
	?>
     
     <h2>&nbsp;</h2>
</body>
