<?php
require_once('library/Database.class.php');
require_once('library/User.class.php');
session_start();

if (!isset($_SESSION['logged_on_user']) || $_SESSION[ 'logged_on_user'] == NULL)
		 	header('Location: index.php');

?>
<!doctype html>
<html>
<head>
	<title>Camagru</title>
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="stylesheet" type="text/css" href="css/gallery.css">
	<link rel="stylesheet" type="text/css" href="css/form.css">
</head>
<body>
	<div class="wrapper">
	<header>
		 <?php
		 	include("layout/header.html"); ?>
	</header>

	<div class="mid col-lg-12 col-md-12 col-sm-12">
		<div id="gallery_title">
			<h2> Browse, like and comment! </h2>
		</div>

<?php

include('library/display_photos.php');

if (isset($_SESSION['logged_on_user']) || $_SESSION['logged_on_user'] == NULL)
	display_photos('all');

	?>
</div>
</div>
	<footer>
		<?php include("layout/footer.html"); ?>
	</footer>


<script type="text/javascript" src="js/likes_comments.js"></script>
</body>
</html>
