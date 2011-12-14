<?php
require_once 'classes/Auth.class.php';

session_start();

$auth = new Auth();

if (!$auth->checkSession()) {
	header("Location: login.php");
}

if (isset($_POST['username'])) {
	$auth = new Auth();
	$auth->logout();
	echo 'Logged out<br />';
	echo '<a href="login.php">Click here to log in again</a><br />';
}
else {
	?>
<html>
<head>
<title>
Logout
</title>
</head>

<body>
<form action="logout.php" method="post" name="form">
<input type="hidden" name="username" value="<?php echo $_SESSION['user_id'] ?>" />
<input type="submit" value="Logout" name="logout" />
</form>
</body>
</html>

<?php 
}
?>