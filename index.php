<?php


include_once ("config/database.php");
// require_once('library/Database.class.php');
require_once('library/User.class.php');

session_start();

if (isset($_GET['activate']) && $_GET['activate'] != 1 && $_GET['activate'] != 0)
	{
		$dbh = new Database();
		$dbh->activate_account();
		// check if activation == SUCCESS
		// header("Location: index.php?activate=1"); // for now in activate_account
	}
?>

<!doctype html>
<html>
<head>
	<title>Camagru</title>
	<meta charset="utf-8">
	<meta name="viewport" contents="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="stylesheet" type="text/css" href="css/form.css">
</head>
<body>
	<div class="wrapper">
		<header>
		<?php include("layout/header.html"); ?>
		</header>

		<section class="mid">
			<?php

			if (isset($_GET['activate']))
			{
				if ($_GET['activate'] == 0)
					echo "
						<span class='top_message col-lg-12 col-md-12 col-sm-12'>
							An activation email has been sent, check your inbox and follow the link to get started!
						</span>
				";
				else if ($_GET['activate'] == 1)
				{
					echo "
						<span class='top_message ch_success col-lg-12 col-md-12 col-sm-12'>
							Your account was successfully activated
						</span>
					";
				}
			}
			else if (isset($_GET['error']))
				include("partials/errors.php");
			if (isset($_SESSION["logged_on_user"]) && $_SESSION['logged_on_user'] != NULL)
			{
				$dir_name = "img/user/" . $_SESSION['logged_on_user']->id;
				if (!is_dir($dir_name))
					$_SESSION['logged_on_user']->create_user_folder();
				include("pages/main.html");
			}
			else
			{
				if (isset($_GET['help']))
				 {
				 	if ($_GET['help'] === 'pw')
						include("pages/reinit_password.html");
					else if ($_GET['help'] === 'ch_pw')
						include("reinit_pw.html");
				}
				else
					include("pages/welcome.html");
			}
			?>
		</section>
	</div>

	<footer>
		<?php include("layout/footer.html"); ?>
	</footer>

</body>
</html>