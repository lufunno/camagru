<?php

header('Content-Type: image/png');

$src = "img/props/" . $_GET['prop'] . ".png";
$big_prop = imagecreatefrompng($src);
$width = 110;
$height = 80;
if ($_GET['type'] == 'small')
	$args = array('x' => 255, 'y' => 210, 'width' => $width, 'height' => $height); 
else if ($_GET['type'] == 'frame')
	$args = array('x' => 20, 'y' => 10, 'width' => 600, 'height' => 470);
else if ($_GET['type'] == 'mask')
	$args = array('x' => 200, 'y' => 30, 'width' => 200, 'height' => 250);
else if($_GET['type'] == 'beard')
	$args = array('x' => 200, 'y' => 150, 'width' => 200, 'height' => 250);
else
	$args = array('x' => 0, 'y' => 110, 'width' => 200, 'height' => 300);
$thumb = imagecrop($big_prop, $args);


imagedestroy($big_prop);

imagepng($thumb);

echo $thumb;

?>