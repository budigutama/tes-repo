<?php
if(isset($_POST['konfirmasi'])){
	mysql_query("UPDATE pembelian SET status = 'konfirmasi' WHERE id_pembelian = '$_GET[id_pembelian]'");
	emailkonfirmasi($_GET['id_pembelian']);
}

if(isset($_POST['terima'])){
	mysql_query("UPDATE pembelian SET status = 'terima' WHERE id_pembelian = '$_GET[id_pembelian]'");
	emailprodukditerima($_GET['id_pembelian']);
	echo "<script>window.location = '?page=datatransaksi';</script>";
}
if(isset($_POST['kirim_resi'])){
	mysql_query("UPDATE pembelian
			   SET kirim_resi = '$_POST[kirim_resi]', status = 'kirim'
			   WHERE id_pembelian = '$_GET[id_pembelian]'") or die(mysql_error());
	emailresi($_GET['id_pembelian']);
}
?>
<h2>Pengolahan Data Transaksi</h2> 
                  
<?php
if(isset($_GET['act'])){
	if($_GET['act'] == 'edit'){
  $id_pembelian=addslashes($_GET['id_pembelian']);
  $qcek = mysql_query("SELECT * FROM pembelian WHERE id_pembelian = '$id_pembelian'");
  $dcek = mysql_fetch_array($qcek);
  if($dcek['id_rekening'] == 0){  //belum bayar
	$sqlcart = "SELECT *
				FROM pembelian as a, detail_pembelian as b, detailproduk as c, produk as d,
				warna as f, ukuran as g, kota as i, provinsi as j
				WHERE a.id_pembelian = b.idpembelian
				AND b.id_detailproduk = c.id_detailproduk
				AND c.id_produk = d.id_produk
				AND c.id_warna = f.id_warna
				AND c.id_ukuran = g.id_ukuran
				AND i.id_provinsi = j.id_provinsi
				AND a.id_pembelian = $id_pembelian
				GROUP BY b.id_detailproduk";
  }
  else{
	$sqlcart = "SELECT *
				FROM pembelian as a, detail_pembelian as b, detailproduk as c, produk as d,
				warna as f, ukuran as g, kota as i, provinsi as j, rekening as k
				WHERE a.id_pembelian = b.idpembelian
				AND b.id_detailproduk = c.id_detailproduk
				AND c.id_produk = d.id_produk
				AND c.id_warna = f.id_warna
				AND c.id_ukuran = g.id_ukuran
				AND i.id_provinsi = j.id_provinsi
				AND a.id_rekening = k.id_rekening
				AND a.id_pembelian = $id_pembelian
				GROUP BY b.id_detailproduk";	  
  }
  $ambildata=mysql_query($sqlcart) or die(mysql_error());
  $datakirim=mysql_fetch_array($ambildata);
?>
<h3 style="margin-left:10px; font-size:16px; color:#333"> Status Transaksi : Di <?php echo $datakirim['status']; ?></h3>
    <div style="width:98%; padding-left:1%">
         <div class="spek" style="float:right;">   
            <table width="100%" cellpadding="2">
            <tr align="center"> 
            	<td colspan="2" style="font-size:14px; font-weight:bold">DETAIL PEMBAYARAN</td></tr>
            <tr>
            	<td width="110px">Jenis Pembayaran</td>
            	<td> : <?php echo strtoupper($datakirim['pembayaran']);?></td>
            </tr>
            <tr>
            	<td width="110px">Pengirim</td>
            	<td> : <?php echo $datakirim['transfer_bank'];?></td>
            </tr>
            <tr>
            	<td width="110px">No. Transaksi </td>
            	<td> : <?php echo $datakirim['transfer_no']; ?></td>
            </tr>
            <tr>
            	<td width="110px">Tanggal Bayar </td>
            	<td> : <?php echo tgl_indo($datakirim['tgl_bayar']); ?></td>
            </tr>
            <?php 
			if ($datakirim['id_rekening']!=''){
				$rek=mysql_fetch_array(mysql_query("SELECT * FROM rekening WHERE id_rekening=$datakirim[id_rekening]"));
			?>
            <tr>
            	<td width="110px">Bank Penerima</td>
            	<td> : <?php echo $rek['bank_rekening']." - ".$rek['cabang_rekening'];?></td>
            </tr>
            <tr>
            	<td width="110px">No. Rekening</td>
            	<td> : <?php echo $rek['no_rekening']." a.n. ".$rek['nama_rekening'];?></td>
            </tr>
                        <?php
                            }
                        ?>
            </table>  
         </div>      
         <div class="spek">   
            <table width="100%" cellpadding="2">
            <tr align="center"> 
            	<td colspan="2" style="font-size:14px; font-weight:bold">ALAMAT PENGIRIMAN PAKET</td></tr>
            <tr>
            	<td width="110px">Nama Penerima</td>
            	<td> :<?php echo $datakirim['kirim_nama'];?></td>
            </tr>
            <tr>
            	<td width="110px">Alamat </td>
            	<td> :<?php echo $datakirim['kirim_alamat'];
					$kota=mysql_fetch_array(mysql_query("SELECT * FROM kota a, provinsi b
														WHERE a.id_provinsi=b.id_provinsi
														AND a.id_kota=$datakirim[kirim_kota]"));
					echo "   $kota[nama_kota] - $kota[nama_provinsi]";?></td>
            </tr>
            <tr>
            	<td width="110px">Kode Pos</td>
            	<td> :<?php echo $datakirim['kirim_kdpos'];?></td>
            </tr>
            <tr>
            	<td width="110px">No. telepon</td>
            	<td> :<?php echo $datakirim['kirim_telp'];?></td>
            </tr>
            <tr>
            	<td width="110px">Cetak Label</td>
                <td> <form method="post" action="laporan/shiping.php" target="_blank">
                    		<input type="hidden" name="iddp" value="<?php echo $datakirim['id_pembelian']; ?>" />
                        	: <input type="image" src="images/pdf.gif" />
                        </form></td>
            </tr>
            <tr>
            	<td colspan="2"><strong>Peket dikirim dengan :</strong></td>
            </tr>
            <tr>
            	<td width="110px">Jenis Pengiriman</td>
            	<td> :<?php
			$kirim=mysql_fetch_array(mysql_query("SELECT * FROM jasapengiriman a, jenispengiriman b
												WHERE a.id_jasapengiriman=b.id_jasapengiriman
												AND b.id_jenispengiriman=$datakirim[kirim_id]")); 
					echo "$kirim[nama_jasapengiriman] - jenis $kirim[nama_jenispengiriman]";?></td>
            </tr>
                        <?php
                        if($datakirim['kirim_resi'] != ''){
                        ?>
            <tr align="left">
               <td>No Resi </h3></td>
               <td>: <b><?php echo $datakirim['kirim_resi']; ?></b></td>
            </tr>
                        <?php
                        }
                        ?>
            </table>  
         </div></div><br />      
<div class="alamat">   
<table width="100%" id="rounded-corner">
  <tr>
    <th width="24">No.</th>
    <th width="125">Nama produk</th>
    <th width="120">Harga</th>
    <th width="40">Berat</th>
    <th width="40">Jumlah</th>
    <th width="130">Total</th>
  </tr><?php
$no=0;
$ambildata=mysql_query($sqlcart) or die(mysql_error());
$total = 0;
$qb = 0;
while($data=mysql_fetch_array($ambildata)){
  $no++;
?>
  <tr valign="center">
    <td align="center"><?php echo $no;?></td>
    <td><?php echo $data['nama_produk']; ?><br />
         <strong><em>Warna :</em></strong>
         <?php echo $data['nama_warna']; ?><br />
         <strong><em>Ukuran :</em></strong>
         <?php echo $data['nama_ukuran']; ?>
    </td>
    <td width="22" align="center">
	<?php if($data['diskon_produk']>0){ ?>
        <span style="text-decoration:line-through; font-weight:bold;">
    	Rp. <?php echo number_format($data['harga_produk'],"2",".",","); ?></span><br />
    	Diskon <?php echo "$data[diskon_produk] % <br /> ";  } ?>
        <span style="font-weight:bold; color:#F00;">
        Rp. <?php echo number_format($data['hargabeli'],"2",".",","); ?></span>
    </td>
    <td width="22" align="center"><?php echo $data['berat'];?></td>
    <td width="22" align="center"><?php echo $data['qty'];?></td>
    <td width="22" align="right"><?php echo  "Rp ".number_format($data['qty'] * $data['hargabeli'],"2",",",".");?></td>
	</tr>
<?php
$total = $total + ($data['qty'] * $data['hargabeli']);
$qb = $qb + ($data['qty']*$data['berat']);
$transaksinota = $data['transfer_no'];
$banknota = $data['transfer_bank'];
}
?>
<tr><td colspan="6"><hr /></td></tr>
		<tr>
        <td colspan="2" align="left"> <strong>Subtotal</strong>
		</td>
            <td colspan="3" align="center">&nbsp;
            </td>
            <td align="right">
                <span style="font-weight:bold;">Rp. <?php echo number_format($total,"2",",","."); ?></span>
            </td>
		</tr>
				<tr>
                    <td colspan="2" align="left">
                    <strong>Ongkos Kirim</strong>
                    </td>
                    <td colspan="3" align="center">(
                    <?php
                    echo (int)ceil($qb)." Kg  x  ";
                    $ongkir=((int)ceil($qb) * $datakirim['kirim_ongkos']);?>
                     Rp. <?php echo number_format($datakirim['kirim_ongkos'],"2",",","."); ?> )
                    </td>
                    <td align="right" style="font-weight:bold">
                      Rp. <?php echo number_format($ongkir,"2",",","."); ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" align="left">
                    <strong>Total Bayar</strong>
                    </td>
                    <td colspan="3" align="center">&nbsp;
                    </td>
                    <td align="right" style="color:#F00; font-weight:bold;">
                        Rp. <?php echo number_format(($total + $ongkir),"2",",","."); ?>
                    </td>
                </tr>
</table>
</div>
<form action="" method="post">
	<input type="hidden" name="id_pembelian" value="<?php echo $id_pembelian;?>" />
    <?php
	if($datakirim['status'] == 'konfirmasi'){
	?>
    <strong>Kirim Resi  :</strong><input type="text" name="kirim_resi" class="newsletter_input"/><br />
	<input type="submit" name="save" value="Simpan" class="buton" />
	<input type="button" name="batal" value="Batal" class="buton" onClick="javasvript:history.back(-1)" />
    <?php
	}
	?>
</form><br />

<?php
if($datakirim['status'] == 'bayar'){
?>
<form method="post" action="">
	<input type="submit" name="konfirmasi" value="Konfirmasi" class="buton" />
</form>
<?php
}
elseif($datakirim['status'] == 'kirim'){
?>
<form method="post" action="">
	<input type="submit" name="terima" value="Diterima" class="buton" />
</form>
<?php
}
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
    <td align="left" width="50%">
    </td>
    <form method="post" action="">
    <td>
      Tanggal<br />
      <input type="text" id="tanggal1" class="newsletter_input" name="tanggal" <?php echo(isset($_POST['tanggal'])) ? "value='$_POST[tanggal]'" : "" ; ?> />
    </td>
    <td>
       Status Pengiriman<br />
			<select name="status" class="newsletter_input" style="height:28px; padding-top:2px;">
                        <option value="">SEMUA</option>
                        <option value="pesan" <?php echo(isset($_POST['status']) && $_POST['status'] == 'pesan') ? "selected" : "" ; ?>>DIPESAN</option>
                        <option value="konfirmasi" <?php echo(isset($_POST['status']) && $_POST['status'] == 'konfirmasi') ? "selected" : "" ; ?>>DIKONFIRMASI</option>
                        <option value="bayar" <?php echo(isset($_POST['status']) && $_POST['status'] == 'bayar') ? "selected" : "" ; ?>>DIBAYAR</option>
                        <option value="kirim" <?php echo(isset($_POST['status']) && $_POST['status'] == 'kirim') ? "selected" : "" ; ?>>DIKIRIM</option>
                        <option value="terima" <?php echo(isset($_POST['status']) && $_POST['status'] == 'terima') ? "selected" : "" ; ?>>DITERIMA</option>
    </select>
    </td>
    <td>
       Jenis Pembayaran<br />
                    <select name="pembayaran" class="newsletter_input" style="height:28px; padding-top:2px;">
                        <option value="">SEMUA</option>
                        <option value="paypal" <?php echo(isset($_POST['pembayaran']) && $_POST['pembayaran'] == 'paypal') ? "selected" : "" ; ?>>PAYPAL</option>
                        <option value="transfer" <?php echo(isset($_POST['pembayaran']) && $_POST['pembayaran'] == 'transfer') ? "selected" : "" ; ?>>TRANSFER</option>
                        <option value="cod" <?php echo(isset($_POST['pembayaran']) && $_POST['pembayaran'] == 'cod') ? "selected" : "" ; ?>>COD</option>
     </select>
     </td>
     <td valign="bottom">                
     <input type="submit" class="buton" name="cari" value="cari" style="margin-top:20px; float:right"/></td>
            </tr>
        </table>
    </form>	  
	<table width="592" id="rounded-corner">
		<thead>
        	<tr>
				<th scope="col" class="rounded-company">No</th>
				<th scope="col" class="rounded">Id Pembelian</th>
				<th scope="col" class="rounded">Nama Member</th>
				<th scope="col" class="rounded">Tanggal Pembelian </th>
				<th scope="col" class="rounded">Jenis Pembayaran </th>
				<th scope="col" class="rounded">Status Pengiriman </th>
				<th scope="col" class="rounded">Detail</th>
        	</tr>
		</thead>
		<tfoot>
		</tfoot>
		<tbody>
			<?php
			$sqldate = "";
			if(isset($_POST['cari'])){
			$sqlstates = "";
			$sqlkind = "";
				if(!empty($_POST['tanggal'])){
					list($tanggal,$bulan,$tahun) = explode('/',$_POST['tanggal']);
					$sqldate = "AND DAY(tgl_beli) = '$tanggal'
								AND YEAR(tgl_beli) = '$tahun'
								AND MONTH(tgl_beli) = '$bulan'";
				}
				if(!empty($_POST['status'])){
					$sqlstates = " AND status = '$_POST[status]'";	
				}
				if(!empty($_POST['pembayaran'])){
					$sqlkind = " AND pembayaran = '$_POST[pembayaran]'";	
				}
				$sqldate = $sqldate.$sqlstates.$sqlkind;
			}

			$no = 0;
			$qdatapembelian = mysql_query("SELECT * FROM pembelian
										   WHERE status != 'terima'
										   $sqldate
										   ORDER BY tgl_beli DESC
										   LIMIT $posisi,$batas ");
			$kolom=1;
			$i=0;
			$no = $posisi+1;
			while($ddatapembelian = mysql_fetch_array($qdatapembelian)){
				if ($i >= $kolom){
					echo "<tr class='row$ddatapembelian[id_pembelian]'>";
				}
			$i++;
			?>
					<td><?php echo $no; ?></td>
					<td><?php echo $ddatapembelian['id_pembelian']; ?></td>
					<td><?php echo $ddatapembelian['kirim_nama']; ?></td>
					<td><?php echo tgl_indo($ddatapembelian['tgl_beli']); ?></td>
					<td><?php echo $ddatapembelian['pembayaran']; ?></td>
					<td><?php echo $ddatapembelian['status']; ?></td>
					<td>
                    <a href="?page=datatransaksi&act=edit&id_pembelian=<?php echo $ddatapembelian['id_pembelian']; ?>">
                    <img src="images/user_edit.png" alt="" title="" border="0" /></a></td>
				</tr>
			<?php
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
	$tampil2 = mysql_query("SELECT * FROM pembelian 
						   WHERE status != 'terima'
						   $sqldate");
	$jmldata = mysql_num_rows($tampil2);
	$jmlhal  = ceil($jmldata/$batas);
			
	echo "<div class=paging>";
	// Link ke halaman sebelumnya (previous)
	if($halaman > 1){
		$prev=$halaman-1;
		echo "<span class=prevnext><a href='$_SERVER[PHP_SELF]?page=datatransaksi&halaman=$prev'>Prev</a></span> ";
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
	  $angka .= "<a href='$_SERVER[PHP_SELF]?page=datatransaksi&halaman=$i'>$i</a> ";
	}
		
	$angka .= "<span class=current>$halaman</span> ";
	for($i=$halaman+1;$i<($halaman+3);$i++)
	{
	 if ($i > $jmlhal) 
		  break;
  	$angka .= "<a href='$_SERVER[PHP_SELF]?page=datatransaksi&halaman=$i'>$i</a> ";
	}
	
	$angka .= ($halaman+2<$jmlhal ? " ...  
  	<a href='$_SERVER[PHP_SELF]?page=datatransaksi&halaman=$jmlhal'>$jmlhal</a> " : " ");
	
	echo "$angka ";
	
	// Link kehalaman berikutnya (Next)
	if($halaman < $jmlhal){
		$next=$halaman+1;
		echo "<span class=prevnext><a href='$_SERVER[PHP_SELF]?page=datatransaksi&halaman=$next'>Next</a></span>";
	}
	else{ 
		echo "<span class=disabled>Next</span>";
	}
		
	echo "</div>";
} //end of else or !isset($_GET['act'])
	?>
     
     <h2>&nbsp;</h2>
