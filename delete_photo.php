<?php

require_once("library/User.class.php");
require_once('library/Database.class.php');
session_start();

include('library/fetch_db_obj.php');

$db->delete_photo($_POST['photo']);
$path = 'img/user/' . $_SESSION[ 'logged_on_user']->id . '/' . $_POST[ 'photo'] . '.png';
unlink($path);
$_SESSION['logged_on_user']->delete_photo();

?>