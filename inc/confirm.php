<div class="center_title_bar">Informasi Pemesanan</div>
<div class="prod_box_big">
	<div class="center_prod_box_big">
    <?php
if(!isset($_SESSION['confirm']))
	echo "<script>window.location = '?page=index';</script>";
        $qlastdetailpembelian = mysql_query("SELECT *
                                     FROM detail_pembelian as a, pembelian as b
                                     WHERE a.idpembelian = b.id_pembelian
                                     AND b.id_member = $_SESSION[id_member]
                                     GROUP BY b.id_pembelian
                                     ORDER BY b.id_pembelian DESC LIMIT 1");
        $datacustom = mysql_fetch_array($qlastdetailpembelian);
		$idn=$datacustom['id_pembelian'];
		$cara=$datacustom['pembayaran'];
        ?>
            <a href="?page=cart&idpr=<?php echo $idn; ?>">
            <div class="panah"><span class="step">Step 1</span><br />Keranjang Belanja</div></a>  
            <a href="?page=checkout"><div class="panah"><span class="step">Step 2</span><br />Alamat Kirim</div></a>  
            <a href="?page=kirim"><div class="panah"><span class="step">Step 3</span><br />Jenis Pengiriman</div></a>  
            <a href="?page=confirm"><div class="panah"><span class="step">Step 4</span><br />Konklusi</div></a>  
    <div style="padding-left:1%;">
         <h4>Id Pembelian :  <?php echo $idn;  ?></h4>
<div class="alamat">   
          <table border="0" cellpadding="5" cellspacing="0" width="100%" align="center">
        <tr align="center" bgcolor="#999999">
            <th>No</th>
            <th>Nama produk</th>
            <th>Berat</th>
            <th>Harga</th>
            <th>Jumlah</th>
            <th>Total</th>
        </tr>
        <?php
        $querycart = mysql_query("SELECT *
                                   FROM detail_pembelian as a, pembelian as b, detailproduk as c, produk as d, warna as e, ukuran as f
                                   WHERE a.idpembelian = b.id_pembelian
                                   AND a.id_detailproduk = c.id_detailproduk
                                   AND c.id_produk = d.id_produk
								   AND c.id_warna = e.id_warna
								   AND c.id_ukuran = f.id_ukuran
                                   AND b.id_member = $_SESSION[id_member]
                                   AND b.id_pembelian = $idn");
        $no = 0;
        $subtotal = 0;
        $stokberat = 0;
        while($datacart = mysql_fetch_array($querycart)){
            $no++;
            $subtotal = $subtotal + ($datacart['qty'] * $datacart['hargabeli']);
            $stokberat =  $stokberat + ($datacart['qty'] * $datacart['berat']);
			$ongkos=$datacart['kirim_ongkos'];
  if($no%2)
  	echo "<tr align=center style='background-color:#cccccc'>";
  else
  	echo "<tr align=center style='background-color:#eeeeee'>";
        ?>
            <td><?php echo $no; ?></td>
            <td>    
			<b><?php echo $datacart['nama_produk']; ?></b>
    <?php
      if($datacart['id_warna'] != NULL){
        $qwarna = mysql_query("SELECT * FROM warna WHERE id_warna = $datacart[id_warna]");
        $dwarna = mysql_fetch_array($qwarna);?>
        <dt><strong><em>Warna :</em></strong><?php echo $dwarna['nama_warna']; ?></dt>
        <?php  } 
         if($datacart['id_ukuran'] != NULL){
        $qukuran = mysql_query("SELECT * FROM ukuran WHERE id_ukuran = $datacart[id_ukuran]");
        $dukuran = mysql_fetch_array($qukuran); ?>
        <dt><strong><em>Ukuran :</em></strong><?php echo $dukuran['nama_ukuran']; ?></dt>
        <?php  }  ?>
</td>
            <td><?php echo $datacart['berat']; ?> Kg</td>
            <td align="center"><?php if($datacart['diskon_produk']>0){ ?>
        <span style="text-decoration:line-through; font-weight:bold;">
    	Rp. <?php echo number_format($datacart['harga_produk'],"2",".",","); ?></span><br />
    	Diskon <?php echo "$datacart[diskon_produk] % <br /> ";  } ?>
        <span style="font-weight:bold; color:#F00;">
        Rp. <?php echo number_format($datacart['hargabeli'],"2",".",","); ?></span></td>
            <td>
                <?php echo $datacart['qty']; ?> Pcs
            </td>
            <td align="right">Rp. <?php echo number_format(($datacart['qty'] * $datacart['hargabeli']),"2",",","."); ?></td>
        </tr>
        <?php
        }
        ?>
		<tr bgcolor="#FFFFFF">
        <td colspan="2" align="left"> <strong>Subtotal</strong>
		</td>
            <td colspan="3" align="center">&nbsp;
            </td>
            <td align="right">
                <span style="font-weight:bold;">Rp. <?php echo number_format($subtotal,"2",",","."); ?></span>
            </td>
		</tr>
				<tr bgcolor="#FFFFFF">
                    <td colspan="2" align="left">
                    <strong>Ongkos Kirim</strong>
                    </td>
                    <td colspan="3" align="center">(
                    <?php
                    echo (int)ceil($stokberat)." Kg  x  ";
                    $total = $subtotal + ((int)ceil($stokberat) * $ongkos);
                    ?>
                     Rp. <?php echo number_format($ongkos,"2",",","."); ?> )
                    </td>
                    <td align="right" style="font-weight:bold">
                      Rp. <?php echo number_format(((int)ceil($stokberat) * $ongkos),"2",",","."); ?>
                    </td>
                </tr>
                <tr bgcolor="#FFFFFF">
                    <td colspan="2" align="left">
                    <strong>Total Bayar</strong>
                    </td>
                    <td colspan="3" align="center">&nbsp;
                    </td>
                    <td align="right" style="color:#F00; font-weight:bold;">
                        Rp. <?php echo number_format($total,"2",",","."); ?>
                    </td>
                </tr>
      </table>
</div>	  
         <div class="alamat">   
            <table width="100%" cellpadding="2">
            <tr align="center"> 
            	<td colspan="2" style="font-size:14px; font-weight:bold">ALAMAT PENGIRIMAN PAKET</td></tr>
            <tr>
            	<td width="160px">Nama Penerima Paket</td>
            	<td> :<?php echo $datacustom['kirim_nama'];?></td>
            </tr>
            <tr>
            	<td width="160px">Alamat </td>
            	<td> :<?php echo $datacustom['kirim_alamat'];?>
               <?php
					$kota=mysql_fetch_array(mysql_query("SELECT * FROM kota a, provinsi b
														WHERE a.id_provinsi=b.id_provinsi
														AND a.id_kota=$datacustom[kirim_kota]"));
					echo "     $kota[nama_kota] - $kota[nama_provinsi]";?></td>
            </tr>
            <tr>
            	<td width="160px">Kode Pos</td>
            	<td> :<?php echo $datacustom['kirim_kdpos'];?></td>
            </tr>
            <tr>
            	<td width="160px">No. telepon</td>
            	<td> :<?php echo $datacustom['kirim_telp'];?></td>
            </tr>
            <tr>
            	<td colspan="2"><strong>Peket dikirim dengan :</strong></td>
            </tr>
            <tr>
            	<td width="160px">Jenis Pengiriman</td>
            	<td> :<?php
					$kirim=mysql_fetch_array(mysql_query("SELECT * FROM jasapengiriman a, jenispengiriman b
														WHERE a.id_jasapengiriman=b.id_jasapengiriman
														AND b.id_jenispengiriman=$datacustom[kirim_id]")); 
					echo "$kirim[nama_jasapengiriman] - jenis $kirim[nama_jenispengiriman]";?></td>
            </tr>
            <tr>
            	<td width="160px">No. Resi Pengiriman</td>
            	<td> :<?php echo $datacustom['kirim_resi'];?></td>
            </tr>
            </table>  
         </div> 
         <hr />
        Proses Order / Pemesanan selesai, info detailnya telah kami kirikan kepada email <?php echo $_SESSION['email_member']; ?>, 
        Jika ada data yang belum sesuai silahkan pilih Step ( langkah ) diatas yang ingin diperbaiki..<br />
        Selanjutnya silahkan pilih menu history untuk melakukan konfirmasi pembayaran..<br />
     
</div></div></div>

