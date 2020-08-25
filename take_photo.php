<?php
session_start();

header('Content-Type: image/png');

function merge_images($image, $prop, $width, $height)
{
	// prop size
	$prop_width = imagesx($prop);
	$prop_height = imagesy($prop);

	// prop position
	$prop_x = $width / 2 - $prop_width / 2;
	$prop_y = $height / 2 - $prop_height / 2;
	// $prop_y = rand() % $height; // depends on prop

	$merged_img = imagecreatetruecolor($width, $height);

	// alpha canal config for prop
	imagealphablending($prop, false);
	imagesavealpha($prop, true);

	imagecopyresampled($merged_img, $image, 0, 0, 0, 0, $width, $height, $width, $height);
	imagecopyresampled($merged_img, $prop, $prop_x, $prop_y, 0, 0, $prop_width, $prop_height, $prop_width, $prop_height);

	imagedestroy($prop);
	imagedestroy($image);

	return ($merged_img);
}

if (isset($_POST['image'])) {

	// necessary if data is from javascript canvas.toDataURL
	$string = str_replace(' ','+',$_POST['image']);

	$string = base64_decode($string);
	$pre_snapshot = imagecreatefromstring($string);
	$width = imagesx($pre_snapshot);
	$height = imagesy($pre_snapshot);
	// print_r(array('width' => $width, 'height' => $height));

	$snapshot = imagecreatetruecolor($width, $height);
	imagecopyresized($snapshot, $pre_snapshot, 0, 0, 0, 0, $width, $height, $width, $height);
	imagedestroy($pre_snapshot);
}

if ($snapshot){

	// size of final image
	$width = imagesx($snapshot);
	$height = imagesy($snapshot);


	if ($_POST['props'] != 'undefined')
	{
		$props = explode(';', $_POST['props']);
		foreach ($props as $value) {
			$value = explode(':', $value)[0];
			$prop = imagecreatefrompng($value);
			$snapshot = merge_images($snapshot, $prop, $width, $height);
		}
	}

	unlink("img/tmp_snapshot.png");

	$snap_name = "img/tmp_snapshot.png";

	imagepng($snapshot, $snap_name);

	$snap_name2 = "img/tmp_snapshot.png?" . filectime("img/tmp_snapshot.png");

	echo ($snap_name2);// . "?" . filemtime($snap_name));
}

else
	echo "error";
?>
