<?php

require_once('library/User.class.php');
require_once('library/Database.class.php');

session_start();

if (!isset($_SESSION['db']))
{
	$db = new Database();
	$_SESSION['db'] = $db;
}
else
	$db = $_SESSION['db'];
$db->unlike_photo($_POST['name'], ':' . $_SESSION['logged_on_user']->username);
$nb_likes = $db->get_nb_likes($_POST['name']);
if ($nb_likes < 0)
	echo "0";
else
	echo $nb_likes;

?>