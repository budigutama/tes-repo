    <form  method="post" action="index.php">
    <table width="240px" cellpadding="2" cellspacing="1">
    	<tr>
        	<td><select name="urutkan" class="urut">
                 <option value="produk_terbaru">Produk Terbaru</option>
                 <option value="produk_diskon">Produk Diskon</option>
                 <option value="produk_terlaris">Produk Terlaris</option>
                 <option value="produk_lihat">Produk Most View</option>
                 </select>
            </td>
        	<td>
            <select name="kate" class="urut">
            <option value="">Semua</option>
               <?php
				$querycat = mysql_query("SELECT *
									 FROM kategori");
				while($datacat = mysql_fetch_array($querycat)){
				echo "<option value=$datacat[id_kategori]>$datacat[nama_kategori]</option>";
				} ?>
                </select>
            </td> 
            <td>
            	<input type="image" src="images/urut.png" name="urut" style="margin-bottom:-4px;"/>  </td>     
         </tr>
      </table></form>
