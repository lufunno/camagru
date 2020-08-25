<?PHP

session_start();

function whoami(){
	foreach ($_SESSION as $key => $login)
	{
		if ($key === "logged_on_user" && $user != NULL)
			return $user['username'] . "\n";
	}
		return NULL;
}

whoami();

?>
