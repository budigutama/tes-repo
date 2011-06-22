<script type="text/javascript">
		$(document).ready(function(){
			$("#registrationform").validate({
				rules: {
					nama: "required",
					alamat: "required",
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
if(isset($_POST['update'])){
	mysql_query("UPDATE admin SET nama_admin = '$_POST[nama]', email_admin = '$_POST[email]', nama_admin = '$_POST[nama]',
				 telp_admin = '$_POST[telp]', alamat_admin = '$_POST[alamat]'
				 WHERE id_admin = $_SESSION[id_admin]");
				 
	?>
        <form name="redirect">
 			<input type="hidden" name="redirect2">
  		</form>
		<script>
		loading('Data Sedang Diupdate', 'Loading');  
		var targetURL="?page=editprofile"
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
?>
<h2>Ubah Biodata</h2> 
<?php
$qadmin = mysql_query("SELECT * FROM admin WHERE id_admin = $_SESSION[id_admin]");
$dadmin = mysql_fetch_array($qadmin);
?>
<form action="" method="post" class="niceform" onsubmit="return conf();" id="registrationform">
	<table>
      	<tr>
           	<td><label for="nama">Nama :</label></td>
           	<td><input type="text" name="nama" id="nama" size="49" maxlength="128" value="<?php echo $dadmin['nama_admin']; ?>" /></td>
        </tr>
      	<tr>
           	<td><label for="email">Email :</label></td>
           	<td><input type="text" name="email" id="email" size="49" maxlength="128" value="<?php echo $dadmin['email_admin']; ?>" /></td>
        </tr>
      	<tr>
           	<td><label for="telp">Telephone :</label></td>
           	<td><input type="text" name="telp" id="telp" size="49" maxlength="128" value="<?php echo $dadmin['telp_admin']; ?>" /></td>
        </tr>
        <tr>
           	<td><label for="alamat">Alamat :</label></td>
           	<td><textarea name="alamat" id="alamat" rows="5" cols="60"><?php echo $dadmin['alamat_admin']; ?></textarea></td>
        </tr>
       	<tr>
           	<td colspan="2" align="center"><input type="submit" name="update" value="Ubah" /><input type="reset" name="reset" value="Batal" onclick="window.history.back(-1);" /></td>
        </tr>
    </table>
</form>
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