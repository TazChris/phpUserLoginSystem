<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once 'classes/Auth.class.php';

session_start();

$auth = new Auth();

if (!$auth->checkSession()) {
	echo 'Session not active<br />';
	echo '<a href="login.php">Click here to go to login page</a><br />';
}
else {
	echo "User ID" . $_SESSION['user_id'];
	echo '<br />';
	echo 'Session Active <br />';
	echo '<a href="logout.php">Click here to go to logout page"</a><br />';
}
?>