<?php
require_once('library/Database.class.php');
header('Location: index.php?activate=0');
$db = new Database();
$user = new User(array('username' => $_GET['username']));
$db->send_activation_email($user);
?>