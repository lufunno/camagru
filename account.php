<?php


// include_once ("library/database.class.php");
require_once('library/User.class.php');

session_start();
	// header('Location: account.php?act=mng');


if (!isset($_SESSION['logged_on_user']) || $_SESSION[ 'logged_on_user'] == NULL)
		 header('Location: index.php');

?>
<!doctype html>
<html>
<head>
	<title>Camagru</title>
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="stylesheet" type="text/css" href="css/form.css">
	<link rel="stylesheet" type="text/css" href="css/account.css">
	<link rel="stylesheet" type="text/css" href="css/gallery.css">
</head>
<body>

	<div class="wrapper">
		<header>
			 <?php include("layout/header.html"); ?>
		</header>

		<div class="mid col-lg-12 col-md-12 col-sm-12">
			<ul class='col-lg-12 col-sm-12 col-md-12 account_navbar'>
				<li id='mng_el' class='col-lg-6 col-md-6 col-sm-6' >
					<a id='mng_button' href='account.php?act=mng' <?php if(!isset($_GET['act']) || $_GET['act'] === 'mng') echo "style='background: #43D1AF;'"?>>Manage account</a>
				</li>
				<li class='col-lg-6 col-md-6 col-sm-6' >
					<a id='photo_button' href='account.php?act=pht' <?php if(isset($_GET['act']) && $_GET['act'] === 'pht') echo "style='background: #43D1AF;'"?>> My photos</a>
				</li>
			</ul>

			<?php
				if (isset($_GET['error']))
					include('partials/errors.php');
			?>
			<div id="account_mngmt" class='col-lg-12 col-md-12 col-sm-12'>
				<?php
					if (isset($_GET['act']) && $_GET['act'] != NULL)
					{
						if ($_GET['act'] === 'pht'  || isset($_GET['page']))
						{
							include('manage_photos.php');
							echo "<script type='text/javascript' src='js/manage_photos.js'></script>";
						}
						else if ($_GET['act'] === 'mng')
						{
							if (isset($_GET['ch']) && $_GET['ch'] != NULL)
							{
								if ($_GET['ch'] == 1)
									echo "<span class='col-lg-12 col-md-12 col-sm-12 ch_success'>Your password was successfully changed</span>";
								else if ($_GET['ch'] == 2)
									echo "";
							}
							include('manage_account.php');
						}
					}
					else
						include('manage_account.php');
				?>
			</div>

			<div class='col-lg-12 col-md-12 col-sm-12'>
				<?php
				if (!isset($_GET['page']) || $_GET['page'] == 0)
					$page = 1;
				else
					$page = $_GET['page'];
				// $photos = $db->get_user_photos($id, $page, 6);
				?>
			</div>


		</div>


	</div>

	<footer>
		<?php include("layout/footer.html"); ?>
	</footer>
</body>
