<?php
require_once 'classes/Auth.class.php';

if (isset($_GET['email']) && isset($_GET['code'])
		&& $_GET['email'] <> '' && $_GET['code'] <> '') {
	$auth = new Auth();

	if ($auth->checkVerification($_GET['email'], $_GET['code'])) {
		header("Location: login.php?verified=1");
	}
	else {
		$verifiedFail = true;
	}
}
else if (isset($_GET['request'])) {
	$auth = new Auth();
	
	if ($auth->sendVerification($_GET['request'])) {
		$requestSent = true;
	}
}

?>

<html>
<head>
<title>
Account Verification
</title>
</head>

<body>
<?php 
if ($verifiedFail) {
	echo 'Problem verifying account, please enter email below to resend verification code';
	echo '<br />';
	echo '<br />';
}
else if (isset($requestSent) && $requestSent == true) {
	echo 'Your verification code has been sent to the email provided.';
	echo '<br />Please check your email for verification code';
	echo '<br />'; 
}	
else if (isset($requestSent) && $requestSent == false) {
	echo 'Your verification code could not be sent to the email provided.';
	echo '<br />Please check your email address and try again.';
	echo '<br />';
}
?>
<form name="verify" method="get" action="verify.php">
Email Address: <input type="text" name="email" />
<br />Verification Code: <input type="text" name="code" />
<br /><input type="submit" value="Verifiy" />
<br />
<br />If you did receive your code, please enter email below
<br />Email for Code Resend: <input type="text" name="request" />
<br /><input type="submit" value="Request Code" />
</form>
</body>
</html>