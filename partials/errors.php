<?php

if (isset($_GET['username']))
	$username = $_GET['username'];
else
	$username = NULL;

$errors = array(
	1 => "Password must be at least 4 characters long and contain at least one non-letter character",
	2 => "login already taken",
	3 => "Your account hasn't been activated yet, check your email or <a href='resend_email.php?username=" . $username . "'>send email again</a>",
	4 => "wrong username or password",
	5 => "passwords don't match",
	6 => "an account has already been created with this email address",
	7 => "wrong password",
	8 => "Your account was already activated"
	);

// echo $_POST[' username'];

echo "<div class='error'>ERROR: ";
echo $errors[$_GET['error']];
echo "</div>";

?>