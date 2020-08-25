<?php
require_once("library/User.class.php");
require_once('library/Database.class.php');
session_start();

if (isset($_SESSION['db']))
	$db = $_SESSION['db'];
else
	$db = new Database();

$time = filectime("img/tmp_snapshot.png");


$db->add_photo($time, $_SESSION['logged_on_user']->username);
// $_SESSION['logged_on_user']->add_photo();
$name = 'img/user/' . $_SESSION['logged_on_user']->id . '/' . $time . ".png";


if (!file_exists('img/user/' . $_SESSION['logged_on_user']->id))
{
	mkdir('img/user/' . $_SESSION['logged_on_user']->id);
}


rename("img/tmp_snapshot.png", $name); // use users nb_photos ?
touch("img/tmp_snapshot.png");

echo $name;

?>