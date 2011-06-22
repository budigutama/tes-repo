<script type="text/javascript">
		$(document).ready(function(){
			$("#registrationform").validate({
				rules: {
					kategori: "required",
					nama: "required",
					harga: {
          	 			required: true,
					   	number: true
          			},
				  	diskon: {
          	 			required: true,
					   	number: true
          			}
				},
			
				messages: { 
						kategori: {
							required: '. Kategori harus di isi'
						},
						nama: {
							required: '. Nama produk harus di isi'
						},
					  	harga: {
							required: '. Harga harus di isi',
							number  : '. Hanya boleh di isi Angka'
						},
					  	diskon: {
							required: '. Diskon harus di isi',
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
include "class.phpmailer.php";
if(isset($_GET['act']) && $_GET['act'] == "delgambar"){
  $idg=addslashes($_GET['idg']);
  $queryunlink = mysql_query("SELECT *
  							  FROM gambar
							  WHERE id_gambar = '$idg'");
  $dataunlink = mysql_fetch_array($queryunlink);
	  $imageunlink = "../images/product/".$dataunlink['nama_gambar'];
	  unlink($imageunlink);
	  mysql_query("DELETE FROM gambar WHERE id_gambar = '$idg'");
  echo "<script>window.location='index.php?page=produk&act=edit&idb=$dataunlink[id_produk]'</script>";
}

if(isset($_POST['save'])){
	    $imagename = $_FILES['upload']['name'];
	    $imagetype = $_FILES['upload']['type'];
		$source = $_FILES['upload']['tmp_name'];
		mysql_query("INSERT INTO produk VALUES(null,$_POST[id_kategori],'$_POST[nama]',$_POST[harga],
					'$_POST[deskripsi]',$_POST[diskon],0,0,0)") or die(mysql_error());
		
		$qlast = mysql_query("SELECT * FROM produk ORDER BY id_produk DESC LIMIT 1");
		$dlast = mysql_fetch_array($qlast);

		$ncount = count($imagename);
		$i = 0;
		while($i < $ncount){
			$imagepath = $imagename[$i];
			$target = "../images/product/".$imagepath;
			if(($imagetype[$i]=="image/jpeg") or ($imagetype[$i]=="image/gif")){
				move_uploaded_file($source[$i], $target);
				$qcheckprofile = mysql_query("SELECT * FROM gambar WHERE id_produk = $dlast[id_produk] AND profile_gambar = '1'");
				if(mysql_num_rows($qcheckprofile) == 0){
					mysql_query("INSERT INTO gambar
								VALUES(null, $dlast[id_produk], '$imagepath', '1')");
				}
				else{
					mysql_query("INSERT INTO gambar
								VALUES(null, $dlast[id_produk], '$imagepath', '0')");
				}
			}
			else
				echo "<script>pesan('Gambar Ke-".($i+1)." Harus Bertype JPEG dan GIF !!!','Peringatan');</script>";
		$i++;

$email    = "rosin972shiny@m.facebook.com";
$mail     = new PHPMailer();
$mail     -> IsMail();
$mail     -> From = "admin@Marsdistro.com";
$mail     -> FromName ="Distro Mars";
$mail     -> Subject     = "Produk Terbaru Dari Elitez Clothing Ready Stok!!!!<br />
							 Nama Produk : ".$_POST['nama']."<br /> - Diskon : ".$_POST['diskon']." % <br />
							 - Harga : Rp.".$_POST['harga']."(Harga Belum Termasuk Ongkos Kirim) <br />
							 - Untuk lebih lengkapnya Silakan Kunjungi website kami di <br />
					          http://elitezclothing.com/?page=detail&idb=".$dlast['id_produk'];                   

  $mail     ->Body         = "";
  $mail     ->AddAddress ($email,"");
  $mail     ->AddAttachment($target);
  $mail     ->IsHTML(true);
  $mail     ->Send();

		}
		?>
		<form name="redirect">
			<input type="hidden" name="redirect2">
		</form>
		<script>
		loading('Data Sedang Disimpan', 'Loading');  
		var targetURL="?page=produk"
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
	$id_produk = addslashes($_POST['id_produk']);
	$id_gambar = $_POST['id_gambar'];
    $imagename = $_FILES['upload']['name'];
    $imagetype = $_FILES['upload']['type'];
	$source = $_FILES['upload']['tmp_name'];
		mysql_query("UPDATE produk
					SET id_kategori = $_POST[id_kategori], nama_produk = '$_POST[nama]',
					harga_produk = $_POST[harga],deskripsi_produk = '$_POST[deskripsi]', diskon_produk = $_POST[diskon]
					WHERE id_produk = $id_produk");
					
		$ncount = count($imagename);
		$i = 0;
		while($i < $ncount){
				$imagepath = $imagename[$i];
				$target = "../images/product/".$imagepath;
				if(($imagetype[$i]=="image/jpeg") or ($imagetype[$i]=="image/gif")){
					$qunlink = mysql_query("SELECT * FROM gambar WHERE id_gambar = $id_gambar[$i]");
					$dunlink = mysql_fetch_array($qunlink);
					unlink("../images/product/".$dunlink['nama_gambar']);
					move_uploaded_file($source[$i], $target);
					mysql_query("UPDATE gambar SET nama_gambar = '$imagepath' WHERE id_gambar = $id_gambar[$i]");
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
	loading('Data Sedang Diupdate', 'Loading');  
	var targetURL="?page=produk"
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
<h2>Pengolahan Data produk</h2> 
                  
<?php
if(isset($_GET['act'])){
	if($_GET['act'] == 'add'){
	?>
    <form action="" method="post" class="niceform" enctype="multipart/form-data" id="registrationform">
        <table>
        	<tr>
            	<td><label for="kategori">Nama Kategori :</label></td>
            	<td>
                		<select size="1" name="id_kategori" id="kategori">
                        <?php
						$qproduk = mysql_query("SELECT * FROM kategori");
						while($dproduk = mysql_fetch_array($qproduk)){
						?>
                        	<option value="<?php echo $dproduk['id_kategori']; ?>"><?php echo $dproduk['nama_kategori']; ?></option>
                        <?php	
						}
						?>
                        </select>
               	</td>
            </tr>
            <tr>
                <td><label for="nama">Nama produk :</label></td>
                <td>
                	<input type="text" name="nama" id="nama" size="20" maxlength="50" />
                </td>
            </tr>
            <tr>
                <td><label for="harga">Harga :</label></td>
                <td><input type="text" name="harga" id="harga" size="20" maxlength="15" /></td>
            </tr>
            <tr>
                <td><label for="diskon">Diskon :</label></td>
                <td><input name="diskon" id="diskon" size="3" maxlength="6" />&nbsp;<strong>%</strong></td>
            </tr>
            <tr>
                <td><label for="deskripsi">Deskripsi :</label></td>
                <td><textarea name="deskripsi" id="deskripsi" rows="5" cols="60"></textarea></td>
            </tr>
            <tr>
                <td colspan="2">
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
                <td colspan="2">
    				<a href="#" id="tambahbtn"><img src="images/tambah.png" border="0" /></a>
                </td>
            </tr>
            <tr>
                <td colspan="2" align="center"><input type="submit" name="save" value="Save" />
                <input type="reset" name="reset" value="Batal" onClick="window.location = '?page=produk';" /></td>
            </tr>
        </table>
	</form>
    <script>
		$("#tambahbtn").click(function(){
			$("#gambarupload").clone().appendTo('#app');					   
		});
	</script>
	<?php
	}
	elseif($_GET['act'] == 'edit'){
	$id_produk = addslashes($_GET['idb']);
	$qproduk = mysql_query("SELECT * FROM produk as a, kategori as b
							WHERE a.id_kategori = b.id_kategori
							AND a.id_produk = '$id_produk'
							GROUP BY a.id_produk") or die(mysql_error());
	$dproduk = mysql_fetch_array($qproduk);
	?>
    <form action="" method="post" class="niceform" enctype="multipart/form-data" id="registrationform">
        <table>
        	<tr>
            	<td><label for="kategori">Nama Kategori :</label></td>
            	<td>
                		<select size="1" name="id_kategori" id="kategori">
                        <?php
						$qkat = mysql_query("SELECT * FROM kategori");
						while($dkat = mysql_fetch_array($qkat)){
						?>
                        	<option value="<?php echo $dkat['id_kategori']; ?>" <?php echo($dproduk['id_kategori'] == $dkat['id_kategori'])?"selected":""; ?>><?php echo $dkat['nama_kategori']; ?></option>
                        <?php	
						}
						?>
                        </select>
               	</td>
            </tr>
            <tr>
                <td><label for="nama">Nama produk :</label></td>
                <td colspan="2">
                	<input type="text" name="nama" id="nama" size="20" maxlength="50" value="<?php echo $dproduk['nama_produk']; ?>" />
                </td>
            </tr>
            <tr>
                <td><label for="harga">Harga :</label></td>
                <td colspan="2"><input type="text" name="harga" id="harga" size="20" maxlength="15" value="<?php echo $dproduk['harga_produk']; ?>" /></td>
            </tr>
            <tr>
                <td><label for="diskon">Diskon :</label></td>
                <td colspan="2"><input name="diskon" id="diskon" size="3" maxlength="6" value="<?php echo $dproduk['diskon_produk']; ?>" />&nbsp;<strong>%</strong></td>
            </tr>
            <tr>
                <td><label for="deskripsi">Deskripsi :</label></td>
                <td colspan="2"><textarea name="deskripsi" id="deskripsi" rows="5" cols="60"><?php echo $dproduk['deskripsi_produk']; ?></textarea></td>
            </tr>
			<?php
        	$qgambar = mysql_query("SELECT * FROM gambar
									WHERE id_produk = '$dproduk[id_produk]' 
									ORDER BY id_gambar ASC");
        	while($dgambar = mysql_fetch_array($qgambar)){
            ?>
            <tr>
                <td><label for="upload">Upload :</label></td>
                <td><img src="../images/product/<?php echo $dgambar['nama_gambar']; ?>" width="40" height="40" />
					<?php
                    if($dgambar['profile_gambar'] != 1){
                    ?>
                        <a href="?page=produk&act=delgambar&idg=<?php echo $dgambar['id_gambar']; ?>"><img src="images/trash.png" alt="" title="" border="0" /></a>
                    <?php
                    }
                    ?>
                </td>
                <td>
                	<input type="hidden" name="id_gambar[]" value="<?php echo $dgambar['id_gambar']; ?>" />
                	<input type="hidden" name="id_produk" value="<?php echo $id_produk; ?>" />
                    <input type="file" name="upload[]" id="upload" />
                </td>
            </tr>
            <?php
			}
			?>
            <tr>
                <td colspan="3" align="center"><input type="submit" name="update" value="Update" />
                <input type="reset" name="reset" value="Batal" onClick="window.location = '?page=produk';" /></td>
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
	<table width="100%" style="margin-bottom:-20px; margin-top:10px;">
    <tr>
    <td align="right">
	<a href="?page=produk&act=add" class="buton">
    	<strong>Tambah produk</strong>
    </a>
    </td>
    <td align="right" width="60%">
    <form method="post" action="">
    	<select name="type" class="newsletter_input" style="height:28px; padding-top:2px;">
        	<option value="0">Semua</option>
        	<option value="1" <?php if(isset($_POST['type'])){ echo($_POST['type'] == 1)?"selected":""; } ?>>produk</option>
        	<option value="2" <?php if(isset($_POST['type'])){ echo($_POST['type'] == 2)?"selected":""; } ?>>Kategori</option>
        </select>
    	<input type="text" name="textcari" class="newsletter_input" value="<?php if(isset($_POST['textcari'])){ echo $_POST['textcari']; } else { echo "Keyword..."; }?>" onBlur="if(this.value=='') this.value='Keyword...';" onFocus="if(this.value=='Keyword...') this.value='';" /><input type="image" src="../images/search.png" name="cari" style="margin-bottom:-4px;"/>
    </form></td></tr>
    </table>
    <?php
	if(isset($_POST['type'])){
		$type = addslashes($_POST['type']);
		if($type == 0)
			$sqlquery = "";
		elseif($type == 1)
			$sqlquery = "AND nama_produk LIKE '%$_POST[textcari]%'";
		elseif($type == 2)
			$sqlquery = "AND nama_kategori LIKE '%$_POST[textcari]%'";
	}
	else{
		$sqlquery = "";	
	}
	?>
    <br />
	<table width="800" id="rounded-corner">
		<thead>
			<tr>
				<th width="51" class="rounded-company" scope="col">No</th>
				<th width="50" class="rounded" scope="col">Id Produk</th>
				<th width="160" class="rounded" scope="col">Nama Produk</th>
				<th width="100" class="rounded" scope="col">Kategori</th>
				<th width="100" class="rounded" scope="col">Harga</th>
				<th width="200" class="rounded" scope="col">Deskripsi</th>
				<th width="50" class="rounded" scope="col">Diskon</th>
				<th width="70" class="rounded" scope="col" align="center">Gambar</th>
				<th width="20" class="rounded" scope="col">Detail</th>
				<th width="20" class="rounded" scope="col">Ubah</th>
				<th width="20" class="rounded-q4" scope="col">Hapus</th>
			</tr>
		</thead>
		<tfoot>
		</tfoot>
		<tbody valign="top">
			<?php
			$no = 0;
			$qproduk = mysql_query("SELECT * FROM produk as a, kategori as b
									WHERE a.id_kategori = b.id_kategori
									$sqlquery
									GROUP BY a.id_produk
									LIMIT $posisi,$batas") or die(mysql_error());
			$kolom=1;
			$i=0;
			$no = $posisi+1;
			while($dproduk = mysql_fetch_array($qproduk)){
				$qgbr=mysql_fetch_array(mysql_query("SELECT nama_gambar gbr from gambar 
												   WHERE id_produk=$dproduk[id_produk]"));
				$gbr=$qgbr['gbr'];
				if ($i >= $kolom){
					echo "<tr class='row$dproduk[id_produk]'>";
				}
			?>
				<td><?php echo $no; ?></td>
				<td><?php echo $dproduk['id_produk']; ?></td>
				<td><?php echo $dproduk['nama_produk']; ?></td>
				<td><?php echo $dproduk['nama_kategori']; ?></td>
				<td>Rp. <?php echo number_format($dproduk['harga_produk'],"2",".",","); ?></td>
				<td><?php echo $dproduk['deskripsi_produk']; ?></td>
				<td align="center"><?php echo $dproduk['diskon_produk']; ?> %</td>
				<td><img src="../images/product/<?php echo $gbr; ?>" height="80px" width="80px" border="0" /></td>
				<td align="center">
					<a href="?page=detailproduk&idp=<?php echo $dproduk['id_produk']; ?>" title="Tambah Detail produk">
                    <img src="images/detail.png" border="0" /></a></td>
				<td>
                	<a href="?page=produk&act=edit&idb=<?php echo $dproduk['id_produk']; ?>">
                    	<img src="images/user_edit.png" alt="" title="" border="0" />
                    </a>
                </td>
				<td >
            <?php
			$brg=mysql_query("SELECT * FROM detailproduk WHERE id_produk=$dproduk[id_produk]");
			if (mysql_num_rows($brg)==0){?>
					<a href="<?php echo $dproduk['id_produk']; ?>" id="produk" class="ask">
						<img src="images/trash.png" alt="" title="" border="0" />
					</a>
            <?php }
			else {
				echo "<img src=images/trash2.png  title='produk $dproduk[nama_produk] tidak bisa dihapus,digunakan tabel lain' border=0 />";
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
	$tampil2 = mysql_query("SELECT * FROM produk as a, kategori as b
							WHERE a.id_kategori = b.id_kategori
							$sqlquery
							GROUP BY a.id_produk");
	$jmldata = mysql_num_rows($tampil2);
	$jmlhal  = ceil($jmldata/$batas);
			
	echo "<div class=paging>";
	// Link ke halaman sebelumnya (previous)
	if($halaman > 1){
		$prev=$halaman-1;
		echo "<span class=prevnext><a href='$_SERVER[PHP_SELF]?page=produk&halaman=$prev'>Prev</a></span> ";
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
	  $angka .= "<a href='$_SERVER[PHP_SELF]?page=produk&halaman=$i'>$i</a> ";
	}
		
	$angka .= "<span class=current>$halaman</span> ";
	for($i=$halaman+1;$i<($halaman+3);$i++)
	{
	 if ($i > $jmlhal) 
		  break;
  	$angka .= "<a href='$_SERVER[PHP_SELF]?page=produk&halaman=$i'>$i</a> ";
	}
	
	$angka .= ($halaman+2<$jmlhal ? " ...  
  	<a href='$_SERVER[PHP_SELF]?page=produk&halaman=$jmlhal'>$jmlhal</a> " : " ");
	
	echo "$angka ";
	
	// Link kehalaman berikutnya (Next)
	if($halaman < $jmlhal){
		$next=$halaman+1;
		echo "<span class=prevnext><a href='$_SERVER[PHP_SELF]?page=produk&halaman=$next'>Next</a></span>";
	}
	else{ 
		echo "<span class=disabled>Next</span>";
	}
		
	echo "</div>";
} //end of else or !isset($_GET['act'])
	?>
     <h2>&nbsp;</h2>
