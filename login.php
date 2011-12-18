<?php
require_once 'classes/Auth.class.php';

session_start();

$auth = new Auth();

if (isset($_POST['username']) && isset($_POST['password'])) {
	$status = $auth->login($_POST['username'], $_POST['password']);
	
	if ($status == 0) {
		header('Location: loggedIn.php');
	}
	else {
		switch ($status) {
			case 1:
				$error = 'User not verified, please check your email for verification';
				break;
			case 2:
				$error = 'User is not active, please check your email for activation information';
				break;
			case 3:
				$error = 'Username and password correct, but issue logging in, try again.';
				break;
			case 4:
				$error = 'Error logging in, please check username and/or password and try again';
				break;
		}
	}
}
else {
	?>
<html>
<head>
<title>
</title>
</head>
<body>
<form method="post" action="login.php" name="loginForm">
<?php if (isset($error)) {
	echo 'There was an issue logging in. Error: ' . $error . '<br />';
}
else if (isset($_GET['verified'])) {
	echo 'Your account is verified, please login below <br />';
}
?>

Username/Email: <input type="text" name="username" />
<br />Password: <input type="password" name="password" />
<br /><input type="submit" value="Login" />
</form>
</body>
</html>	
	<?php 
}
?>