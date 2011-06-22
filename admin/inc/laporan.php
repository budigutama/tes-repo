<h2>Laporan</h2><br />
    <div style="width:98%; padding-left:1%">
         <div class="spek" style="float:right;">   
            <table width="100%" cellpadding="1">
            <tr align="center"> 
            	<td colspan="3" height="30px" style="font-size:14px; font-weight:bold">Laporan Produk</td></tr>
    <form method="post" action="?page=laporanproduk">
            <tr>
            	<td width="200px">
                Bulan<br />
                <select name="bulan" class="newsletter_input" style="height:28px; padding-top:2px;">
                        <?php
                            $querybulan = mysql_query( "SELECT MONTH(tanggal_detailproduk) as bulan
                                                        FROM detailproduk
                                                        GROUP BY MONTH(tanggal_detailproduk)");
                            while($databulan = mysql_fetch_array($querybulan)){
							?>
                              <option value="<?php echo $databulan['bulan']; ?>"><?php echo getBulan($databulan['bulan']); ?></option>
                            <?php
                            }
                        ?>
                    <option value="">SEMUA</option>
                    </select>
                    <select name="tahun" class="newsletter_input" style="height:28px; padding-top:2px; width:80px;">
						<?php
                            $querytahun = mysql_query("SELECT YEAR(tanggal_detailproduk) as tahun
                                                        FROM detailproduk
                                                        GROUP BY YEAR(tanggal_detailproduk)");
                            while($datatahun = mysql_fetch_array($querytahun)){
								?>
                                	<option value="<?php echo $datatahun['tahun']; ?>"><?php echo $datatahun['tahun']; ?></option>
                                <?php
                            }
                        ?>
                    <option value="">SEMUA</option>
                    </select>
				</td>
                <td>
                Kategori<br />
                <select name="kategori" class="newsletter_input" style="height:28px; padding-top:2px; width:110px;">
						<?php
                            $querykategori = mysql_query("SELECT nama_kategori
                                                        FROM produk a, kategori b
														Where a.id_kategori=b.id_kategori
                                                        GROUP BY b.id_kategori");
                            while($kategori = mysql_fetch_array($querykategori)){
								?>
                                <option value="<?php echo $kategori['nama_kategori']; ?>"><?php echo $kategori['nama_kategori']; ?></option>
                                <?php
                            }
                        ?>
                    <option value="">SEMUA</option>
                    </select>
                </td>
                 <td><input type="submit" name="cariproduk" value="cari" class="buton" style="margin-top:20px; float:right"/></td>
            </tr>
            </form>
            <tr><td colspan="3">&nbsp;</td></tr>
            </table>  
         </div> 
         
         <div class="spek" style="clear:both; float:right; margin-top:20px;">   
            <table width="100%" cellpadding="1">
            <tr align="center"> 
            	<td colspan="2" height="30px" style="font-size:14px; font-weight:bold">Laporan Retur</td></tr>
    <form method="post" action="">
            <tr>
            	<td width="300px">
                Tanggal<br />
                <input type="text" class="newsletter_input" id="tanggal3" name="tanggal3" <?php echo(isset($_POST['tanggal1'])) ? "value='$_POST[tanggal1]'" : "" ; ?> size="12"/> s/d <input type="text" class="newsletter_input" id="tanggal4" name="tanggal4" <?php echo(isset($_POST['tanggal2'])) ? "value='$_POST[tanggal2]'" : "" ; ?> size="12"/></td>
                 <td><input type="submit" name="cariretur" value="cari" class="buton" style="margin-top:20px; float:right"/></td>
            </tr>
            </form>
            <tr><td colspan="2">&nbsp;</td></tr>
            </table>  
         </div>
               
         <div class="spek">   
            <table width="100%" cellpadding="1">
            <tr align="center"> 
            	<td colspan="3" height="30px" style="font-size:14px; font-weight:bold">Laporan Penjualan</td></tr>
            <tr>
            	<td colspan="3"><b>Laporan Perhari</b></td>
            </tr>
    <form method="post" action="?page=lap_review">
            <tr>
            	<td width="200px">
                Tanggal<br />
                <input type="text" class="newsletter_input" id="tanggal1" name="tanggal1" <?php echo(isset($_POST['tanggal1'])) ? "value='$_POST[tanggal1]'" : "" ; ?> size="7"/> s/d <input type="text" class="newsletter_input" id="tanggal2" name="tanggal2" <?php echo(isset($_POST['tanggal2'])) ? "value='$_POST[tanggal2]'" : "" ; ?> size="7"/></td>
                <td>
                Pembayaran<br />
                <select name="pembayaran_hari" class="newsletter_input" style="height:28px; padding-top:2px; width:94px;">
                        <option value="">SEMUA</option>
                        <option value="paypal">PAYPAL</option>
                        <option value="transfer">TRANSFER</option>
                    </select></td>
                 <td><input type="submit" name="carihari" value="cari" class="buton" style="margin-top:20px; float:right"/></td>
            </tr>
            <tr><td colspan="3"><hr /></td></tr>
            <tr>
            	<td colspan="3"><b>Laporan Perbulan</b></td>
            </tr>
            <tr>
            	<td>
                Bulan<br />
                    <select name="bulan" class="newsletter_input" style="height:28px; padding-top:2px;">
                        <?php
                            $querybulan = mysql_query( "SELECT MONTH(tgl_beli) as bulan
                                                        FROM pembelian
                                                        GROUP BY MONTH(tgl_beli)");
                            while($databulan = mysql_fetch_array($querybulan)){
								?>
                                	<option value="<?php echo $databulan['bulan']; ?>" <?php echo(isset($_POST['bulan']) && $_POST['bulan'] == $databulan['bulan']) ? "selected" : "" ; ?>><?php echo getBulan($databulan['bulan']); ?></option>
                                <?php
                            }
                        ?>
                    </select>
                    <select name="tahun" class="newsletter_input" style="height:28px; padding-top:2px; width:80px;">
						<?php
                            $querytahun = mysql_query("SELECT YEAR(tgl_beli) as tahun
                                                        FROM pembelian
                                                        GROUP BY YEAR(tgl_beli)");
                            while($datatahun = mysql_fetch_array($querytahun)){
								?>
                                	<option value="<?php echo $datatahun['tahun']; ?>" <?php echo(isset($_POST['tahun']) && $_POST['tahun'] == $datatahun['tahun']) ? "selected" : "" ; ?>><?php echo $datatahun['tahun']; ?></option>
                                <?php
                            }
                        ?>
                    </select>
				</td>
                <td>
                Pembayaran<br />
                <select name="pembayaran_bulan" class="newsletter_input" style="height:28px; padding-top:2px; width:94px;">
                        <option value="">SEMUA</option>
                        <option value="paypal">PAYPAL</option>
                        <option value="transfer">TRANSFER</option>
                    </select>
                 </td>
                 <td><input type="submit" name="caribulan" value="cari" class="buton" style="margin-top:20px; float:right"/></td>
            </tr>
            <tr><td colspan="3"><hr /></td></tr>
            <tr>
            	<td colspan="3"><b>Laporan Pertahun</b></td>
            </tr>
            <tr>
            	<td width="200px">
                Tahun<br />
                <select name="tahun" class="newsletter_input" style="height:28px; padding-top:2px; width:94px;">
						<?php
                            $querytahun = mysql_query("SELECT YEAR(tgl_beli) as tahun
                                                        FROM pembelian
                                                        GROUP BY YEAR(tgl_beli)");
                            while($datatahun = mysql_fetch_array($querytahun)){
								?>
                                	<option value="<?php echo $datatahun['tahun']; ?>" <?php echo(isset($_POST['tahun']) && $_POST['tahun'] == $datatahun['tahun']) ? "selected" : "" ; ?>><?php echo $datatahun['tahun']; ?></option>
                                <?php
                            }
                        ?>
                    </select>
				</td>
                <td>
                Pembayaran<br />
                <select name="pembayaran" class="newsletter_input" style="height:28px; padding-top:2px; width:94px;">
                        <option value="">SEMUA</option>
                        <option value="paypal">PAYPAL</option>
                        <option value="transfer">TRANSFER</option>
                    </select></td>
                 <td><input type="submit" name="caritahun" value="cari" class="buton" style="margin-top:20px; float:right"/></td>
            </tr>
            </form>
            </table>  
         </div></div><br />      
     <h2>&nbsp;</h2>
