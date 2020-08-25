<?php

if (isset($_SESSION['db']))
	$db = $_SESSION['db'];
else
{
	$db = new Database();
	$_SESSION['db'] = $db;
}

?>