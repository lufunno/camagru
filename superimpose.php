<?php

header ("Content-type: image/png");

$doc = new DomDocument;

$doc->validateOnParse = true;
$doc->Load('index.php');

$img = $doc->getElementById('saved_photo')->src;

// source img
$src = imagecreatefrompng("img/moustache.png");
$src_width = imagesx($src);
$src_height = imagesy($src);
imagealphablending($src, true);
imagesavealpha($src, true);

// dest img
$dest = imagecreatefromstring($img);
$dest_width = imagesx($dest);
$dest_height = imagesy($dest);

// coordonnees

$dest_x = ($dest_width - $src_width)/2;
$dest_y =  ($dest_height - $src_height)/2;

// place src in dest
imagecopy($dest, $src, $dest_x, $dest_y, 0, 0, $src_width, $src_height);

//display
imagepng($dest);

imagedestroy($src);
imagedestroy($dest);


?>