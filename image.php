<?php
session_start();
function strrand($length)
{
	$str = "";
	
	while(strlen($str)<$length){
	$random=rand(48,122);
	if( ($random>65 && $random<90)  ){ //47->58: number; 65->90 : A-Z; 97->121: a-z
	$str.=chr($random);
	} 
	
	}
		
	return $str;
}

$text = $_SESSION['string']=strrand(5);
header("Content-type: image/png");

$im = imagecreatefrompng("phuong.png");

$white = imagecolorallocate($im, 66, 66, 66);

$font = 'PAPYRUS.TTF';
$fontsize=19;

imagettftext($im,  $fontsize, 0, 5, 35, $white, $font, $text );

imagepng($im);
imagedestroy($im);

?> 