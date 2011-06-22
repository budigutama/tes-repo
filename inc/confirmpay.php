<div class="center_title_bar">Konfirmasi Pembayaran</div>
<div class="prod_box_big">
	<div class="center_prod_box_big">
<?php
if(isset($_POST['confirmpayment'])){
	$tanggal=$_POST['tgl']." ".date( 'H:i:s');
	if(!empty($_POST['namabank']) && !empty($_POST['notransaksi']) && !empty($_POST['jumlahbayar'])){
		if (!empty($_POST['banklain']) && ($_POST['namabank']=="lain"))
		{ $bank=$_POST['banklain'];}
		else 
		{ $bank=$_POST['namabank']; }
		mysql_query("UPDATE pembelian SET 
		transfer_bank = '$bank', 
		transfer_no = '$_POST[notransaksi]',
		tgl_bayar= '$tanggal',
		status= 'bayar', 
		transfer_jumlah='$_POST[jumlahbayar]', 
		id_rekening = $_POST[id_rekening], 
		pembayaran = 'transfer' 
		WHERE id_pembelian = '$_POST[idn]' 
		AND id_member = '$_SESSION[id_member]'");
		emailbayar($_POST['idn']);
	}
	else{
		echo "<h3>Maaf, Data Tidak Boleh Ada yang Kosong.</h3>";
		$errormsg = 1;
	}
}
else{
	echo "<script>window.location = '?page=index';</script>";
}
?>
		<div class="content">
				Kepada Yth.
				<br />
				<br />
				Sdr/i &nbsp;&nbsp;<b><?php echo $_SESSION['nama_member']; ?></b>
				<br />
				<br />
				Terimakasih Telah Melakukan Pembayaran. Kami Akan Mengirimkan Konfirmasi Kepada Anda Paling Lambat 1 x 24 Jam Melalui Email.<br>
				Pesanan Anda Sudah Tidak Dapat Dibatalkan.
				<br>
				<br>
				Administrator elitez-distro.com
				<br><br>
                <input type="submit" onclick="window.location = '?page=history';" class="buton" value="Kembali">
		  </div>
    </div>
</div>