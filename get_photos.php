<?php

require_once('library/Database.class.php');
require_once('library/Photo.class.php');
require_once('library/User.class.php');

if (!isset($_SESSION['db']))
{
	$db = new Database;
	$_SESSION['db'] = $db;
}
else
	$db = $_SESSION['db'];
if (isset($_SESSION['logged_on_user']))
{
	print_r($_SESSION['logged_on_user']);
	$_SESSION['logged_on_user']->photos = $db->get_photos($_SESSION['logged_on_user']->id);
	print_r($_SESSION['logged_on_user']->photos);
}
// $_SESSION['logged_on_user']->photos ;

// $dir_name = "img/user/" . $_SESSION['logged_on_user']->id;
// 	if (!is_dir($dir_name))
// 		$_SESSION['logged_on_user']->create_user_folder();
// 	$user_photos = scandir($dir_name);
// 	// if ($user_photos)
// 	// {
// 		print_r ($user_photos);
// 	// }
?>