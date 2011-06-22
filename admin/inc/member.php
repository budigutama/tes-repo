<h2>Pengolahan Data Member</h2> 
                  
<?php
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
    </td>
    <td align="right" width="70%">
    <form method="post" action="">
    	<select name="type" class="newsletter_input" style="height:28px; padding-top:2px;">
        	<option value="0">Semua</option>
        	<option value="1" <?php if(isset($_POST['type'])){ echo($_POST['type'] == 1)?"selected":""; } ?>>Nama</option>
            <option value="2" <?php if(isset($_POST['type'])){ echo($_POST['type'] == 2)?"selected":""; } ?>>Alamat</option>
            <option value="3" <?php if(isset($_POST['type'])){ echo($_POST['type'] == 3)?"selected":""; } ?>>Telephone</option>
            <option value="4" <?php if(isset($_POST['type'])){ echo($_POST['type'] == 3)?"selected":""; } ?>>Kodepos</option>
            <option value="5" <?php if(isset($_POST['type'])){ echo($_POST['type'] == 4)?"selected":""; } ?>>Email</option>
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
			$sqlquery = "AND nama_member LIKE '%$_POST[textcari]%'";
		elseif($type == 2)
			$sqlquery = "AND alamat_member LIKE '%$_POST[textcari]%'";
		elseif($type == 3)
			$sqlquery = "AND telp_member LIKE '%$_POST[textcari]%'";
		elseif($type == 4)
			$sqlquery = "AND kodepos_member LIKE '%$_POST[textcari]%'";
		elseif($type == 5)
			$sqlquery = "AND email_member LIKE '%$_POST[textcari]%'";
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
				<th width="444" class="rounded" scope="col">Kodepos</th>
				<th width="444" class="rounded" scope="col">Email</th>
				<th width="32" class="rounded" scope="col">Status</th>
				<th width="45" class="rounded-q4" scope="col">Delete</th>
			</tr>
		</thead>
		<tfoot>
		</tfoot>
		<tbody>
			<?php
			$no = 0;
			$qmember = mysql_query("SELECT * FROM member
									WHERE 1
									$sqlquery
									LIMIT $posisi,$batas");
			$kolom=1;
			$i=0;
			$no = $posisi+1;
			while($dmember = mysql_fetch_array($qmember)){
				if ($i >= $kolom){
					echo "<tr class='row$dmember[id_member]'>";
				}
			?>
				<td><?php echo $no; ?></td>
				<td><?php echo $dmember['nama_member']; ?></td>
				<td><?php echo $dmember['alamat_member']; ?></td>
				<td><?php echo $dmember['telp_member']; ?></td>
				<td><?php echo $dmember['kodepos_member']; ?></td>
				<td><?php echo $dmember['email_member']; ?></td>
				<td>
                	<a href="<?php echo $dmember['id_member']; ?>" id="publish-member" class="ask">
                	<?php
					if($dmember['status_member'] == 0){
                    	echo "<img src='images/publish_t.png' border='0' alt='aktif' title='aktif' />";
					}
					elseif($dmember['status_member'] == 1){
                    	echo "<img src='images/publish_y.png' border='0' alt='non aktif' title='non aktif' />";
					}
					?>
                    </a>
                </td>
				<td width="45">
					<a href="<?php echo $dmember['id_member']; ?>" id="member" class="ask">
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
	$tampil2 = mysql_query("SELECT * FROM member
							WHERE 1
							$sqlquery");
	$jmldata = mysql_num_rows($tampil2);
	$jmlhal  = ceil($jmldata/$batas);
			
	echo "<div class=paging>";
	// Link ke halaman sebelumnya (previous)
	if($halaman > 1){
		$prev=$halaman-1;
		echo "<span class=prevnext><a href='$_SERVER[PHP_SELF]?page=member&halaman=$prev'>Prev</a></span> ";
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
	  $angka .= "<a href='$_SERVER[PHP_SELF]?page=member&halaman=$i'>$i</a> ";
	}
		
	$angka .= "<span class=current>$halaman</span> ";
	for($i=$halaman+1;$i<($halaman+3);$i++)
	{
	 if ($i > $jmlhal) 
		  break;
  	$angka .= "<a href='$_SERVER[PHP_SELF]?page=member&halaman=$i'>$i</a> ";
	}
	
	$angka .= ($halaman+2<$jmlhal ? " ...  
  	<a href='$_SERVER[PHP_SELF]?page=member&halaman=$jmlhal'>$jmlhal</a> " : " ");
	
	echo "$angka ";
	
	// Link kehalaman berikutnya (Next)
	if($halaman < $jmlhal){
		$next=$halaman+1;
		echo "<span class=prevnext><a href='$_SERVER[PHP_SELF]?page=member&halaman=$next'>Next</a></span>";
	}
	else{ 
		echo "<span class=disabled>Next</span>";
	}
	?>
    </div>
     <h2>&nbsp;</h2>
