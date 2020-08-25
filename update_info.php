<?php

require_once('library/Database.class.php');
require_once('library/User.class.php');

session_start();

include('library/fetch_db_obj.php');

if (isset($_POST['submit']) && $_POST['submit'] === 'Change password')
{
	if (hash('whirlpool', $_POST['old_password']) == $_SESSION['logged_on_user']->password)
	{
		$db->set_password($_SESSION['logged_on_user']->username, $_POST['new_password']);
		header('Location: account.php?act=mng&ch=1');
	}
	else
		header('Location: account.php?act=mng&error=7');
}
if ($_POST['submit'] === "Save changes")
{
	if ($db->update_info($_SESSION['logged_on_user']->id, $_POST))
		header('Location: account.php?act=mng&ch=2');
}
?>