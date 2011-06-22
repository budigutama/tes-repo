<?php
   if(isset($_GET['page'])){
	   	switch($_GET['page']){
			case "home"			:include "inc/home.php";break;
			case "index"			:include "inc/home.php";break;
			case "register"		:include "inc/register.php";break;
			case "detail"		:include "inc/detail.php";break;
			case "carapembayaran"	:include "inc/carapembayaran.php";break;
			case "kontak"	:include "inc/kontak.php";break;
			case "carapembelian"	:include "inc/carapembelian.php";break;
			case "cararetur"	:include "inc/cararetur.php";break;
			case "cart"			:include "inc/cart.php";break;
			case "katalog_produk"	:include "inc/home.php";break;
			case "barang_terbaru"	:include "inc/home.php";break;
			case "barang_laku"	:include "inc/home.php";break;
			case "barang_liat"	:include "inc/home.php";break;
			case "logout"		:include "inc/logout.php";break;
			case "editakun"		:include "inc/edit.account.php";break;
			case "history"		:include "inc/history.php";break;
			case "view"			:include "inc/view.php";break;
			case "checkout"		:include "inc/checkout.php";break;
			case "confirm"		:include "inc/confirm.php";break;
			case "kirim"		:include "inc/kirim.php";break;
			case "confirmpay"	:include "inc/confirmpay.php";break;
			case "lupapassword"	:include "inc/lupapassword.php";break;
			case "retur"		:include "inc/retur.php";break;
			case "dretur"		:include "inc/dretur.php";break;
			case "returcart"	:include "inc/returcart.php";break;
			case "returkirim"	:include "inc/returkirim.php";break;
			case "viewretur"	:include "inc/viewretur.php";break;
			case "success"	:include "inc/success.php";break;
			case "failed"	:include "inc/failed.php";break;
			default 	:include "inc/defaultpage.php";
		}
   }
   else{
		include "inc/home.php";   
   }
	?>    		