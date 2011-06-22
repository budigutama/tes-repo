<script type="text/javascript">
		$(document).ready(function(){
			$("#registrationform").validate({
				rules: {
					produk: "required",
					warna: "required",
					ukuran: "required",
				  	stok: {
          	 			required: true,
					   	number: true
          			},
				  	berat: {
          	 			required: true,
					   	number: true
          			}
				},
			
				messages: { 
						produk: {
							required: '. produk harus di isi'
						},
						warna: {
							required: '. Warna harus di isi'
						},
						ukuran: {
							required: '. Ukuran harus di isi'
						},
					  	stok: {
							required: '. Stok harus di isi',
							number  : '. Hanya boleh di isi Angka'
						},
					  	berat: {
							required: '. Berat harus di isi',
							number  : '. Hanya boleh di isi Angka'
						}
				},
				 
				 success: function(label) {
					label.text('OK!').addClass('valid');
				}
			});
		});
</script>
<?php
include "DetSize.php";
$idp = $_GET['idp'];
$dtprod=mysql_fetch_array(mysql_query("SELECT * FROM produk a, kategori b
									  WHERE a.id_kategori=b.id_kategori 
									  AND a.id_produk='$idp'
									  GROUP BY a.id_produk"));
$kategori=$dtprod['nama_kategori'];									  

if(isset($_POST['save'])){
	$id_warna = addslashes($_POST['id_warna']);
	$id_ukuran = addslashes($_POST['id_ukuran']);
	$berat = addslashes($_POST['berat']);
	$stok = addslashes($_POST['stok']);
	$promo = addslashes($_POST['promo']);
	if(mysql_num_rows(mysql_query("SELECT * FROM detailproduk WHERE id_produk = $idp AND id_warna = $id_warna AND id_ukuran = $id_ukuran")) == 0){
	mysql_query("INSERT INTO detailproduk VALUES(null,$idp,$id_warna,$id_ukuran,now(),$stok,$berat,$_SESSION[id_admin])") or die(mysql_error());
	?>
        <form name="redirect">
 			<input type="hidden" name="redirect2">
  		</form>
		<script> 
		loading('Data Sedang Disimpan', 'Loading')
		var targetURL="?page=detailproduk&idp=<?php echo $idp; ?>"
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
  		echo "<script>pesan('Maaf, Detail produk Tidak Boleh Sama !!','Peringatan');</script>";
	}
}

if(isset($_POST['tambah'])){
		$imagename = $_FILES['upload']['name'];
	    $imagetype = $_FILES['upload']['type'];
		$source = $_FILES['upload']['tmp_name'];

		$ncount = count($imagename);
		$i = 0;
		while($i < $ncount){
			$imagepath = $imagename[$i];
			$target = "../images/product/".$imagepath;
			if(($imagetype[$i]=="image/jpeg") or ($imagetype[$i]=="image/gif")){
				move_uploaded_file($source[$i], $target);
				$qcheckprofile = mysql_query("SELECT * FROM gambar WHERE id_produk = $idp AND profile_gambar = '1'");
				if(mysql_num_rows($qcheckprofile) == 0){
					mysql_query("INSERT INTO gambar
								VALUES(null, $idp, '$imagepath', '1')");
				}
				else{
					mysql_query("INSERT INTO gambar
								VALUES(null, $idp, '$imagepath', '0')");
				}
			}
			else
				echo "<script>pesan('Gambar Ke-".($i+1)." Harus Bertype JPEG dan GIF !!!','Peringatan');</script>";
				
		$i++;
		}
		?>
		<form name="redirect">
			<input type="hidden" name="redirect2">
		</form>
		<script>
		loading('Data Sedang Disimpan', 'Loading');  
		var targetURL="?page=detailproduk&idp=<?php echo $idp; ?>"
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

if(isset($_POST['update'])){
	$id_detailproduk = addslashes($_POST['id_detailproduk']);
	$id_warna = addslashes($_POST['id_warna']);
	$id_ukuran = addslashes($_POST['id_ukuran']);
	$promo = addslashes($_POST['promo']);
	$stok = addslashes($_POST['stok']);
	$berat = addslashes($_POST['berat']);
	
	if(mysql_num_rows(mysql_query("SELECT * FROM detailproduk WHERE id_produk = $idp AND id_warna = $id_warna AND id_ukuran = $id_ukuran AND id_detailproduk != $id_detailproduk")) == 0){
	mysql_query("UPDATE detailproduk SET id_warna = '$id_warna', id_ukuran = '$id_ukuran',
				berat_detailproduk = '$berat', stok_detailproduk = '$stok', id_admin=$_SESSION[id_admin]
				WHERE id_detailproduk = $id_detailproduk");
	?>
        <form name="redirect">
 			<input type="hidden" name="redirect2">
  		</form>
		<script>
		loading('Data Sedang Diupdate', 'Loading');  
		var targetURL="?page=detailproduk&idp=<?php echo $idp; ?>"
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
  		echo "<script>pesan('Maaf, Detail produk Tidak Boleh Sama !!','Peringatan');</script>";
	}
}
?>
<h2>Pengolahan Detail Produk ( <?php echo $dtprod['kode_kategori'].$idp; ?> ) <?php echo $dtprod['nama_produk']; ?></h2> 

<?php
if(isset($_GET['act'])){
	if($_GET['act'] == 'add'){
	?>
	<div class="spesifikasiukuran">
	<?php spesifikasiview($kategori); ?>
	</div>
    <form action="" method="post" class="niceform" id="registrationform">
        <table>
        	<tr>
            	<td><label for="produk">Nama produk :</label></td>
            	<td><b><?php echo $dtprod['nama_produk']; ?></b>
               	</td>
            </tr>
        	<tr>
            	<td><label for="warna">Warna :</label></td>
            	<td>
                		<select size="1" name="id_warna" id="warna">
                        <?php
						$qwarna = mysql_query("SELECT * FROM warna");
						while($dwarna = mysql_fetch_array($qwarna)){
						?>
                        	<option value="<?php echo $dwarna['id_warna']; ?>"><?php echo $dwarna['nama_warna']; ?></option>
                        <?php	
						}
						?>
                        </select>
               	</td>
            </tr>
        	<tr>
            	<td><label for="ukuran">Ukuran <?php echo $kategori; ?> :</label></td>
            	<td>
                		<select size="1" name="id_ukuran" id="ukuran">
                        <?php
						$qukuran = mysql_query("SELECT * FROM ukuran 
								   WHERE kategori='$kategori'");
						while($dukuran = mysql_fetch_array($qukuran)){
						?>
                        <option value="<?php echo $dukuran['id_ukuran']; ?>"><?php echo $dukuran['nama_ukuran']; ?></option>
                        <?php	
						}
						?>
                        </select>
               	</td>
            </tr>
            <tr>
                <td><label for="stok">Stok :</label></td>
                <td><input name="stok" id="stok" size="3" maxlength="6" /></td>
            </tr>
            <tr>
                <td><label for="berat">Berat :</label></td>
                <td><input name="berat" id="berat" size="3" maxlength="6" />&nbsp;<strong>Kg</strong></td>
            </tr>
        	<tr>
            	<td colspan="2" align="center"><input type="submit" name="save" value="Simpan" />
              <input type="reset" name="reset" value="Batal" onClick="window.location = '?page=detailproduk&idp=<?php echo $idp; ?>';" /></td>
            </tr>
        </table>
	</form>
    <?php
	}
	elseif($_GET['act'] == 'edit'){
	$id_detailproduk = addslashes($_GET['idb']);
	$qdetailproduk = mysql_query("SELECT * FROM detailproduk as a, produk as b, ukuran as c, warna as d
						 WHERE a.id_produk = b.id_produk
						 AND a.id_ukuran = c.id_ukuran
						 AND a.id_warna = d.id_warna
						 AND a.id_detailproduk = '$id_detailproduk'");
	$ddetailproduk = mysql_fetch_array($qdetailproduk);
	?>
	<div class="spesifikasiukuran">
	<?php spesifikasiview($kategori); ?>
	</div>
    <form action="" method="post" class="niceform" id="registrationform">
        <table>
            <tr>
                <td><label for="produk">produk :</label></td>
                <td><b><?php echo $dtprod['nama_produk']; ?></b>
                </td>
            </tr>
            <tr>
                <td><label for="ukuran">Ukuran :</label></td>
                <td>
                	<select name="id_ukuran" id="ukuran">
                    	<?php
						$qukuran = mysql_query("SELECT * FROM ukuran 
								   WHERE kategori='$kategori'");
						while($dukuran = mysql_fetch_array($qukuran)){
						?>
                        	<option value="<?php echo $dukuran['id_ukuran']; ?>" <?php echo($dukuran['id_ukuran'] == $ddetailproduk['id_ukuran'])?"selected":""; ?>><?php echo $dukuran['nama_ukuran']; ?></option>
                        <?php
						}
						?>
                    </select>
                </td>
            </tr>
            <tr>
                <td><label for="warna">Warna :</label></td>
                <td>
                	<select name="id_warna" id="warna">
                    	<?php
						$qwarna = mysql_query("SELECT * FROM warna");
						while($dwarna = mysql_fetch_array($qwarna)){
						?>
                        	<option value="<?php echo $dwarna['id_warna']; ?>" <?php echo($dwarna['id_warna'] == $ddetailproduk['id_warna'])?"selected":""; ?>><?php echo $dwarna['nama_warna']; ?></option>
                        <?php
						}
						?>
                    </select>
                </td>
            </tr>
            <tr>
                <td><label for="stok">Stok :</label></td>
                <td><input name="stok" id="stok" size="3" maxlength="6" value="<?php echo $ddetailproduk['stok_detailproduk']; ?>" /></td>
            </tr>
            <tr>
                <td><label for="berat">Berat :</label></td>
                <td><input name="berat" id="berat" size="3" maxlength="6" value="<?php echo $ddetailproduk['berat_detailproduk']; ?>" />&nbsp;<strong>Kg</strong></td>
            </tr>
        	<tr>
            	<td colspan="2" align="center"><input type="hidden" name="id_detailproduk" value="<?php echo $id_detailproduk; ?>" />
                	<input type="submit" name="update" value="Ubah" /><input type="reset" name="reset" value="Batal" onClick="window.location = '?page=detailproduk&idp=<?php echo $idp; ?>';" />
                </td>
            </tr>
        </table>
	</form>
    <?php
	}
}
else{
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
<table>
<tr>
    <td>Kode Produk</td>
    <td width="100px">: <?php echo $dtprod['kode_kategori'].$idp; ?></td>
<?php
$qgbr=mysql_query("SELECT * FROM gambar WHERE id_produk=$idp LIMIT 6");
while ($g=mysql_fetch_array($qgbr)){
?>
  <td width="70" height="80" rowspan="4">
  <img src="../images/product/<?php echo $g['nama_gambar']; ?>" height="80" /></td>
<?php } ?>
  </tr>
  <tr>
    <td>Nama Produk </td>
    <td>: <?php echo $dtprod['nama_produk']; ?></td>
  </tr>
  <tr>
    <td>Harga</td>
    <td>: <?php echo "Rp. ".number_format($dtprod['harga_produk'],2,",",".");?></td>
  </tr>
</table>
<table width="592" id="rounded-corner">
    <thead>
    	<tr>
        	<th width="51" class="rounded-company" scope="col">No</th>
            <th width="100" class="rounded" scope="col">Warna</th>
            <th width="100" class="rounded" scope="col">Ukuran</th>
            <th width="200" class="rounded" scope="col">Tanggal Input</th>
            <th width="50" class="rounded" scope="col">Berat</th>
            <th width="50" class="rounded" scope="col">Admin</th>
            <th width="50" class="rounded" scope="col">Stok</th>
            <th width="42" class="rounded" scope="col">Ubah</th>
            <th width="45" class="rounded-q4" scope="col">Hapus</th>
        </tr>
    </thead>
        <tfoot>
    </tfoot>
    <tbody>
    	<?php
		$no = 0;
		$qdetailproduk = mysql_query("SELECT * FROM detailproduk a, produk b, ukuran c, warna d, admin e
									WHERE a.id_produk = b.id_produk
									AND a.id_ukuran = c.id_ukuran
									AND a.id_admin = e.id_admin
									AND a.id_warna = d.id_warna
									AND b.id_produk='$idp'
									ORDER BY a.id_produk LIMIT $posisi,$batas");
		$kolom=1;
		$i=0;
		$no = $posisi+1;
		while($ddetailproduk = mysql_fetch_array($qdetailproduk)){
			if ($i >= $kolom){
				echo "<tr class='row$ddetailproduk[id_detailproduk]'>";
			}
		?>
        	<td><?php echo $no; ?></td>
            <td><?php echo $ddetailproduk['nama_warna']; ?></td>
            <td><?php echo $ddetailproduk['nama_ukuran']; ?></td>
            <td><?php echo tgl_indo($ddetailproduk['tanggal_detailproduk']); ?></td>
            <td><?php echo $ddetailproduk['berat_detailproduk']; ?> Kg</td>
            <td><?php echo $ddetailproduk['nama_admin']; ?></td>
            <td><?php echo $ddetailproduk['stok_detailproduk']; ?> Pcs</td>
            <td>
            	<a href="?page=detailproduk&act=edit&idb=<?php echo $ddetailproduk['id_detailproduk']; ?>&idp=<?php echo $idp; ?>">
                	<img src="images/user_edit.png" alt="" title="" border="0" />
                </a>
            </td>
            <td width="45">
            <?php
			$brg=mysql_query("SELECT * FROM detail_pembelian WHERE id_detailproduk=$ddetailproduk[id_detailproduk]");
			if (mysql_num_rows($brg)==0){?>
            	<a href="<?php echo $ddetailproduk['id_detailproduk']; ?>" id="detailproduk" class="ask">
                	<img src="images/trash.png" alt="" title="" border="0" />
                </a>
            <?php }
			else {
		echo "<img src=images/trash2.png  title='produk $ddetailproduk[id_produk] tidak bisa dihapus,digunakan tabel lain' border=0 />";
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

	<a href="?page=detailproduk&act=add&idp=<?php echo $idp;?>" class="buton">Tambah Detail produk</a>
<a href="?page=produk" class="buton">Kembali</a>    <br /><br /><br />
    <hr width="800px" align="left"/>
    <h4>Tambah Gambar</h4>
    <form method="post" action="" class="niceform" enctype="multipart/form-data">
   	<table>
   		<tr>
        	<td>
            	<div id="gambarupload">
                    <table>
                        <tr>
                            <td width="99"><label for="upload">Upload :</label></td>
                            <td><input type="file" name="upload[]" id="upload" /></td>
                        </tr>
                    </table>
            	</div>
            	<div id="app"></div>
            </td>
        </tr>
        <tr>
        	<td>
    			<a href="#" id="tambahbtn"><img src="images/tambah.png" border="0" title="Tambah Upload"/></a>
            </td>
    	</tr>
        <tr align="center">
        	<td>
            	<input type="submit" name="tambah" value="Simpan Gambar" />
            </td>
    	</tr>
    </table>
    </form>
    <script>
		$("#tambahbtn").click(function(){
			$("#gambarupload").clone().appendTo('#app');					   
		});
	</script>

<div class="pagination">
	<?php
	$tampil2 = mysql_query("SELECT * FROM detailproduk as a, produk as b, ukuran as c, warna as d
							WHERE a.id_produk = b.id_produk
							AND a.id_ukuran = c.id_ukuran
							AND a.id_warna = d.id_warna
							$sqlquery
							AND b.id_produk='$idp'");
	$jmldata = mysql_num_rows($tampil2);
	$jmlhal  = ceil($jmldata/$batas);
		
	echo "<div class=paging>";
	// Link ke halaman sebelumnya (previous)
	if($halaman > 1){
		$prev=$halaman-1;
		echo "<span class=prevnext><a href='$_SERVER[PHP_SELF]?page=detailproduk&halaman=$prev'>Prev</a></span> ";
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
	  $angka .= "<a href='$_SERVER[PHP_SELF]?page=detailproduk&halaman=$i'>$i</a> ";
	}
	
	$angka .= "<span class=current>$halaman</span> ";
	for($i=$halaman+1;$i<($halaman+3);$i++)
	{
	  if ($i > $jmlhal) 
		  break;
	  $angka .= "<a href='$_SERVER[PHP_SELF]?page=detailproduk&halaman=$i'>$i</a> ";
	}
	
	$angka .= ($halaman+2<$jmlhal ? " ...  
			  <a href='$_SERVER[PHP_SELF]?page=detailproduk&halaman=$jmlhal'>$jmlhal</a> " : " ");
	
	echo "$angka ";	
	// Link kehalaman berikutnya (Next)
	if($halaman < $jmlhal){
		$next=$halaman+1;
		echo "<span class=prevnext><a href='$_SERVER[PHP_SELF]?page=detailproduk&halaman=$next'>Next</a></span>";
	}
	else{ 
		echo "<span class=disabled>Next</span>";
	}
	
	echo "</div>";
}
	?>
     
     <h2>&nbsp;</h2>
