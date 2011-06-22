<?php
session_start();
include "../fungsi/koneksi.php";
include "../fungsi/function.php";
?>
<link rel="stylesheet" type="text/css" href="tfw.css" />
     
<script language="javascript">
function validasi(form){
  if (form.email.value == ""){
    alert("Anda belum mengisikan Email.");
    form.email.focus();
    return (false);
  }
     
  if (form.password.value == ""){
    alert("Anda belum mengisikan Password.");
    form.password.focus();
    return (false);
  }
  return (true);
}
</script>
<title>Login Administrator</title>
</head>
<body OnLoad="document.login.email.focus();">
<div class="content">
      <div id="bd">
          <h1 class="judul">Login Administrator</h1>
<?php
if(isset($_POST['login'])){
	$time = time();
	$time_check = $time - 600;
	mysql_query("UPDATE admin SET status_login = '0' WHERE waktu_login < $time_check");
	loginadmin($_POST['email'],$_POST['password']);
}

if(isset($_POST['kirim'])){
	lupasadmin($_POST['email']);
}

if(isset($_POST['gantipassword'])){
	if($_POST['password'] == $_POST['password2']){
		$password = md5($_POST['password']);
		$qubah = mysql_query("UPDATE admin SET password_admin = '$password' WHERE verification_admin = '$_POST[code]'") or die(mysql_error());
		if($qubah){
			$changever = md5(uniqid());
			mysql_query("UPDATE admin SET verification_admin = '$changever' WHERE verification_admin = '$_POST[code]'") or die(mysql_error());
			echo "<h3 class=h3>Password Telah Dirubah !</h3>";
		}
		else
			echo "<h3 class=h3>Password Gagal Dirubah, Verifikasi Tidak Cocok !</h3>";
	}
	else{
		echo "<h3 class=h3>Password Tidak Cocok !</h3>";	
	}
}

 if (isset($_POST['lupas'])){?>
        	<div>
				<form id="login-form" action="" method="post">
                    <div class="row">
	                    <label for="username_or_email">Email</label>
    					<span class="field">
      						<input aria-required="true" class="text" id="username_or_email" name="email" type="text">
						</span>
					</div>
                    <div class="row submit">
                        <span class="btn btn-default"><input name="kirim" value="kirim" type="submit"></span>
                        <span class="btn"><input id="cancel-btn" value="Kembali" type="submit"></span>
                    </div>
				</form>
    		</div> 
      <?php }
      elseif (isset($_GET['code'])){
		  if(mysql_num_rows(mysql_query("SELECT * FROM admin WHERE verification_admin = '$_GET[code]'")) == 1){
			?>
            <div>
            <p align="justify">Silahkan Masukkan Password Baru Anda :</p>
            	<form id="login-form" action="" method="post">
					<div class="row password">
						<label for="password">Kata Sandi</label>
                        <span class="field">
                            <input aria-required="true" class="password" id="password" name="password" value="" type="password">
                        </span></div>
					<div class="row password">
						<label for="password">Kata Sandi</label>
                        <span class="field">
                            <input aria-required="true" class="password" id="password" name="password2" value="" type="password">
                        </span></div>
                    <div class="row submit">
                    		<input name="code" type="hidden" value="<?php echo $_GET['code']; ?>" />
                        <span class="btn btn-default"><input name="gantipassword" value="Ganti Password" type="submit"></span>
                    </div>
                </form>
            <?php
			}
			else{
				echo "<script>window.location = '?page=index';</script>";	
			} ?>

    		</div> 
      <?php }
	  else{?>
        	<div>
				<form id="login-form" action="" method="post">
                    <div class="row">
	                    <label for="username_or_email">Email</label>
    					<span class="field">
      						<input aria-required="true" class="text" id="username_or_email" name="email" type="text">
						</span>
					</div>
					<div class="row password">
						<label for="password">Kata Sandi</label>
                        <span class="field">
                            <input aria-required="true" class="password" id="password" name="password" value="" type="password">
                        </span></div>
                    <div class="row submit">
                        <span class="btn btn-default"><input name="login" value="Masuk" type="submit"></span>
                    </div>
                    <span style="margin-left:150px;">
    					<input type="submit" name="lupas" value="Lupa Password ?" style="border:0px; background:#fff; font-size:13px; font-weight:bold;">
					</span>
				</form>
    		</div>
      <?php }
	  ?>
	    </div>
</div>
</body>
</html>