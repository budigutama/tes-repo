<?php
session_start();
/*function redirectToHTTPS()
{
  if($_SERVER['HTTPS']!="on")
  {
     $redirect= "https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
     header("Location:$redirect");
  }
}
redirectToHTTPS();*/
include "fungsi/function.php";
include "admin/inc/DetSize.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=windows-1252" />
<title>Elitez Clothing</title>
<?php
require_once('fungsi/koneksi.php');
?>
<link href='images/logo.png' rel='SHORTCUT ICON'/>
<link rel="stylesheet" type="text/css" href="style.css" />
<link rel="stylesheet" type="text/css" href="lib/jquery.hrzAccordion.defaults.css">
<link rel="stylesheet" href="lib/membertabs.css" type="text/css" media="screen">
<link rel="stylesheet" type="text/css" href="notif.css" />
<link rel="stylesheet" href="js/tabs/jquery.tabs.css" type="text/css" media="print, projection, screen">
<link href="js/jalert/jquery.alerts.css" rel="stylesheet" type="text/css" media="screen">
<link rel="stylesheet" href="js/validate/val.css" type="text/css" />
<link rel="stylesheet" type="text/css" href="js/star/css/crystal-stars.css"/>
<link rel="stylesheet" href="js/prety/css/prettyPhoto.css" type="text/css" media="screen" title="prettyPhoto main stylesheet" charset="utf-8" />

<script src="js/jquery.js" type="text/javascript"></script>
<script src="js/jalert/jquery.alerts.js" type="text/javascript"></script>
<script src="js/jquery.history_remote.pack.js" type="text/javascript"></script>
<script src="js/jquery.tabs.pack.js" type="text/javascript"></script>
<script type="text/javascript" src="js/validate/jquery.validate.js"></script>
<link rel="stylesheet" type="text/css" href="iecss.css" />
<script type="text/javascript" src="js/boxOver.js"></script>
</head>
<body>
<div id="main_container">

<div id="header"></div>
<!-- end of menu tab -->
<div id="main_content">
<div id="menu_tab">
<div style="float:left; margin-top:6px;margin-left:10px;margin-right:-400px;">
   <?php include "inc/notif.php"; ?>
</div>
<a href="?page=home" title="home"><div class="menu_tab_img">Home</div></a>   
<div id="cari">
<form method="post" action="index.php">
	 <input type="text" name="textcari" class="newsletter_input" value="<?php if(isset($_POST['textcari'])){ echo $_POST['textcari']; } else { echo "Keyword..."; }?>" onblur="if(this.value=='') this.value='Keyword...';" onfocus="if(this.value=='Keyword...') this.value='';" /><input type="image" src="images/search.png" name="search" style="margin-bottom:-4px;"/>
     </form>  </div>
</div>
<div class="left_content">  
   <?php include "inc/left.php"; ?>
</div><!-- end of left content --> 
<div class="center_content">
   <?php include "inc/center.php"; ?>
</div><!-- end of center content -->

<div class="right_content">
    <?php include "inc/right.php"; ?>  
</div><!-- end of right content -->   
   </div>
<!-- end of main content -->
   
   <div class="footer">
     <div class="footer-conten">
        <a href="http://www.facebook.com/elitez" title="Join to Our Pages on Facebook">
         <img src="images/fb2.png" alt="Join facebook" width="16" alt="facebook" height="16" border="0" title="" />elitez-distro   </a></div>
     <div class="footer-conten">
         <a href="http://www.twitter.com/elitez" title="Follow Our Twitter @elitez">
         <img src="images/twitter2.png" alt="follow Us" width="16" height="16" border="0" title="" />@elitezdistro</a>
        </div>
     <div class="footer-kanan">
        <a href="?page=carapembayaran">Cara Pembayaran</a></div>
     <div class="footer-kanan">
		<a href="?page=carapembelian">Cara Pembelian</a></div>
     <div class="footer-kanan">
		<a href="?page=cararetur">Cara Retur</a></div>
     <div class="footer-kanan">
     	<a href="?page=kontak">Kontak</a></div>
   </div> <!-- end footer -->                


</div>
<!-- end of main_container -->
</body>
</html>