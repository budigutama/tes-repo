
<?php
$idn = addslashes($_GET['idn']);
?>
<div class="center_title_bar">View History Pembelian : <?php echo $idn; ?></div>
<div class="prod_box_big">
	<div class="center_prod_box_big">
                <?php
            $qstatus = mysql_query("SELECT * FROM detail_pembelian as a, pembelian as b
                                   WHERE a.idpembelian = b.id_pembelian
                                   AND b.id_member = '$_SESSION[id_member]'
                                   AND b.id_pembelian = $idn
                                   GROUP BY b.id_pembelian") or die(mysql_error());
            $status = mysql_fetch_array($qstatus);
            $nstatus = mysql_num_rows($qstatus);
            if($nstatus){
                    $qlastdetail_pembelian = mysql_query("SELECT *
                                                 FROM detail_pembelian as a, pembelian as b
                                                 WHERE a.idpembelian = b.id_pembelian
                                                 AND b.id_member = $_SESSION[id_member]
                                                 AND b.id_pembelian = $idn
                                                 GROUP BY b.id_pembelian
                                                 ORDER BY b.id_pembelian DESC LIMIT 1");
                    $datacustom = mysql_fetch_array($qlastdetail_pembelian);
                ?>
                    <table>
                        <tr align="left">
                            <td>Nama Pemesan</td>
                            <td>: <strong><?php echo $_SESSION['nama_member']; ?></strong></td>
                        </tr>
                        <tr align="left">
                            <td>Email</td>
                            <td>: <?php echo $_SESSION['email_member']; ?></td>
                        </tr>
                    </table>    
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
					$ongkos = $datacart['kirim_ongkos'];
  if($no%2)
  	echo "<tr align=center style='background-color:#cccccc'>";
  else
  	echo "<tr align=center style='background-color:#eeeeee'>";
        ?>
                    <td><?php echo $no; ?></td>
                    <td>
						<?php echo $datacart['nama_produk']; ?><br />
                        <strong><em>Warna :</em></strong>
                        <?php echo $datacart['nama_warna']; ?><br />
                        <strong><em>Ukuran :</em></strong>
                        <?php echo $datacart['nama_ukuran']; ?>
                    </td>
                    <td align="center"><?php echo $datacart['berat']; ?></td>
            <td align="center"><?php if($datacart['diskon_produk']>0){ ?>
        <span style="text-decoration:line-through; font-weight:bold;">
    	Rp. <?php echo number_format($datacart['harga_produk'],"2",".",","); ?></span><br />
    	Diskon <?php echo "$datacart[diskon_produk] % <br /> ";  } ?>
        <span style="font-weight:bold; color:#F00;">
        Rp. <?php echo number_format($datacart['hargabeli'],"2",".",","); ?></span></td>
                    <td align="center">
                        <?php echo $datacart['qty']; ?>
                    </td>
                    <td align="right">Rp. <?php echo number_format(($datacart['qty'] * $datacart['hargabeli']),"2",",","."); ?></td>
                </tr>
                <?php
                } //end while
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
                        Rp. <?php echo number_format($total,"2",",","."); 
						mysql_query("UPDATE pembelian SET totalbayar=$total WHERE id_pembelian=$idn");
						?>
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
            	<td> :<?php echo $datacustom['kirim_alamat'];
					$kota=mysql_fetch_array(mysql_query("SELECT * FROM kota a, provinsi b
														WHERE a.id_provinsi=b.id_provinsi
														AND a.id_kota=$datacustom[kirim_kota]"));
					echo "   $kota[nama_kota] - $kota[nama_provinsi]";?></td>
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
                        <?php
                        if($datacustom['kirim_resi'] != ''){
                        ?>
            <tr align="left">
               <td>No Resi Pengiriman </h3></td>
               <td>: <b><?php echo $datacustom['kirim_resi']; ?></b></td>
            </tr>
                        <?php
                        }
                        ?>
            </table>  
         </div>      
              <?php
			if($status['status'] == 'bayar'){
                echo "<h2>Status Pesanan Sudah Dibayar</h2>
				<p> Pesanan telah dibayar, pembayaran anda akan segera kami konfirmasi maksimal 1x24 jam.<br />
				Terima Kasih</p>";
			}
			elseif($status['status'] == 'konfirmasi'){
                echo "<h2>Status Pembayaran Sudah Dikonfirmasi</h2>
				<p>Terima kasih telah melakukan pembayaran, pembayaran anda telah konfirmasi, Selanjutnya kami akan segera 
				melakukan pengiriman ke alamat yang telah anda pilih. <br>
				Terima Kasih</p>";
			}
			elseif($status['status'] == 'kirim'){
                echo "<h2>Status Pesanan Barang Sedang Dikirim</h2>
				<p>Terima kasih telah melakukan pembayaran, pembayaran anda telah konfirmasi, Selanjutnya kami akan segera 
				melakukan pengiriman ke alamat yang telah anda pilih. <br>
				Terima Kasih</p>";
			}
			elseif($status['status'] == 'terima'){
                echo "<h2>Status Pembelian Produk Sudah Diterima, Transaksi Selesai</h2>
				<p>Terima kasih telah melakukan pembayaran, pembayaran anda telah konfirmasi, Selanjutnya kami akan segera 
				melakukan pengiriman ke alamat yang telah anda pilih. <br>
				Terima Kasih</p>";
			}
			elseif($status['status'] == 'pesan' AND $status['pembayaran']!="cod"){ ?>
            <h2>Status transaksi : Di<?php echo $status['status']?></h2>
            <p> Silahahkan lakukan konfirmasi pembayaran pada tombol konfirmasi dibawah.. Jika dalam jangka waktu 1x24 Jam belum
             melakukan pembayaran maka transaksi pembelian kami anggap dibatalkan..</p><br />
                  <button name="Konfirmasi" id="konfirmasi-btn" class="buton">Konfirmasi</button><br>
            <script>
                $("#konfirmasi-btn").click(function(){
                    $("#fieldconfirm").fadeIn();
                });
            </script>
          <div id="fieldconfirm" style="display:none;">
         <div class="alamat">   
                <table border="0">
                    <tr>
                        <td colspan="4" style='padding-left:10px;'>
                        <?php 
						require_once('paypal.inc.php');													
						$return = 'http://elitezclothing.com/?page=history';
						$cancel_return = 'http://elitezclothing.com/?page=failed';
						$notify_url = 'http://elitezclothing.com/ipn_paypal.php';				
						$button2 = new PayPalButton;													
						$button2->accountemail = 'elitez_1305707072_biz@gmail.com';						
						$button2->custom = 'my custom passthrough variable'; 							
						$button2->currencycode = 'USD';													
						$button2->class = 'paypalbutton';												
						$button2->width = '';														
						$button2->image = 'paypal.png';						
						$button2->buttonimage = 'images/btn.gif';					
						$button2->buttontext = 'I agree, proceed to Payment';							
						$button2->askforaddress = false;												
						$button2->return_url = $return;							
						$button2->ipn_url = $notify_url;								
						$button2->cancel_url = $cancel_return;
						//$button2->AddItem(item_name,quantity,price,item_code,shipping,shipping2,handling,tax);	
						//$QUANTITY_1     $PRICE_1       $ID_1 - $NAME_1
				   $sql2 = mysql_query("SELECT * from pembelian a, detail_pembelian b, produk c, detailproduk d, ukuran e, warna f
										WHERE a.id_pembelian=b.idpembelian 
										AND b.id_detailproduk=d.id_detailproduk
										AND c.id_produk=d.id_produk
										AND d.id_warna=f.id_warna
										AND d.id_ukuran=e.id_ukuran
										AND a.id_pembelian='$idn'");
						while ($sql = mysql_fetch_object($sql2)){
							$size 				= $sql->nama_ukuran;
							$warna 				= $sql->nama_warna;
							$namaproduk			= $sql->nama_produk;
							$kode				= $sql->id_produk;
							$diskon             = ($sql->diskon_produk/100);
							$hargakotor         = $sql->hargabeli;
							$diskonrp			= ($hargakotor * $diskon);
							$hargadiskon		= $hargakotor - $diskonrp;
							$harga				= number_format((konversikedolar($hargadiskon)),2);
							$qty				= $sql->qty;
							$totalberat			= $totalberat + ($berat * $qty);
							$ongkir	 			= number_format((konversikedolar((int)ceil($stokberat) * $ongkos)),2);
							$button2->AddItem($namaproduk,$qty,$harga,$kode,'','','','','Warna',$warna,'Size',$size);
						}	
						$button2->AddItem("Ongkos Kirim",'',$ongkir,'','','','','','','','Id Pembelian',$idn,'','');
						$button2->OutputButton();?>
                        </td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td colspan="2">&nbsp;</td>
                      </tr>
                    </tr>
                        <?php
                        $notaID = addslashes($_GET['idn']);
                        $sqlbank = "SELECT * FROM rekening";
                        $querybank = mysql_query($sqlbank) or die(mysql_error());
                        $nomor=0;
                        while($databank = mysql_fetch_array($querybank)){
                            $nomor++;
                            ?>
                              <tr>
                                <td rowspan='3' style='padding-left:10px;'><input type="image" src="images/<?php echo $databank['gambar_rekening']; ?>" name="jenisbank" class='<?php echo "myDiv_$nomor"; ?>' /></td>
                                <td style='padding-left:10px;'>Nama Pemilik</td>
                                <td style='padding-left:10px;'>: <?php echo $databank['nama_rekening']; ?></td>
                              </tr>
                              <tr>
                                <td style='padding-left:10px;'>No. Rekening</td>
                                <td style='padding-left:10px;'>: <?php echo $databank['no_rekening']; ?></td>
                              </tr>
                              <tr>
                                <td style='padding-left:10px;'>Cabang</td>
                                <td style='padding-left:10px;'>: <?php echo $databank['cabang_rekening']; ?></td>
                              </tr>
                              <tr>
                                <td style='padding-left:10px;'>&nbsp;</td>
                                <td style='padding-left:10px;'>&nbsp;</td>
                                <td colspan='2'>&nbsp;</td>
                              </tr>
                              <?php
                        } //end while
                        ?>
                </table></div>
        
                <script type="text/javascript">
                $(document).ready(function(){
                    $('input[name="jenisbank"]').click(function() {
                        var selected = $(this).attr("class");					
						$('.shade').fadeTo('slow', 0, function(){
							$('.shade').hide();							
							$('#' + selected).fadeTo('slow', 1, function(){});
							$('#' + selected).show();
                        });
                    });
                });
                </script>
                
                <?php
                $sqlbank = "SELECT * FROM rekening";
                $qbank = mysql_query($sqlbank);
                $noid = 0;
                while($dbank = mysql_fetch_array($qbank)){
                $noid++;
                ?>
                <div id='myDiv_<?php echo $noid; ?>' class='shade' style='display:none;'>
         <div class="alamat">   
                <h2>Form Konfirmasi Pembayaran Rekening</h2>
                    <form action="?page=confirmpay" method="post" id="registrationform<?php echo $noid; ?>">
                        <table width="100%" align="left" cellspacing="5" bgcolor="#CCCCCC">
                            <tr>
                                <td>Bank Tujuan</td>
                                 <td>:</td>
                                <td> <?php echo $dbank['bank_rekening']; ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Atas Nama</td>
                                 <td>:</td>
                                <td> <?php echo $dbank['nama_rekening']; ?>
                                </td>
                            </tr>
                            <tr>
                                <td>No. Rekening Tujuan</td>
                                  <td>:</td>
                               <td> <?php echo $dbank['no_rekening']; ?>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">&nbsp;</td>
                            </tr>
                            <tr>
                                <td>Tanggal Transfer</td>
                                  <td>:</td>
                               <td><select name="tgl">
							   <?php $t=substr($datacustom['tgl_beli'],0,10);?>
							   <option value="<?php echo $t; ?>">
							   <?php echo tgl_indo($t); ?></option>
							   <?php if ($t!=date("Y-m-d")) {
							   ?>
							   <option value="<?php echo date("Y-m-d"); ?>"><?php echo tgl_indo(date("Y-m-d")); ?></option>
							   <?php } ?>
							   </select>
                                </td>
                            </tr>
                            <tr>
                                <td>Nama Bank </td>
                                 <td>:</td>
                                <td>
                                <select name="namabank" id="namabank<?php echo $noid; ?>" onchange="lain<?php echo $noid; ?>()">
                                	<option value="">-- Pilih Bank --</option>
                                	<option value="BCA">BCA</option>
                                	<option value="Mandiri">Mandiri</option>
                                	<option value="BNI">BNI</option>
                                	<option value="HSBC">HSBC</option>
                                	<option value="Danamon">Danamon</option>
                                	<option value="BII">BII</option>
                                    <option value="lain">Bank Lain</option>
                                </select>
                   <script type="text/javascript">
                    function lain<?php echo $noid; ?>() {
                        var selected = $("#namabank<?php echo $noid; ?>").val();
						if (selected =="lain"){
	  						document.getElementById("x<?php echo $noid; ?>").innerHTML=" :<input type=text name=banklain size=10/>";
						}
						if (selected !="lain"){
	  						document.getElementById("x<?php echo $noid; ?>").innerHTML="";
						}
                    };
				   </script>
                                <span id="x<?php echo $noid; ?>"></span>
                                    <input name="idn" type="hidden" value="<?php echo $idn; ?>" />
                                    <input type="hidden" name="id_rekening" value="<?php echo $dbank['id_rekening']; ?>"/>
                                </td>
                            </tr>
                            <tr>
                                <td>No. Transaksi</td>
                                 <td>:</td>
                                <td> <input name="notransaksi" id="notransaksi" size="30" type="text" class="inputan"/></td>
                            </tr>
                            <tr>
                                <td>Jumlah Pembayaran</td>
                                 <td>:</td>
                                <td> <input name="jumlahbayar" id="jumlahbayar" size="10"  class="inputan" type="text" value="<?php echo $total; ?>" /></td>
                            </tr>
                            <tr>
                                <td colspan="2"><input type="submit" name="confirmpayment" value="Konfirmasi" id="konfirmasi<?php echo $noid; ?>" class="buton"/></td>
                            </tr>
                        </table>
                    </form>
                </div></div>
                <?php
                } //endif
       ?> </div> <?php     
	          }
			}
        else{
            echo "<h3>Tidak Ada pembelian Pembelian<h3>";
        }
        ?>
    </div>
</div>