<link rel="stylesheet" href="js/validate/val.css" type="text/css" />
<script type="text/javascript" src="js/validate/jquery.validate.js"></script>
<script type="text/javascript">
		$(document).ready(function(){
			$("#registrationform").validate({
				rules: {
					nama: "required",
					alamat: "required",
				  	kodepos: {
          	 			required: true,
					   	number: true,
						minlength: 5,
						maxlength: 5
          			},
					provinsi : "required",
					kota : "required",
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
					  	kodepos: {
							required: '. Kodepos harus di isi',
							number  : '. Hanya boleh di isi Angka',
							minlength: '. Kodepos minimal 5 karakter',
							maxlength: '. Kodepos maksimal 5 karakter'
						},
						alamat: {
							required: '. Alamat harus di isi'
						},
						provinsi: {
							required: '. Provinsi harus di isi'
						},
						kota: {
							required: '. Kota harus di isi'
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
	<div class="center_title_bar">Registrasi</div>
	<div class="prod_box_big">
		<div class="center_prod_box_big">

		<form action="?page=home" method="post" id="registrationform">
            <table align="left" border="0" cellpadding="5" cellspacing="5">
              <tr>
                <td width="119" align="left">Nama</td>
                <td width="564" align="left">
                	<input name="nama" id="nama" type="text" size="30" maxlength="30" class="inputan" />
                </td>
              </tr>
              <tr>
                <td align="left">Kata Sandi</td>
                <td align="left">
        			<div class="form-row">
                  		<input name="password" type="password" id="pass" size="25" maxlength="30" class="inputan"/>
                  	</div>
                </td>
              </tr>
            <tr>
                <td align="left">Konfirmasi Kata Sandi</td>
                <td align="left">                    
                  <div class="form-row">
                      <input name="cpassword" type="password" id="cpassword" size="25" maxlength="30" class="inputan"/>
                  </div>
                  </td>
              </tr>
              <tr>
                <td align="left">Email</td>
                <td align="left">
                  <input name="email" type="text" id="email" size="30" maxlength="30" class="inputan" /></td>
              </tr>
              <tr>
                <td align="left">Alamat</td>
                <td valign="top" align="left">
                
                  <textarea name="alamat" cols="30" rows="3" id="alamat" class="inputan"></textarea></td>
              </tr>
              <tr>
                <td align="left">Provinsi</td>
                <td align="left"><select name="provinsi" id="provinsi" class="inputan">
                  <option value="">-- Pilih Provinsi --</option>
                  <?php
                        $res=mysql_query("SELECT * FROM provinsi");
                        while($provinsi=mysql_fetch_array($res)){
                            echo "<option value=\"$provinsi[id_provinsi]\">$provinsi[nama_provinsi]</option>";
                        }
                    ?>
                </select></td>
              </tr>
              <tr>
                <td align="left">Kota</td>
                <td align="left"><select name="kota" id="kota" class="inputan">
                    <option value="">-- Pilih Kota --</option>
                  </select></td>
              </tr>
              <tr>
                <td align="left">Kode Pos</td>
                <td align="left"><input name="kodepos" type="text" id="kodepos" size="10" maxlength="5" class="inputan" /></td>
              </tr>
              <tr>
                <td align="left">No telepon</td>
                <td align="left"><input name="telp" type="text" id="telp" size="20" maxlength="20" class="inputan" /></td>
              </tr>
              <tr>
                <td align="left">&nbsp;</td>
                <td align="left"><img src="image.php" /></td>
              </tr>
              <tr>
                <td align="left">Masukan kode</td>
                <td align="left"><input name="code" type="text" size="10" maxlength="5" class="inputan" /></td>
              </tr>
               <tr>
                <td colspan="2" align="center">
                	<input type="submit" name="registrasi" value="Proses" class="buton" />
                	<input type="reset" value="Reset" class="buton" />
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