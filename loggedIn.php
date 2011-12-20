<?php
require_once 'classes/Auth.class.php';
session_start();

$auth = new Auth();
if (!$auth->checkSession()) {
	$auth = null;
	header("Location: login.php");
}
$auth = null;
?>

<html>
<head>
<title>
Logged in
</title>
</head>
<body>
User is logged in;
<br />To change password, click <a href="change_password.php">here</a>
<br />To log out, visit the <a href="logout.php">Logout page</a>
</body>
</html>