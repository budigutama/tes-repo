<?php
if(isset($_POST['update'])){
	$qcek = mysql_query("SELECT * FROM member WHERE id_member = '$_SESSION[id_member]'");
	$dcek = mysql_fetch_array($qcek);
	
	if(!empty($_POST['passwordbaru']) && !empty($_POST['passwordlama'])){
		if(md5($_POST['passwordlama']) == $dcek['password_member']){
			if($_POST['passwordbaru'] == $_POST['confirm']){
				mysql_query("UPDATE member SET password_member = '".md5($_POST['passwordbaru'])."' WHERE id_member = '$_SESSION[id_member]'");
				echo "<script>pesan('Password Berhasil Dirubah','Berhasil');</script>";
			}
			else{
				echo "<script>pesan('Password Tidak Cocok dengan Konfirmasi','Peringatan!!!');</script>";
			}	
		}
		else{
			echo "<script>pesan('Password Tidak Cocok dengan Password Lama','Peringatan!!!');</script>";
		}
	}
	else{
		if($_POST['email'] == $dcek['email_member']){
			if(!empty($_POST['nama']) && !empty($_POST['email']) && !empty($_POST['alamat']) && !empty($_POST['telp']) && !empty($_POST['kodepos'])){
				mysql_query("UPDATE member SET nama_member = '$_POST[nama]', email_member = '$_POST[email]',
							alamat_member = '$_POST[alamat]', id_kota = '$_POST[idkota]',
							kodepos_member = '$_POST[kodepos]', telp_member = '$_POST[telp]'
							WHERE id_member = '$_SESSION[id_member]'");
				echo "<script>pesan('Informasi Akun Berhasil Diubah','Berhasil');</script>";
			}
			else{
				echo "<script>pesan('Data Tidak Boleh Ada yang Kosong','Peringatan!!!');</script>";	
			}
		}
		else{
			echo "<script>pesan('Email Telah Tersedia','Peringatan!!!');</script>";
		}
	}
}
$qakun = mysql_query("SELECT * FROM member as a, kota as b, provinsi as c
					 WHERE a.id_kota = b.id_kota
					 AND b.id_provinsi = c.id_provinsi
					 AND a.id_member = '$_SESSION[id_member]'");
$dakun = mysql_fetch_array($qakun);
?>
<div class="center_title_bar">Account Setting</div>
<div class="prod_box_big">
	<div class="center_prod_box_big">            
		<form  action="#" method="post" class="newsletter_input">
            <table align="left" border="0" cellpadding="5" cellspacing="5">
              <tr>
                <td width="119" align="left">Nama</td>
                <td width="564" align="left">
                  <input name="nama" type="text" size="30" maxlength="30" class="newsletter_input" value="<?php echo $dakun['nama_member']; ?>" /></td>
              </tr>
              <tr>
                <td align="left">Email</td>
                <td align="left">
                  <input name="email" type="text" size="30" maxlength="30" class="newsletter_input" value="<?php echo $dakun['email_member']; ?>" /></td>
              </tr>
              <tr>
                <td align="left">Alamat</td>
                <td valign="top" align="left">
                
                  <textarea name="alamat" cols="30" rows="3" class="contact_textarea"><?php echo $dakun['alamat_member']; ?></textarea></td>
              </tr>
              <tr>
                <td align="left">Provinsi</td>
                <td align="left"><select name="provinsi" id="provinsi">
                  <option value="">-- Pilih Provinsi --</option>
                  <?php
                        $qprov=mysql_query("SELECT * FROM provinsi");
                        while($dprov=mysql_fetch_array($qprov)){
						?>
                        <option value="<?php echo $dprov['id_provinsi']; ?>" <?php echo($dprov['id_provinsi'] == $dakun['id_provinsi'])?"selected":""; ?>><?php echo $dprov['nama_provinsi']; ?></option>
                        <?php
                        }
                    ?>
                </select></td>
              </tr>
              <tr>
                <td align="left">Kota</td>
                <td align="left">
                	<select name="idkota" id="kota">
                		<?php
						$qkota = mysql_query("SELECT * FROM kota");
						while($dkota = mysql_fetch_array($qkota)){
						?>
                        <option value="<?php echo $dkota['id_kota']; ?>" <?php echo($dkota['id_kota'] == $dakun['id_kota'])?"selected":""; ?>><?php echo $dkota['nama_kota']; ?></option>
                        <?php
						}
						?>
                	</select>
               	</td>
              </tr>
              <tr>
                <td align="left">Kode Pos</td>
                <td align="left"><input name="kodepos" type="text" size="30" maxlength="30" class="newsletter_input" value="<?php echo $dakun['kodepos_member']; ?>" /></td>
              </tr>
              <tr>
                <td align="left">No telepon</td>
                <td align="left"><input name="telp" type="text" size="30" maxlength="30" class="newsletter_input" value="<?php echo $dakun['telp_member']; ?>" /></td>
              </tr>
              <tr>
                <td align="left" colspan="2">&nbsp;</td>
              </tr>
              <tr>
                <td align="left">Password Lama</td>
                <td align="left">
                  <input name="passwordlama" type="password" size="30" maxlength="30" class="newsletter_input" /></td>
              </tr>
              <tr>
                <td align="left" colspan="2"><hr /></td>
              </tr>
              <tr>
                <td align="left">Password Baru</td>
                <td align="left">
                  <input name="passwordbaru" type="password" size="30" maxlength="30" class="newsletter_input" /></td>
              </tr>
            <tr>
                <td align="left">Confirm Password</td>
                <td align="left">
                  <input name="confirm" type="password" size="30" maxlength="30" class="newsletter_input"/></td>
              </tr>
               <tr>
                <td colspan="2" align="center">
                	<input type="submit" name="update" value="Ubah" class="button" />
                	<input type="reset" name="reset" value="Batal" class="button" />
                </td>
                </tr>
            </table>
        </form>
    </div>
</div>
<script>
	$("#provinsi").change(function(){ 
		var idprov = $("#provinsi").val();
		$.ajax({ 
				url: "inc/getdata/kota.php", 
				data: "idprov="+idprov, 
				cache: false, 
				success: function(msg){
					$("#kota").html(msg); 
				} 
		}); 
	}); 
</script>  