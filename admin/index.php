<?php
session_start();
include "../fungsi/koneksi.php";
include "../fungsi/function.php";

$time = time();
$time_check = $time - 600;

if(isset($_SESSION['id_admin'])){
mysql_query("UPDATE admin
			 SET waktu_login = $time
			 WHERE id_admin = '$_SESSION[id_admin]'");
}

mysql_query("UPDATE admin SET status_login = '0' WHERE waktu_login < $time_check");

if(!isset($_SESSION['id_admin']))
	echo "<script>window.location = 'login.php';</script>";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Administrator Elitez Clothing</title>

<link href='../images/logo.png' rel='SHORTCUT ICON'/>
<link rel="stylesheet" type="text/css" href="style.css" />
<link rel="stylesheet" type="text/css" href="stylehorizontal.css" />
<script type="text/javascript" src="jquery.min.js"></script>
<script language="javascript" type="text/javascript" src="niceform/niceforms.js"></script>
<link rel="stylesheet" type="text/css" media="all" href="niceform/niceforms-default.css" />
<script language="javascript" type="text/javascript" src="../js/jalert/jquery.alerts.js"></script>
<link rel="stylesheet" type="text/css" media="all" href="../js/jalert/jquery.alerts.css" />
<link type="text/css" href="../js/datepicker/ui.all.css" rel="stylesheet" />   
<script type="text/javascript" src="../js/datepicker/ui.core.js"></script>
<script type="text/javascript" src="../js/datepicker/ui.datepicker.js"></script>   
<script type="text/javascript" src="../js/datepicker/ui.datepicker-id.js"></script>
<script type="text/javascript" src="../js/datepicker/effects.core.js"></script>
<script type="text/javascript" src="../js/datepicker/effects.drop.js"></script>
<script type="text/javascript"> 
   $(document).ready(function(){
      $("#tanggal1").datepicker({
        showAnim    : "drop",
        showOptions : { direction: "up" }
      });
      $("#tanggal2").datepicker({
        showAnim    : "drop",
        showOptions : { direction: "up" }
      });
      $("#tanggal3").datepicker({
        showAnim    : "drop",
        showOptions : { direction: "up" }
      });
      $("#tanggal4").datepicker({
        showAnim    : "drop",
        showOptions : { direction: "up" }
      });
    });
</script>
<script type="text/javascript" src="jconfirmaction.jquery.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$('.ask').jConfirmAction();
	});
</script>
<link rel="stylesheet" href="../js/validate/valadmin.css" type="text/css" />
<script type="text/javascript" src="../js/validate/jquery.validate.js"></script>
</head>

<body>
      <div id="menu_tab">
      	 <div id="menunav">
 		        <a href="?page=logout">Logout</a>
         </div>
            </div><!-- end of menu tab -->
    <div id="header">
     </div>
<div id="main_container">
   <div id="main_content"> 
   
          <div class="left_content">
		   <?php
		  include "menu.php";
		   ?>
		  </div>
          <div class="center_content">
		   <?php
		  include "halaman.php";
		   ?>
		  </div>
      </div>
	   <div class="footer">
        </div>    
   
   </div> <!-- end footer -->   
</div>
<!-- end of main_container -->
</body>
</html>
