		     <?php
				if(isset($_GET['page'])){
					switch($_GET['page']){
						case "home"			: include "inc/home.php";break;
						case "admin"		: include "inc/admin.php";break;
						case "kategori"		: include "inc/kategori.php";break;
						case "produk" 		: include "inc/produk.php";break;
						case "detailproduk" : include "inc/detailproduk.php";break;
						case "kota" 		: include "inc/kota.php";break;
						case "provinsi" 	: include "inc/provinsi.php";break;
						case "ukuran" 		: include "inc/ukuran.php";break;
						case "warna" 		: include "inc/warna.php";break;
						case "member" 		: include "inc/member.php";break;
						case "rekening" 	: include "inc/rekening.php";break;
						case "laporan" 	: include "inc/laporan.php";break;
						case "lap_review" 	: include "inc/laporan_review.php";break;
						case "ongkir"	 	: include "inc/ongkir.php";break;
						case "retur"		 	: include "inc/retur.php";break;
						case "jasapengiriman"	 	: include "inc/jasapengiriman.php";break;
						case "jenispengiriman"	 	: include "inc/jenispengiriman.php";break;
						case "laporanproduk"	 	: include "inc/laporanproduk.php";break;
						case "datatransaksi": include "inc/datatransaksi.php";break;
						case "editprofile" 	: include "inc/editprofile.php";break;
						case "testiproduk" 	: include "inc/testiproduk.php";break;
						case "editpassword"	: include "inc/editpassword.php";break;
						case "kontak"	 	: include "inc/kontak.php";break;
						case "logout"		: mysql_query("UPDATE admin SET status_login='0' WHERE id_admin = '$_SESSION[id_admin]'");
											  session_destroy();
											  echo "<script>window.location = 'index.php';</script>";
											  break;
					}
				}
				else{
                	include "inc/home.php"; 
				}
            	?> 
