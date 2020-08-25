<?PHP

session_start();


require_once("library/Database.class.php");
$db = new Database();

if ($_POST['submit'] == 'Sign in' && ($user = $db->auth_user(($login = $_POST["username"]), $_POST["password"])) != NULL)
{
	if ($user->activate == 1)
	{
		header("Location: index.php");
		$_SESSION["logged_on_user"] = $user;
	}
	else
		header("Location: index.php?error=3&username=" . $user->username);
}
else
{
	header("Location: index.php?error=4");
	$_SESSION["logged_on_user"] = "";
}

?>
