<?php


if (!isset($_SESSION['id_member'])) {
?>
     <div class="border_box">
        <div class="title_box">Login</div>
<?php 
if(isset($_POST['login'])){
	$email = mysql_escape_string($_POST['email']);
	$password = md5($_POST['password']);
	$qchecklogin = mysql_query("SELECT * FROM member 
	WHERE email_member = '$email' AND password_member = '$password' AND status_member = '1'") 
	or die(mysql_error());
		if(mysql_num_rows($qchecklogin) !=0 ){
		$dmember = mysql_fetch_array($qchecklogin);
		$_SESSION['id_member'] = $dmember['id_member'];
		$_SESSION['email_member'] = $dmember['email_member'];
		$_SESSION['nama_member'] = $dmember['nama_member'];
		echo "<script>window.location = '?page=home';</script>";	
	}
	else{
		echo "<h3> Username dan Password Salah!!!</h3>";	
	}
}
if(isset($_POST['kirimpass'])){
	lupapassword($_POST['email']);
}

		if (isset($_POST['lupas'])){ 
		?>
        <h4> Lupa Password</h4>
     	<form action="" method="post">
            <table align="center" width="160" cellpadding="1">
              <tr>
                <td width="160" align="left">Email :
                <input type="text" name="email" id="email" class="newsletter_input" /></td>
              </tr>
              <tr>
                <td align="left">
                <input name="kirimpass" type="submit" value="Kirim Email" class="buton"/>     	</form>
                </td>
              </tr> 
              <tr>
                <td><input type="submit" onclick="window.history.back(-1);" value="kembali" style="border:0; background:#FFF; color:#F00"/></td>
              </tr>
            </table>
		<?php }
		else{
		?>  
     	<form action="" method="post">
            <table align="center" width="160" cellpadding="1">
              <tr>
                <td width="160" align="left" colspan="2">Email :
                <input type="text" name="email" id="email" class="newsletter_input" /></td>
              </tr>
              <tr>
                <td align="left" colspan="2">Password :<input type="password" name="password" id="password" class="newsletter_input" /></td>
              </tr>
              <tr>
                <td align="left">
            <input name="login" type="submit" value="Login" class="buton"/>
            </form></td><td>
             <a href="?page=register" title="Register"><input type="submit" class="buton" value="Register"/></a>
                </td>
              </tr> 
              <tr>
                <td colspan="2"><input name="lupas" type="submit" value="lupa password ??" style="border:0; background:#FFF; color:#F00"/></td>
              </tr>
            </table>
            <?php } ?>
     </div>  
     <?php
} else {
?>
     <div class="border_box">   
         <div class="title_box" align="center"><strong>Hai.. <?php echo substr($_SESSION['nama_member'],0,15); ?>.</strong></div>
         <div class="product_title" align="left"><a href="?page=editakun">Pengaturan Akun</a></div>
         <div class="product_title" align="left"><a href="?page=history">Lihat Histori</a></div>
         <div class="product_title" align="left"><a href="?page=retur">Retur Produk</a></div>
		 <div class="product_title" align="left"><a href="?page=logout">Logout</a></div>

       </div> 
   <?php } ?>
 <div class="border_box">
    <div class="title_box">Produk Berdasarkan </div>
    <div class="judul">Size :</div>
     <div class="blok">
 <?php
 $qsize=mysql_query("SELECT a.nama_ukuran size FROM detailproduk b, ukuran a
					 WHERE b.id_ukuran=a.id_ukuran
					 GROUP BY size");
 while ($size=mysql_fetch_array($qsize)){ ?>
 <a href="?page=home&size=<?php echo $size['size'];?>" title="Size <?php echo $size['size'];?>">
 <div class="center-harga"><?php echo $size['size'];?></div></a>
<?php	} ?>
 </div>
     <div class="judul">Harga :</div>
 <?php
 $hb="";
 $i=0;
 $qharga=mysql_query("SELECT c.harga_produk harga FROM detailproduk b, produk c
					 WHERE b.id_produk=c.id_produk
					 GROUP BY c.id_produk
					 ORDER BY harga");
 echo "<div class=blok>";
 while ($harga=mysql_fetch_array($qharga)){
 $h=$harga['harga'];
 if ($h<"30000"){$hr="<30rb"; $q="harga_produk  <30000";}
 elseif ($h<"40000" AND $h>="30000"){$hr="30rb";$q="harga_produk>=30000  AND harga_produk<40000";}
 elseif ($h<"50000" AND $h>="40000"){$hr="40rb";$q="harga_produk>=40000  AND harga_produk<50000";}
 elseif ($h<"60000" AND $h>="50000"){$hr="50rb";$q="harga_produk>=50000  AND harga_produk<60000 ";}
 elseif ($h<"70000" AND $h>="60000"){$hr="60rb";$q="harga_produk>=60000  AND harga_produk<70000";}
 elseif ($h<"80000" AND $h>="70000"){$hr="70rb";$q="harga_produk>=70000  AND harga_produk<80000";}
 elseif ($h<"90000" AND $h>="80000"){$hr="80rb";$q="harga_produk>=80000  AND harga_produk<90000";}
 elseif ($h<"100000" AND $h>="90000"){$hr="90rb";$q="harga_produk>=90000  AND harga_produk<100000";}
 elseif ($h<"110000" AND $h>="100000"){$hr="100rb";$q="harga_produk>=100000  AND harga_produk<110000";}
 elseif ($h<"120000" AND $h>="110000"){$hr="110rb";$q="harga_produk>=110000  AND harga_produk<120000";}
 elseif ($h<="130000" AND $h>="120000"){$hr="120rb";$q="harga_produk>=120000  AND harga_produk<130001";}
 elseif ($h>"130000") {$hr=">130rb ";$q="harga_produk  >130000";}
 if ($hb!=$hr){
 $hb=$hr; $i++;
 ?><a href="?page=home&harga=<?php echo $q;?>" title="Harga <?php echo $hb;?>"><div class="center-harga"><?php echo $hb;?></div></a> 
 <?php }
 }
 ?></div>
    <div class="judul">Warna :</div>
 <?php
 $qwarna=mysql_query("SELECT * FROM warna a, detailproduk b, produk c
					 WHERE a.id_warna=b.id_warna
					 AND b.id_produk=c.id_produk
					 GROUP BY a.id_warna");
 $jml=mysql_num_rows($qwarna);
 if ($jml<="4" AND $jml>"0"){$h="35px";}
 elseif ($jml<="8" AND $jml>"4"){$h="75px";}
 elseif ($jml<="12" AND $jml>"8"){$h="105px";}
 echo "<div class=blok style=height:$h>";
 while ($warna=mysql_fetch_array($qwarna)){
 $w=strtoupper($warna['nama_warna']);
 if ($w=="BIRU"){$c="#00C";}
 elseif ($w=="HITAM"){$c="#000";}
 elseif ($w=="COKLAT"){$c="#804000";}
 elseif ($w=="MERAH"){$c="#F00";}
 elseif ($w=="PUTIH"){$c="WHITE";}
 elseif ($w=="ABU-ABU"){$c="#888";}
 elseif ($w=="KUNING"){$c="#CF0";}
 elseif ($w=="MAGENTA"){$c=$w;}
 echo "<a href=?page=home&warna=$w title=WARNA_$w><div class=center-warna style=background:$c></div></a>";
}
 ?> </div>
 </div>
   
  <div class="border_box">
    <div class="title_box">JNE Tracking</div>  
			<form action="http://jne.co.id/index.php?mib=tracking&lang=IN" method="POST" target="_blank">      
                <table>
                    <tr>
                        <td colspan="2">
                            <img src="images/jne.png" width="140" height="40" />
                        </td>
                    </tr>
                    <tr>
                        <td align="center">
                         <input type="text" name="awbnum" id="awbnum" autocomplete="off" value="<?php if(isset($_POST['awbnum'])){ echo $_POST['awbnum']; } else { echo "JNE Airwaybill Number..."; }?>" onblur="if(this.value=='') this.value='JNE Airwaybill Number...';" onfocus="if(this.value=='JNE Airwaybill Number...') this.value='';" class="newsletter_input">
                        </td>
                        <td><input type="image" src="images/trek.png" name="submittracking" style="margin-bottom:-4px;" title="Trekking JNE"/></td>
                    </tr>
                 </table>
            </form>
     </div>
     
  <div class="border_box">
    <div class="title_box">Kurs Dollar</div>  
<?php
$usd = 'USD';
             $rupiah  = 'IDR';
             $sumber = 'http://finance.yahoo.com/d/quotes.csv?e=.csv&f=sl1d1t1&s='. $usd . $rupiah .'=X';
             $ambil = @fopen($sumber, 'r');
               if ($ambil) {
               $mentah = fgets($ambil, 4096);
               fclose($ambil); }
               $kolom = explode(',',$mentah);
               $rpdollar = $kolom[1];
?>
               <p style="font-size:12px; color:#666; font-weight:bold">1 USD = Rp. <?php echo $rpdollar; ?></p>
			   <p style="font-size:10px; color:#FF0000">*Sumber :finance.yahoo.com</p>
     </div>
     
