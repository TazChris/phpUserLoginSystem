<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
include_once 'classes/Auth.class.php';

session_start();

$auth = new Auth();

if (!$auth->checkSession()) {
	header("Location: login.php");
}
else if (isset($_POST['curpw']) 
			&& isset($_POST['newpw1'])
			&& isset($_POST['newpw1'])) {
	if ($_POST['newpw1'] == $_POST['newpw2']) {
		if ($auth->changePassword($_SESSION['user_id'], $_SESSION['email'], $_POST['curpw'], $_POST['newpw1'])) {
			echo 'Password change successfully';
		}
		else {
			echo 'Issue changing password';
		}
	}
	else {
		$error = 'New passwords do not match';
	}
}
else ?>

<html>
<head>
<title>
Change PW
</title>
<body>
<form name="changepw" action="change_password.php" method="post">
Current Password: <input type="password" name="curpw" />
<br />New Password: <input type="password" name="newpw1" />
<br />Verify New Password: <input type="password" name="newpw2" />
<input type="submit" value="Change Password" />

</form>
</body>

</head>
</html>