<?php
header("Content-type: image/png");

$img = imagecreatefromstring("");
$wm = imagecreatefrompng("img/moustache.png");

//Position du WM sur l'image finale
$positionx = 0;
$positiony = 0;

//Calcul de la taille de l'image sur laquelle, on va placer le watermark (ou autre image)
$largeurSrc = imagesx($img);
$hauteurSrc = imagesy($img);

//Calcul de l'image du watermark
$largeurW = imagesx($wm);
$hauteurW = imagesy($wm);

//Si l'image source est plus large que celle du WM, on effectue la mise en place du WM
if($largeurSrc > $largeurW)
{
  # Création de l'image finale aux dimensions de l'image qui va accueillir le WM
  $final     = imagecreatetruecolor($largeurSrc,$hauteurSrc);

  # configuration du canal alpha pour le WM
  imagealphablending($wm,false);
  imagesavealpha($wm,true);

  # Création de l'image finale
  imagecopyresampled($final,$img,0,0,0,0,$largeurSrc,$hauteurSrc,$largeurSrc,$hauteurSrc);
  # Ajout du watermark
  imagecopyresampled($final,$wm,$positionx,$positiony,0,0,$largeurW,$hauteurW,$largeurW,$hauteurW);
  # Pour ajouter d'autre photo par dessus, procéder de la meme façon

  # affichage de l'image finale
  imagepng($final);
}
?>


