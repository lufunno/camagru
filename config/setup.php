<?php

require('../library/Database.class.php');
// header('Location: ../index.php');

function setup_db() {

$db = new Database();
$db->build_table();
return TRUE;
// $db->add_admin_account();
}

if (setup_db() == TRUE)
	echo "build was successful";

?>