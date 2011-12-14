<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once 'classes/Auth.class.php';

session_start();

$auth = new Auth();

if (isset($_POST['email']) && isset($_POST['pw1'])) {
	if ($auth->createUser($_POST['email'], $_POST['pw1'])) {
		echo 'Account Created';
	}
}

?>

<html>
<head>
<title>
Register for site
</title>
</head>

<body>
<form name="register" action="register.php" method="post">
Email: <input type="text" name="email" />
<br />Password: <input type="password" name="pw1" />
<br /><input type="Submit" value="Submit" />
</form>
</body>

</html>