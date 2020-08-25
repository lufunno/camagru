<?php

require_once('library/Database.class.php');
require_once('library/User.class.php');

session_start();

header("Location: index.php?help=pw&status=0"); // change status if user not found or pw reinitialized

include('library/fetch_db_obj.php');


$db->reinit_password($_POST['email']);

?>