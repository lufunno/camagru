<?php

require_once('library/Database.class.php');
require_once('library/User.class.php');

session_start();

include('library/fetch_db_obj.php');

if ($db->auth_user($_GET['user'], $_POST['old_password']))
{
	header('Location: index.php?help=pw&status=1')
	$db->set_password($_GET['user'], $_POST['new_password']);
}
else
	header('Location: index.php?error=7');
?>