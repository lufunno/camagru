<?PHP

require_once('library/Database.class.php');
require_once('library/User.class.php');


function password_strength($password) {
	if (strlen($password) >= 4 && !ctype_alpha($password)) // no special chars ':'
		return TRUE;
	return FALSE;
}

// errno global would be better for errors

function create_account() {
	$db = new Database();
	if (password_strength($_POST["password"]) == TRUE)
	{
		if ($_POST['password'] === $_POST['conf_password'])   // do this in ajax instead
		{
			$new_user = new User($_POST);
			if (($ret = $db->add_user($new_user)) < 0)
			{
				if ($ret == -1)
					header("Location: index.php?error=2");
				else
					header("Location: index.php?error=6");
			}
			else
				header("Location: index.php?activate=0");
		}
		else
			header("Location: index.php?error=5");
	}
	else
		header("Location: index.php?error=1");
}

if ($_POST["submit"] === "Sign up" && $_POST["password"] && ($_POST["email"] && $_POST['username']))
	create_account();
else
	header("Location: index.php?error="); // missing info error / ajaxify

?>
