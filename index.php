<?php
require_once 'classes/Auth.class.php';

session_start();

$auth = new Auth();

if (!isset($_SESSION['user_id'])) {
	//Not logged in, send to login page.
	header( 'Location: index.html' );
} else {
	//Check we have the right user
	$logged_in = $auth->checkSession();

	if(empty($logged_in)){
		//Bad session, ask to login
		$auth->logout();
		header( 'Location: index.html' );

	} else {
		//User is logged in, show the page
	}
}

