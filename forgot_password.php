<?php
include_once 'classes/Auth.class.php';

session_start();

$auth = new Auth();

if ($auth->checkSession()) {
	echo 'You are already logged in. Please use password reset option';
}
else if(isset($_POST['email'])) {
	if ($auth->forgotPassword($_POST['email'])) {
		echo 'Please check your email for new password';
	}
	else {
		echo 'Email address not found, please check and try again.';
	}
}
else {
?>

<html>
<head>
<title>
Forgot PW
</title>
</head>
<body>
<form name="forgotpw" action="forgot_password.php" method="post">
Please enter your email address: <input type="text" name="email" />
<br /><input type="submit" value="Send new Password" />
</form>
</body>
</html>

<?php } ?>