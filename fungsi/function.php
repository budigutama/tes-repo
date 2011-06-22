<?php
function emailkeadmin($judul,$pesan,$dari){
	$queryemail=mysql_query("SELECT * FROM admin");
	
	
	while ($email=mysql_fetch_array($queryemail)){
		$admin="$email[email_admin]";
		mail($admin,$judul,$pesan,$dari);
	}
}

function login($email,$password){
	$password = md5($password);
	$querymember = mysql_query("SELECT *
							   FROM member
							   WHERE email_member = '$email' AND password_member = '$password' AND status_member = '1'");
	if(mysql_num_rows($querymember) == 1){
		$datamember = mysql_fetch_array($querymember);
		$_SESSION['id_member'] = $datamember['id_member'];
		$_SESSION['email'] = $datamember['email_member'];
		$_SESSION['nama'] = $datamember['nama_member'];
		$qupcart = mysql_query("SELECT * FROM pembelian WHERE session_id = '".session_id()."'");
		if(mysql_num_rows($qupcart))
			mysql_query("UPDATE pembelian SET id_member = $_SESSION[id_member] WHERE session_id = '".session_id()."'");
		echo "<script>window.location = 'index.php';</script>";
	}
	else{
		?>
        <script>pesan('Maaf, account tidak ditemukan','Peringatan');</script>
        <?php
	}
}
function lupapassword($email){
	$qcheckemail = mysql_query("SELECT * FROM member WHERE email_member = '$email'");
	$ncheckemail = mysql_num_rows($qcheckemail);
	if($ncheckemail == 1){

		$code = md5(rand(0,9999999));
		mysql_query("UPDATE member SET verificationcode_member = '$code' WHERE email_member = '$email'");
		emaillupapassword($email,$code);
		echo "<h3> Verifikasi Password Baru Telah Dikirim Ke Email </h3>";
	}
	else
	{
		echo "<h3>Email Anda Tidak ditemukan </h3>";
	}	
}

function loginadmin($email,$password){
	$password = md5($password);
	$qadmin = mysql_query("SELECT *
							   FROM admin
							   WHERE email_admin = '$email' AND password_admin = '$password' AND status_login = '0'");
	if(mysql_num_rows($qadmin) == 1){
		$dadmin = mysql_fetch_array($qadmin);
		$_SESSION['id_admin'] = $dadmin['id_admin'];
		$_SESSION['email_admin'] = $dadmin['email_admin'];
		$_SESSION['nama_admin'] = $dadmin['nama_admin'];
		
		$time = time();
		$time_check = $time-600;
		mysql_query("UPDATE admin SET status_login = '1', waktu_login = $time WHERE id_admin = '$dadmin[id_admin]'") or die(mysql_error()); // Update status login
		echo "<script>window.location = 'index.php';</script>";
	}
	else{
		?>
        <h3 class="h3"> account tidak ditemukan <h3>
        <?php
	}
}

function lupasadmin($email){
	$qcheckemail = mysql_query("SELECT * FROM admin WHERE email_admin = '$email'");
	$ncheckemail = mysql_num_rows($qcheckemail);
	if($ncheckemail == 1){
		$code = md5(rand(0,9999999));
		mysql_query("UPDATE admin SET verification_admin = '$code' WHERE email_admin = '$email'");
		emaillupasadmin($email,$code);
		echo "<h3>Verifikasi Password Baru Telah Dikirim Ke Email</h3>";
	}
	else
	{
		echo "<h3>Email Anda Tidak ditemukan </h3>";
	}	
}

function viewcounter($idb){
	mysql_query("UPDATE produk SET viewcounter_produk = viewcounter_produk + 1 WHERE id_produk = $idb") or die(mysql_error());
}

function konversikedolar($uang){
             $usd = 'USD';
             $rupiah  = 'IDR';
             $sumber = 'http://finance.yahoo.com/d/quotes.csv?e=.csv&f=sl1d1t1&s='. $usd . $rupiah .'=X';
             $ambil = @fopen($sumber, 'r');
               if ($ambil) {
               $mentah = fgets($ambil, 4096);
               fclose($ambil); }
               $kolom = explode(',',$mentah);
               $rpdollar = ROUND($kolom[1],2)-20;

			return $uang/$rpdollar;
}

function hargadiskon($idb){
	$qdiskon = mysql_query("SELECT * FROM produk WHERE id_produk = $idb");
	$ddiskon = mysql_fetch_array($qdiskon);
	$harga = $ddiskon['harga_produk'];
	$diskon = $harga-(($ddiskon['diskon_produk']/100)*$harga);
	return ($diskon);
}

function get_jnestates($id_pembelian,$resi){
	$link = "http://jne.co.id/index.php?mib=tracking.detail&awb=$resi&awb_list=$resi";
	$result =  curl($link);
	if($result){
		$part = explode('Delivered', $result);
		if(count($part) == 3){
			mysql_query("UPDATE pembelian SET status = 'terima' WHERE id_pembelian = '$id_pembelian'");
			emailprodukditerima($id_pembelian);
		}
	}
}

function check_query($querystring){
	$filtered = mysql_escape_string($querystring);
	return $filtered;
}

function updatevote($idproduk,$vote){
	$s = 0;
	$qrate = mysql_query("SELECT * FROM produk WHERE id_produk = $idproduk");
	$drate = mysql_fetch_array($qrate);
	$avg = (($drate['voterrating_produk'] * $drate['rating_produk']) + $vote) / ($drate['voterrating_produk'] + 1);
	if(!isset($_SESSION['vote'][$s])){
		$updatevote = true;
	}
	else{
		$updatevote = true;
		while(isset($_SESSION['vote'][$s])){
			if($_SESSION['vote'][$s] == $idproduk){
				echo "<h3>Anda Hanya Boleh 1 Kali Mengisi Voting !! </h3>";
				$updatevote = false;
				break;
			}
		$s++;
		}
	}	
	
	if($updatevote){
		$_SESSION['vote'][$s] = $idproduk;
		$avg = (int)ceil($avg);
		mysql_query("UPDATE produk SET voterrating_produk = voterrating_produk + 1, rating_produk = $avg WHERE id_produk = $idproduk");
		echo "<h3> Terima Kasih Telah Melakukan Voting </h3>";
	}
}

#fungsi untuk konversi ke tanggal indonesia
function tgl_indo($tgl){
  $tanggal = substr($tgl,8,2);
  $bulan = getBulan(substr($tgl,5,2));
  $tahun = substr($tgl,0,4);
  return $tanggal.' '.$bulan.' '.$tahun;		 
}	
#fungsi untuk mendapatkan nama bulan
function getBulan($bln){
  switch ($bln){
    case 1: 
      return "Januari";
      break;
    case 2:
      return "Februari";
      break;
    case 3:
      return "Maret";
      break;
    case 4:
      return "April";
      break;
    case 5:
      return "Mei";
      break;
    case 6:
      return "Juni";
      break;
    case 7:
      return "Juli";
      break;
    case 8:
      return "Agustus";
      break;
    case 9:
      return "September";
      break;
    case 10:
      return "Oktober";
      break;
    case 11:
      return "Nopember";
      break;
    case 12:
      return "Desember";
      break;
  }
}

function emailkonfirmasi($id_pembelian){
	$qdetailpembelian = mysql_query("SELECT * FROM pembelian as a, rekening as b, member as c
							WHERE a.id_rekening = b.id_rekening
							AND a.id_member=c.id_member
							AND a.id_pembelian = '$id_pembelian'
							GROUP BY a.id_pembelian");
	$ddetailpembelian = mysql_fetch_array($qdetailpembelian);
	
	$kepada = "$ddetailpembelian[email_member]";
	$judul  = "[ elitezclothing.com ] Konfirmasi Pembayaran Tagihan";
			
	$ke     = "Kepada Yth. Sdr/i. $ddetailpembelian[kirim_nama],<br />
				<br />
				Pembayaran Telah kami konfirmasi.<br />
				Kami Beritahukan Bahwa Anda Telah Membayar Lunas Pemesanan Anda.<br />
				Silahkan Tunggu Nomor Resi produk Pesanan Anda karena Sedang Dalam Proses Pengiriman.";
	$keadmin = "Dear Admin,<br />
				<br />
				Pasanan dengan ID : $id_pembelian oleh member $ddetailpembelian[email_member]<br />
				Telah dikonfirmasi.";
	$pesan  = " <br />
				<br />
				Berikut adalah data konfirmasi yang anda masukkan :<br />
				<table>
					<tr>
						<td>Id Pembelian</td>
						<td>: <strong>$id_pembelian<strong></td>
					</tr>
					<tr>
						<td>Nama</td>
						<td>: $ddetailpembelian[kirim_nama]</td>
					</tr>
					<tr>
						<td>Email</td>
						<td>: $ddetailpembelian[email_member]</td>
					</tr>
					<tr>
						<td>Besar Pembayaran</td>
						<td>: Rp ".number_format($ddetailpembelian['totalbayar'],"2",",",".").",-</td>
					</tr>
					<tr>
						<td colspan='2'>Bank Tujuan</td>
					</tr>
					<tr>
						<td>Nama Bank</td>
						<td>: $ddetailpembelian[bank_rekening]</td>
					</tr>
					<tr>
						<td>No. Rekening</td>
						<td>: $ddetailpembelian[no_rekening]</td>
					</tr>
					<tr>
						<td colspan='2'>Pembayaran dari</td>
					</tr>
					<tr>
						<td>Nama Bank</td>
						<td>: $ddetailpembelian[transfer_bank]</td>
					</tr>
					<tr>
						<td>No. Transaksi</td>
						<td>: $ddetailpembelian[transfer_no]</td>
					</tr>
				</table>";
	$footer	 ="Terima kasih atas kepercayaan Anda.<br />
				elitezclothing.com<br />
				<br />
				---<br />
				elitezclothing.com<br />
			   Main Office: Jl. buah batu no. 238 Bandung Jawa Barat.<br />
			   Email: admin@elitezclothing.com<br />
			   ---";
	$dari   = "From: admin@elitezclothing.com \r\n";
	$dari  .= "Reply-To: admin@elitezclothing.com \r\n";
	$dari  .= "Content-type: text/html \r\n";
	mail($kepada,$judul,$ke.$pesan.$footer,$dari);
	emailkeadmin($judul,$ke.$pesan,$dari);
}

function emailresi($id_pembelian){
	$querycustomer = mysql_query("SELECT * FROM pembelian a, member b 
								 WHERE a.id_member=b.id_member 
								 AND a.id_pembelian = '$id_pembelian'");
	$datacustomer = mysql_fetch_array($querycustomer);
	$kepada = "$datacustomer[email_member]";
	$judul  = "[ elitezclothing.com ] Nomor Resi Paket Kiriman";
	$pesan  = "Pesanan Anda Telah Kami Kirim,<br />
			   Id Pembelian : $id_pembelian<br />
			   Nomor Resi Anda Adalah : <b>$datacustomer[kirim_resi].</b>
			   Untuk Informasi Penulusuran Paket Silahkan Cek DI sini 
			   <a href='http://www.jne.co.id/index.php?mib=tracking.detail&awb=$datacustomer[kirim_resi]'>
			   Tracking JNE</a><br><br>
			   Silahkan Tunggu Paket Kiriman Anda
			   <br /><br />
			   Terima kasih atas kepercayaan Anda Berbelanja Di website Kami.<br />
			   Salam Hangat,<br /><br />
			   elitezclothing.com<br />
			   <br />
			   ---<br />
			  elitezclothing.com<br />
			   Main Office: Jl. buah batu no. 238 Bandung Jawa Barat.<br />
			   Email: admin@elitezclothing.com<br />
			   ---";
	$pesanadmin   = "Pesanan dengan Id Pembelian : $id_pembelian, Oleh : $datacustomer[email_member] <br />
					Telah dikirim kealamat member dengan No Resi : <b>$datacustomer[kirim_resi].</b>";	
	$dari   = "From: admin@elitezclothing.com \r\n";
	$dari  .= "Reply-To: admin@elitezclothing.com \r\n";
	$dari  .= "Content-type: text/html \r\n";
	mail($kepada,$judul,$pesan,$dari);
	emailkeadmin($judul,$pesanadmin,$dari);
	
}

function emailhapuspesanan($id_pembelian){
	$querycustomer = mysql_query("SELECT * FROM pembelian a member b 
								 WHERE a.id_member=b.id_member 
								 AND id_pembelian = '$id_pembelian'");
	$datacustomer = mysql_fetch_array($querycustomer);
	$kepada = "$datacustomer[email_member]";
	$judul  = "[ elitezclothing.com ] Hapus Pesanan";
	$pesan = "Maaf, Waktu yang kami tentukan untuk konfirmasi pembayaran pesanan anda telah habis.<br />
			Data akan otomatis terhapus dari history.<br />
			Silahkan melakukan pesanan kembali.
			Terima kasih atas perhatian Anda.<br />
			elitezclothing.com<br />
			<br />
			---<br />
			elitezclothing.com<br />
			   Main Office: Jl. buah batu no. 238 Bandung Jawa Barat.<br />
			   Email: admin@elitezclothing.com<br />
			   ---";
	$pesanadmin =" Pesanan Dengan ID $datacostumer[id_pembelian], tanggal ".tgl_indo($datacostumer['tgl_beli'])." Oleh :
				  $datacostumer[email_member], Telah dibatalkan karena telah melebihi batas waktu pembayaran..
			  ";
	$dari   = "From: admin@elitezclothing.com \r\n";
	$dari  .= "Reply-To: admin@elitezclothing.com \r\n";
	$dari  .= "Content-type: text/html \r\n";
	mail($kepada,$judul,$pesan,$dari);
	emailkeadmin($judul,$pesanadmin,$dari);
}

function emailshipping($id_pembelian,$email){
	$querycustomer = mysql_query("SELECT * FROM pembelian WHERE id_pembelian = '$id_pembelian'");
	$datacustomer = mysql_fetch_array($querycustomer);
	$kepada = "$email";
	$judul  = "[ elitezclothing.com ] Daftar Pesanan";
	$ke	 	= "Terima Kasih Telah Memesan Di elitezclothing.com :";
	$keadmin= "Telah diterima satu transaksi pembalian:";
	$pesan  ="		 <br />
					 <br />
					 Berikut adalah rincian pesanan:
					 <br />
					 <table>
					 	<tr>
							<td>Id Pembelian</td>
							<td>: $id_pembelian</td>
						</tr>
					 	<tr>
							<td>Nama </td>
							<td>: $datacustomer[kirim_nama]</td>
						</tr>
					 	<tr>
							<td>Alamat </td>
							<td>: $datacustomer[kirim_alamat]</td>
						</tr>
					 	<tr>
							<td>Kodepos </td>
							<td>: $datacustomer[kirim_kdpos]</td>
						</tr>
					 	<tr>
							<td>No. Telp </td>
							<td>: $datacustomer[kirim_telp]</td>
						</tr>
					 	<tr>
							<td>Email </td>
							<td>: $email</td>
						</tr>
					 </table>
					 <table border=0 cellspacing=0 cellpadding=3 style='border:1px #666 solid'>
						<tr>
						  <th style='background-color:#999'>No.</th>
						  <th style='background-color:#999'>Nama produk</th>
						  <th style='background-color:#999'>Warna</th>
						  <th style='background-color:#999'>Ukuran</th>
						  <th style='background-color:#999'>Harga</th>
						  <th style='background-color:#999'>Berat</th>
						  <th style='background-color:#999'>Stok</th>
						  <th style='background-color:#999'>Jumlah</th>
						</tr>";
	
	$qcart = mysql_query("SELECT * FROM detail_pembelian as a, pembelian as b, detailproduk as c, produk as d, warna as e, ukuran as f
						  WHERE a.idpembelian = b.id_pembelian
						  AND a.id_detailproduk = c.id_detailproduk
						  AND c.id_produk = d.id_produk
						  AND c.id_warna = e.id_warna
						  AND c.id_ukuran = f.id_ukuran
						  AND b.id_pembelian = '$id_pembelian'");
	$no = 0;
	$total = 0;
	$ongkos = 0;
	$qb = 0;
	while($dcart = mysql_fetch_array($qcart)){
	$ongkos = $dcart['kirim_ongkos'];
	$qb = $qb + ($dcart['qty']*$dcart['berat']);
	$total = $total + ($dcart['hargabeli']*$dcart['qty']);
	$no++;
	$pesan .="  <tr align=center>
					  <td>$no.</td>
					  <td>$dcart[nama_produk]</td>
					  <td>$dcart[nama_warna]</td>
					  <td>$dcart[nama_ukuran]</td>
					  <td align='right'>Rp".number_format($dcart['hargabeli'],"2",",",".")."</td>
					  <td>$dcart[berat] KG</td>
					  <td>$dcart[qty]</td>
					  <td align='right'>Rp".number_format(($dcart['hargabeli']*$dcart['qty']),"2",",",".")."</td>
			    </tr>";
	}
	$total = $total + ($ongkos*(int)ceil($qb));
	
	$pesan .="<tr style='background-color:#ccc'>
				  <td colspan='7'>Ongkos Kirim (".(int)ceil($qb)." x Rp ".number_format($ongkos,"2",",",".").")</td>
				  <td align='right'>Rp. ".number_format(($ongkos*(int)ceil($qb)),"2",",",".")."</td>
			</tr>
			<tr>
				  <td colspan='7'>Total Bayar</td>
				  <td align='right' style=color:#F00><strong>Rp. ".number_format($total,"2",",",".")."</strong></td>
			</tr>
			</table>
			<br />";
			
	$bayaran ="Pembayaran dapat pesanan dilakukan Menggukakan metode pembayaran yang kami gunakan dibawah ini :
			<br />
			<table  border=0 cellspacing=0 cellpadding=3>
			<tr>
				<td colspan=3><a href='http://elitezclothing.com/?page=view&idn=$id_pembelian'>
				<img src='http://elitezclothing.com/images/btn.gif'></a></td>
			</tr>";
			$ambilrek=mysql_query("SELECT * FROM rekening");
			while ($rek=mysql_fetch_array($ambilrek)){
	$bayaran .="
			<tr>
              <td rowspan='3' style='padding-left:10px;'>
              <a href='http://elitezclothing.com/?page=view&idn=$id_pembelian'>
			  <img src='http://elitezclothing.com/images/$rek[gambar_rekening]'></a></td>
              <td style='padding-left:10px;'>Atas Nama</td>
              <td style='padding-left:10px;'>: $rek[nama_rekening]</td>
            </tr>
            <tr>
              <td style='padding-left:10px;'>No. Rekening</td>
              <td style='padding-left:10px;'>: $rek[no_rekening]</td>
            </tr>
            <tr>
              <td style='padding-left:10px;'>Cabang</td>
              <td style='padding-left:10px;'>: $rek[cabang_rekening]</td>
            </tr>
            <tr>
              <td style='padding-left:10px;'>&nbsp;</td>
              <td style='padding-left:10px;'>&nbsp;</td>
              <td colspan='2'>&nbsp;</td>
            </tr>"; }
	$bayaran .="
			<table>
			Untuk Konfirmasi Pembayaran dapat Dilakukan dihalaman Histori atau bisa klik pada gambar<br />
			Terima kasih atas kepercayaan Anda.<br />
			elitezclothing.com<br />
			<br />
			---<br />
			elitezclothing.com<br />
			   Main Office: Jl. buah batu no. 238 Bandung Jawa Barat.<br />
			   Email: admin@elitezclothing.com<br />
			   ---";
	$dari   = "From: admin@elitezclothing.com \r\n";
	$dari  .= "Reply-To: admin@elitezclothing.com \r\n";
	$dari  .= "Content-type: text/html \r\n";
	mail($kepada,$judul,$ke.$pesan.$bayaran,$dari);
	emailkeadmin($judul,$keadmin.$pesan,$dari);
}

function emailbayar($id_pembelian){
	$qdetailpembelian = mysql_query("SELECT * FROM pembelian as a, rekening as b, member as c
							WHERE a.id_rekening = b.id_rekening
							AND a.id_member=c.id_member
							AND a.id_pembelian = '$id_pembelian'
							GROUP BY a.id_pembelian");
	$ddetailpembelian = mysql_fetch_array($qdetailpembelian);
	
	$kepada = "$ddetailpembelian[email_member]";
	$judul  = "[ elitezclothing.com ] Pembayaran Pesanan";
			
	$ke     = "Kepada Yth. Sdr/i. $ddetailpembelian[kirim_nama],<br />
				<br />
				Terimakasih Telah Melakukan Pembayaran.<br /> 
				Kami Akan Mengirimkan Konfirmasi Kepada Anda Paling Lambat 1 x 24 Jam Melalui Email.
				Pesanan Anda Sudah Tidak Dapat Dibatalkan.
				<br />
				Berikut adalah data konfirmasi yang anda masukkan :<br />";
	$keadmin = "Dear Admin <br>
				Telah diterima konfirmasi pembayaran dari $ddetailpembelian[email_member] <br />
				Berikut adalah detail konfirmasi bembayarannya :";			
	$pesan 	=" 	<table>
					<tr>
						<td>Id Pembelian</td>
						<td>: <strong>$id_pembelian<strong></td>
					</tr>
					<tr>
						<td>Nama</td>
						<td>: $ddetailpembelian[kirim_nama]</td>
					</tr>
					<tr>
						<td>Email</td>
						<td>: $ddetailpembelian[email_member]</td>
					</tr>
					<tr>
						<td>Tanggal Pembayaran</td>
						<td>: ".tgl_indo($ddetailpembelian['tgl_bayar'])." </td>
					</tr>
					<tr>
						<td>Besar Pembayaran</td>
						<td>: Rp ".number_format($ddetailpembelian['totalbayar'],"2",",",".").",-</td>
					</tr>
					<tr>
						<td colspan='2'>Bank Tujuan</td>
					</tr>
					<tr>
						<td>Nama Bank</td>
						<td>: $ddetailpembelian[bank_rekening]</td>
					</tr>
					<tr>
						<td>No. Rekening</td>
						<td>: $ddetailpembelian[no_rekening]</td>
					</tr>
					<tr>
						<td colspan='2'>Pembayaran dari</td>
					</tr>
					<tr>
						<td>Nama Bank</td>
						<td>: $ddetailpembelian[transfer_bank]</td>
					</tr>
					<tr>
						<td>No. Transaksi</td>
						<td>: $ddetailpembelian[transfer_no]</td>
					</tr>
				</table>";
	$footer	.=" Terima kasih atas kepercayaan Anda.<br />
				elitezclothing.com<br />
				<br />
				---<br />
				elitezclothing.com<br />
			   Main Office: Jl. buah batu no. 238 Bandung Jawa Barat.<br />
			   Email: admin@elitezclothing.com<br />
			   ---";
	$footadmin.="Silahkan 
	<a href='http://www.elitezclothing.com/admin/index.php?page=datatransaksi&act=edit&id_pembelian=$id_pembelian'>Klik Disini</a> Untuk melakukan konfirmasi ";
	$dari   = "From: admin@elitezclothing.com \r\n";
	$dari  .= "Reply-To: admin@elitezclothing.com \r\n";
	$dari  .= "Content-type: text/html \r\n";
	mail($kepada,$judul,$ke.$pesan.$footer,$dari);
	emailkeadmin($judul,$keadmin.$pesan.$footadmin,$dari);
}

function emailbayarpaypal($id_pembelian,$payer,$recipe){
	$qdetailpembelian = mysql_query("SELECT * FROM pembelian a, member b
							WHERE a.id_member=b.id_member
							AND a.id_pembelian = '$id_pembelian'
							GROUP BY a.id_pembelian");
	$ddetailpembelian = mysql_fetch_array($qdetailpembelian);
	
	$kepada = "$ddetailpembelian[email_member]";
	$judul  = "[ elitezclothing.com ] Pembayaran Pesanan Paypal";
			
	$ke	    = "Kepada Yth. Sdr/i. $ddetailpembelian[kirim_nama],<br />
				<br />
				Terimakasih Telah Melakukan Pembayaran.<br /> 
				Pesanan Anda Sudah Tidak Dapat Dibatalkan.
				<br />
				Berikut adalah data pembayaran paypal anda:<br />";
	$keadmin = "Dear Admin,<br />
				<br />
				Telah diterima pembayaran menggunakan PayPal dari $ddetailpembelian[email_member].<br /> 
				Berikut adalah  detailnya:<br />";
	$pesan	="<table>
					<tr>
						<td>Id Pembelian</td>
						<td>: <strong>$id_pembelian<strong></td>
					</tr>
					<tr>
						<td>Nama</td>
						<td>: $ddetailpembelian[kirim_nama]</td>
					</tr>
					<tr>
						<td>Email</td>
						<td>: $ddetailpembelian[email_member]</td>
					</tr>
					<tr>
						<td>Tanggal Pembayaran</td>
						<td>: ".tgl_indo($ddetailpembelian['tgl_bayar'])." </td>
					</tr>
					<tr>
						<td>Besar Pembayaran</td>
						<td>: $ddetailpembelian[totalbayar] </td>
					</tr>
					<tr>
						<td>Account Penjual</td>
						<td>: $recipe</td>
					</tr>
					<tr>
						<td>Account Pembayar</td>
						<td>: $pbayar</td>
					</tr>
					<tr>
						<td>No. Transaksi</td>
						<td>: $ddetailpembelian[transfer_no]</td>
					</tr>
				</table>";
	$footer	 ="Terima kasih atas kepercayaan Anda.<br />
				elitezclothing.com<br />
				<br />
				---<br />
				elitezclothing.com<br />
			   Main Office: Jl. buah batu no. 238 Bandung Jawa Barat.<br />
			   Email: admin@elitezclothing.com<br />
			   ---";
	$dari   = "From: admin@elitezclothing.com \r\n";
	$dari  .= "Reply-To: admin@elitezclothing.com \r\n";
	$dari  .= "Content-type: text/html \r\n";
	mail($kepada,$judul,$ke.$pesan.$footer,$dari);
	emailkeadmin($judul,$keadmin.$pesan,$dari);
}


function emailregister($email,$nama,$alamat,$id_kota,$telp,$kodepos,$code){
	$querykota = mysql_query("SELECT *
							  FROM provinsi as a, kota as b
							  WHERE a.id_provinsi = b.id_provinsi
							  AND b.id_kota = '$id_kota'");
	$datakota = mysql_fetch_array($querykota);
	$kepada = "$email";
	$judul  = "[ elitezclothing.com ] Registrasi dan Aktivasi";
	$ke	  	= "Kepada Yth. Sdr/i. $nama,<br />
			   <br />
			   Terima kasih atas kepercayaan Anda menjadi anggota elitezclothing.com 
			   (<a href='http://www.elitezclothing.com'>http://www.elitezclothing.com</a>).<br />
			   <br />
			   Berikut adalah data diri Anda <br />
			   <br />
			   --------------------------------------------------------------<br />";
	$keadmin = "Dear Admin<br />
			   <br />
			   Telah ada 1 member baru yang mendaftar di elitezclothing.com.<br />
			   <br />
			   Berikut adalah data diri member  :<br />
			   <br />
			   --------------------------------------------------------------<br />";
	$pesan	="	   <table>
					<tr>
						<td>Nama Lengkap</td>
						<td>: $nama</td>
					<tr>
					<tr>
						<td>Alamat</td>
						<td>: $alamat</td>
					<tr>
					<tr>
						<td>Telepon</td>
						<td>: $telp</td>
					<tr>
					<tr>
						<td>Email</td>
						<td>: $email</td>
					<tr>
					<tr>
						<td>Kota</td>
						<td>: $datakota[nama_kota]</td>
					<tr>
					<tr>
						<td>Provinsi</td>
						<td>: $datakota[nama_provinsi]</td>
					<tr>
					<tr>
						<td>Kodepos</td>
						<td>: $kodepos</td>
					<tr>
			   </table>
			   --------------------------------------------------------------<br /><br />";
			   
	$footer	=" Untuk Melanjutkan Aktivitas Belanja Anda, Silahkan Verifikasi Account Anda<br /><br />
			   Silahkan Klik Link Dibawah Ini Untuk Melakukan Verifikasi Account<br />
			   <a href='http://elitezclothing.com/index.php?page=home&code=$code'>http://elitezclothing.com/index.php?page=home&code=$code</a><br />
			   Jika tidak berjalan dengan baik, Silahkan Copy link diatas ke Url Anda.
			   <br />
			   <br />
			   Terima kasih atas kepercayaan Anda.<br /><br />
			   ---<br />
			   elitezclothing.com<br />
			   Main Office: Jl. buah batu no. 238 Bandung Jawa Barat.<br />
			   Email: admin@elitezclothing.com<br />
			   ---";
	$dari   = "From: admin@elitezclothing.com \r\n";
	$dari  .= "Reply-To: admin@elitezclothing.com \r\n";
	$dari  .= "Content-type: text/html \r\n";
	mail($kepada,$judul,$ke.$pesan.$footer,$dari);
	emailkeadmin($judul,$keadmin.$pesan,$dari);
}

function emaillupapassword($email,$verifikasi){
	$qemail = mysql_query("SELECT * FROM member WHERE email_member = '$email'");
	$demail = mysql_fetch_array($qemail);
	$kepada = "$email";
	$judul  = "[ elitezclothing.com ] Verifikasi Permintaan Password Baru";
	$pesan  = "Kepada Yth. Sdr/i. $demail[nama_member],<br />
			   <br />
			   Untuk Melakukan Perubahan Password Account Anda, Silahkan Klik Link Dibawah Ini<br />
			   <a href='http://elitezclothing.com/index.php?page=lupapassword&code=$verifikasi'>http://elitezclothing.com/index.php?page=lupapassword&code=$verifikasi</a><br />
			   Jika tidak berjalan dengan baik, Silahkan Copy link diatas ke Url Anda.
			   <br />
			   <br />
			   Terima kasih atas kepercayaan Anda.<br /><br />
			   ---<br />
			   elitezclothing.com<br />
			   Main Office: Jl. buah batu no. 238 Bandung Jawa Barat.<br />
			   Email: admin@elitezclothing.com<br />
			   ---";
	$dari   = "From: admin@elitezclothing.com \r\n";
	$dari  .= "Reply-To: admin@elitezclothing.com \r\n";
	$dari  .= "Content-type: text/html \r\n";
	mail($kepada,$judul,$pesan,$dari);	
}

function emailprodukditerima($id_pembelian){
	$querycustomer = mysql_query("SELECT * FROM pembelian a, member b 
								 WHERE a.id_member=b.id_member
								 AND a.id_pembelian = '$id_pembelian'");
	$datacustomer = mysql_fetch_array($querycustomer);
	$kepada = "$datacustomer[email_member]";
	$judul  = "[ elitezclothing.com ] Konfirmasi Pesanan Telah Sampai";
	$pesan = "Terima Kasih Telah Memesan Di elitezclothing.com<br />
			  Status Pesanan Anda Sesuai dengan Jasa Pengiriman Kami Telah Sampai Kepada Anda.
					 <br />
					 <br />
					 Berikut adalah rincian pesanan anda :
					 <br />
					 <table>
					 	<tr>
							<td>Id Pembelian</td>
							<td>: $id_pembelian</td>
						</tr>
					 	<tr>
							<td>Nama </td>
							<td>: $datacustomer[kirim_nama]</td>
						</tr>
					 	<tr>
							<td>Alamat </td>
							<td>: $datacustomer[kirim_alamat]</td>
						</tr>
					 	<tr>
							<td>Kodepos </td>
							<td>: $datacustomer[kirim_kdpos]</td>
						</tr>
					 	<tr>
							<td>No. Telp </td>
							<td>: $datacustomer[kirim_telp]</td>
						</tr>
					 </table>
					 <table border=0 cellspacing=0 cellpadding=3 style='border:1px #666 solid'>
						<tr>
						  <th style='background-color:#999'>No.</th>
						  <th style='background-color:#999'>Nama produk</th>
						  <th style='background-color:#999'>Warna</th>
						  <th style='background-color:#999'>Ukuran</th>
						  <th style='background-color:#999'>Harga</th>
						  <th style='background-color:#999'>Berat</th>
						  <th style='background-color:#999'>Stok</th>
						  <th style='background-color:#999'>Jumlah</th>
						</tr>";
	
	$qcart = mysql_query("SELECT * FROM pembelian as a, detail_pembelian as b, detailproduk as c, produk as d, warna as e, ukuran as f
						  WHERE a.id_pembelian = b.idpembelian
						  AND b.id_detailproduk = c.id_detailproduk
						  AND c.id_produk = d.id_produk
						  AND c.id_warna = e.id_warna
						  AND c.id_ukuran = f.id_ukuran
						  AND a.id_pembelian = '$id_pembelian'");
	$no = 0;
	$total = 0;
	$ongkos = 0;
	$qb = 0;
	while($dcart = mysql_fetch_array($qcart)){
	$ongkos = $dcart['kirim_ongkos'];
	$qb = $qb + ($dcart['qty']*$dcart['berat']);
	$total = $total + ($dcart['hargabeli']*$dcart['qty']);
	$no++;
	$pesan .="  <tr>
					  <td>$no.</td>
					  <td>$dcart[nama_produk]</td>
					  <td>$dcart[nama_warna]</td>
					  <td>$dcart[nama_ukuran]</td>
					  <td align='right'>Rp".number_format($dcart['hargabeli'],"2",",",".")."</td>
					  <td>$dcart[berat]</td>
					  <td>$dcart[qty]</td>
					  <td align='right'>Rp".number_format(($dcart['hargabeli']*$dcart['qty']),"2",",",".")."</td>
			    </tr>";
	}
	$total = $total + ($ongkos*(int)ceil($qb));
	
	$pesan .="<tr style='background-color:#ccc'>
				  <td colspan='7'>Ongkos Kirim (".(int)ceil($qb)." x Rp ".number_format($ongkos,"2",",",".").")</td>
				  <td align='right'>Rp".number_format(($ongkos*(int)ceil($qb)),"2",",",".")."</td>
			</tr>
			<tr>
				  <td colspan='7'>Total</td>
				  <td align='right' style='color:#F00'>Rp".number_format($total,"2",",",".")."</td>
			</tr>
			</table>
			<br /><br />
			Jika Ada produk didalam Pesanan Anda yang Tidak Sesuai dengan yang Anda Inginkan<br />
			Silahkan Melakukan Pengembalian produk Kepada Kami,<br />
			Terima kasih atas kepercayaan Anda.<br />
			elitezclothing.com<br />
			<br />
			---<br />
			elitezclothing.com<br />
			   Main Office: Jl. buah batu no. 238 Bandung Jawa Barat.<br />
			   Email: admin@elitezclothing.com<br />
			   ---";
	$dari   = "From: admin@elitezclothing.com \r\n";
	$dari  .= "Reply-To: admin@elitezclothing.com \r\n";
	$dari  .= "Content-type: text/html \r\n";
	mail($kepada,$judul,$pesan,$dari);
}

function email_hubungi($email,$nama,$isi){
	$kepada = "$email";
	$judul  = "[ elitezclothing.com ] Balasan";
	$pesan  = "Kepada Yth. Sdr/i. $nama,<br />
			   <br />
			   Terimakasih atas kritik dan sarannya kepada kami, kami akan berusaha untuk selalu meningkatkan mutu pelayanan kami.<br>
			   berikut adalah jawaban kami atas Pertanyaan sodara..<br>
			   $isi
			   <br />
			   <br />
			   Terima kasih atas kepercayaan Anda.<br /><br />
			   ---<br />
			   elitezclothing.com<br />
			   Main Office: Jl. buah batu no. 238 Bandung Jawa Barat.<br />
			   Email: admin@elitezclothing.com<br />
			   ---";
	$pesanadmin ="Dear Admin <br />
				Telah diterima kritik dan saran dari $email sebagai berikut :<br />
				$isi
				<br />
				";
	$dari   = "From: admin@elitezclothing.com \r\n";
	$dari  .= "Reply-To: admin@elitezclothing.com \r\n";
	$dari  .= "Content-type: text/html \r\n";
	mail($kepada,$judul,$pesan,$dari);
	emailkeadmin($judul,$pesanadmin,$dari);
}

function emaillupasadmin($email,$verifikasi){
	$qemail = mysql_query("SELECT * FROM admin WHERE email_admin = '$email'");
	$demail = mysql_fetch_array($qemail);
	$kepada = "$email";
	$judul  = "[ elitezclothing.com ] Verifikasi Permintaan Password Admin Baru";
	$pesan  = "Kepada Yth. Sdr/i. $demail[nama_admin],<br />
			   <br />
			   Untuk Melakukan Perubahan Password Account Anda, Silahkan Klik Link Dibawah Ini<br />
			   <a href='http://elitezclothing.com/admin/login.php?code=$verifikasi'>http://elitezclothing.com/admin/login.php?code=$verifikasi</a><br />
			   Jika tidak berjalan dengan baik, Silahkan Copy link diatas ke Url Anda.
			   <br />
			   <br />
			   Terima kasih atas kepercayaan Anda.<br /><br />
			   ---<br />
			   elitezclothing.com<br />
			   Main Office: Jl. buah batu no. 238 Bandung Jawa Barat.<br />
			   Email: admin@elitezclothing.com<br />
			   ---";
	$dari   = "From: admin@elitezclothing.com \r\n";
	$dari  .= "Reply-To: admin@elitezclothing.com \r\n";
	$dari  .= "Content-type: text/html \r\n";
	mail($kepada,$judul,$pesan,$dari);	
}

?>