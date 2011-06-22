<div class="center_title_bar">Cara Pembayaran</div>
<div class="prod_box_big">
	<div class="center_prod_box_big">
    	<H2 align="left">Cara Pembayaran</H2>
              <p align="left">1. Pembayaran dapat dilakukan dengan transfer bank ke no Rekening Dibawah Ini:
               <table>
			   <?php
                        $sqlbank = "SELECT * FROM rekening";
                        $querybank = mysql_query($sqlbank) or die(mysql_error());
                        while($databank = mysql_fetch_array($querybank)){
                            ?>
                              <tr>
                                <td rowspan='3' style='padding-left:10px;'>
                                <img src="images/<?php echo $databank['gambar_rekening']; ?>" ></td>
                                <td style='padding-left:10px;'>Atas Nama</td>
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
                </table>
</p>
              <p align="left"> 2. Pembayaran Juga dapat Menggunakan Payman Gatway Dibawah Ini <br />
              <div style="padding-left:16px"><img src="images/btn.gif"/></div>
              </p>
	</div>                
</div>