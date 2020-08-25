<?php

require_once('library/Database.class.php');
require_once('library/User.class.php');

session_start();
header('Location: gallery.php?page=' . $_GET['page']);


include('library/fetch_db_obj.php');

if (isset($_POST['comment']) && $_POST['comment'] != NULL)
	$db->add_comment($_GET['photo'], $_POST['comment']);
?>