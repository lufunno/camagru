<?PHP

header("Location: index.php");

session_start();
unset($_SESSION["logged_on_user"]);

?>
