<?php
// AWAL: Ambil data yang dikirim dari paypal, kemudian buat request untuk validasi pembayaran
$req = 'cmd=_notify-validate';

foreach ($_POST as $key => $value) {
$value = urlencode(stripslashes($value));
$req .= "&$key=$value";
}

// AKHIR: Ambil data yang dikirim dari paypal, kemudian buat request untuk validasi pembayaran

// AWAL: Kirimkan request kembali ke Paypal
$header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
$fp = fsockopen ('ssl://www.sandbox.paypal.com', 443, $errno, $errstr, 30);
// AKHIR: Kirimkan request kembali ke Paypal

// AWAL: Baca semua data dari paypal, simpan ke variable local.
// Nama-nama variable, baca di dokumentasi IPN Guide
$item_name 		= $_POST['item_name'];
$item_number 		= $_POST['item_number'];
$payment_status 	= $_POST['payment_status'];
$payment_amount 	= $_POST['mc_gross'];
$payment_currency 	= $_POST['mc_currency'];
$txn_id 		= $_POST['txn_id'];
$receiver_email 	= $_POST['receiver_email'];
$payer_email 		= $_POST['payer_email'];
$id_pembelian 		= $_POST['option_selection1_2'];
$size               = $_POST['option_selection2_1'];
$waktu			= $_POST['payment_date'];
$qty			= $_POST['quantity'];
// AKHIR: Baca semua data dari paypal, simpan ke variable local.

if (!$fp) {
// Jika pengiriman validasi gagal, lakukan sesuatu di bawah ini
} else {
	fputs ($fp, $header . $req); // Kirimkan Request
	while (!feof($fp)) {
		$res = fgets ($fp, 1024); // Baca Response
		if (strcmp ($res, "VERIFIED") == 0) { // Jika pembayaran telah terverifikasi
                    include("fungsi/koneksi.php");
// AWAL: update data pembayaran
   			$sql2="update pembelian  SET tgl_bayar=str_to_date('$waktu', '%H:%i:%s %b %d, %Y' ),
									 pembayaran='paypal',
									 transfer_jumlah='$payment_amount',
									 status='konfirmasi',
									 transfer_no='$txn_id'
				  where id_pembelian='$id_pembelian'";
   			$res2=mysql_query($sql2);
			emailbayarpaypal($id_pembelian,$payer_email,$receiver_email)
			// AKHIR: update data pembayaran
			// AWAL: Mengirim email yang berisi data IPN (tidak wajib dibuat)
   			$post_ipn="";
   			foreach ($_POST as $key => $value) {
      			$post_ipn .= "$key = $value\n";
   			}
   			mail("elitez.cloth@gmail.com","IPN :  $id_pembelian",$post_ipn."\n\nRES: \n".$res);
			// AKHIR: Mengirim email yang berisi data IPN (tidak wajib dibuat)
   			}
		
		else if (strcmp ($res, "INVALID") == 0) {
			// log for manual investigation
		}
	}
	fclose ($fp);// Tutup Request/Respose
}
?>
