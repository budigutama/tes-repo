<h2>Ubah Kata Sandi</h2> 
<?php
if(isset($_POST['update'])){
	$querygetpass = mysql_query("SELECT *
								FROM admin
								WHERE id_admin = '$_SESSION[id_admin]'");
	$datagetpass = mysql_fetch_array($querygetpass);
	if($datagetpass['password_admin'] == md5($_POST['oldpassword'])){
		if($_POST['newpassword'] == $_POST['confirm']){
			$querypass = mysql_query("UPDATE admin
										SET password_admin = '".md5($_POST['newpassword'])."'
										WHERE id_admin = '$_SESSION[id_admin]'");
			if($querypass){
				?>
					<form name="redirect">
						<input type="hidden" name="redirect2">
					</form>
					<script>
					loading('Data Sedang Diupdate', 'Loading');  
					var targetURL="?page=editpassword"
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
				?>
					<script>pesan('Maaf, Gagal Mengupdate Password !!','Perhatian');</script>
				<?php
			}
		}
		else{
			?>
				<script>pesan('Maaf, Password dan Confirmasi Tidak Cocok !!','Perhatian');</script>
			<?php
		}
	}
	else{
		?>
			<script>pesan('Maaf, Password Tidak Cocok dengan Password Lama !!','Perhatian');</script>
		<?php
	}
}
?>
<form action="" method="post" class="niceform" onsubmit="return conf();">
	<table>
      	<tr>
           	<td><label for="oldpassword">Password Lama :</label></td>
           	<td><input type="password" name="oldpassword" id="oldpassword" size="30" maxlength="50" /></td>
        </tr>
       	<tr>
           	<td colspan="2" align="center"><hr /></td>
        </tr>
      	<tr>
           	<td><label for="newpassword">Password Baru :</label></td>
           	<td><input type="password" name="newpassword" id="newpassword" size="30" maxlength="50" /></td>
        </tr>
      	<tr>
           	<td><label for="confirm">Confirm :</label></td>
           	<td><input type="password" name="confirm" id="confirm" size="30" maxlength="50" /></td>
        </tr>
       	<tr>
           	<td colspan="2" align="center"><input type="submit" name="update" value="Ubah" /><input type="reset" name="reset" value="Batal" onclick="window.location = '?page=editpassword';" /></td>
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