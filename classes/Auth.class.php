<?php
require_once 'classes/AuthDB.class.php';

class Auth {
	private $_siteKey;
	private $_db;

	public function __construct()
	{
		$this->siteKey = 'SLLLLSDIE*#&Slks*(Lsdf***,asdf';
	}

	private function randomString($length = 50)
	{
		$characters = '0123456789abcdefghijklmnopqrstuvwxyz!@#$%^&*()ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$string = '';

		for ($p = 0; $p < $length; $p++) {
			$string .= $characters[mt_rand(0, strlen($characters) - 1)];
		}

		return $string;
	}

	protected function hashData($data)
	{
		return hash_hmac('sha512', $data, $this->_siteKey);
	}

	public function isAdmin()
	{
		//$selection being the array of the row returned from the database.
		if($selection['is_admin'] == 1) {
			return true;
		}

		return false;
	}

	public function createUser($email, $password, $is_admin = 0)
	{
		$this->_db = new AuthDB();

		//Generate users salt
		$user_salt = $this->randomString();
			
		//Salt and Hash the password
		$password = $user_salt . $password;
		$password = $this->hashData($password);
			
		//Create verification code
		$code = $this->randomString();

		//Commit values to database here.
		$created = $this->_db->createUser($email, $password, $user_salt, $code);

		$this->_db = null;

		if($created != false){
			return true;
		}
			
		return false;
	}

	public function login($email, $password)
	{
		$this->_db = new AuthDB();

		//Select users row from database base on $email
		$selection = $this->_db->getUserInfo($email);

		//Salt and hash password for checking
		$password = $selection[0]['user_salt'] . $password;
		$password = $this->hashData($password);

		//Check email and password hash match database row
		if ($password == $selection[0]['password']) $match = true;
		else $match = false;

		//Convert to boolean
		$is_active = (boolean) $selection[0]['is_active'];
		$verified = (boolean) $selection[0]['is_verified'];

		if($match == true) {
			if($is_active == true) {
				if($verified == true) {
					//Email/Password combination exists, set sessions
					//First, generate a random string.
					$random = $this->randomString();
					//Build the token
					$token = $_SERVER['HTTP_USER_AGENT'] . $random;
					$token = $this->hashData($token);

					//Setup sessions vars
					if (!isset($_SESSION)) {
						session_start();
					}
					$_SESSION['token'] = $token;
					$_SESSION['user_id'] = $selection[0]['pkUserId'];

					//Delete old logged_in_member records for user
					$this->_db->removePriorLogins($selection[0]['pkUserId']);

					//Insert new logged_in_member record for user
					$inserted = $this->_db->markUserLoggedIn($selection[0]['pkUserId'], session_id(), $token);

					//Logged in
					if($inserted != false) {
						return 0;
					}

					return 3;
				}
				else {
					//Not verified
					return 1;
				}
			}
			else {
				//Not active
				return 2;
			}
		}

		//No match, reject
		return 4;
	}

	public function checkSession()
	{
		if (isset($_SESSION['user_id'])) { //session available, continue
			//get db routines
			$db = new AuthDB();

			//Select the row
			$selection = $db->checkSession($_SESSION['user_id']);

			//close db
			$db = null;
			unset($db);
			
			if($selection) {
				//Check ID and Token
				if(session_id() == $selection['session_id'] && $_SESSION['token'] == $selection['token']) {
					//Id and token match, refresh the session for the next request
					$this->refreshSession();
					return true;
				}
				//TODO: Possibly remove session since exists ?
			}
		}

		return false;
	}

	private function refreshSession()
	{
		$db = new AuthDB();

		//Regenerate id
		session_regenerate_id();

		//Regenerate token
		$random = $this->randomString();
		//Build the token
		$token = $_SERVER['HTTP_USER_AGENT'] . $random;
		$token = $this->hashData($token);

		//Store in session
		$_SESSION['token'] = $token;

		if ($db->updateSession($_SESSION['user_id'], session_id(), $token)) {
			return true;
		}

		return false;
	}

	public function logout() {
		$db = new AuthDB();

		//destroy session
		session_destroy();

		//delete the row based on user_id
		$db->logoutUser($_SESSION['user_id']);
	}

	public function sendVerification($user_id) {
		;
	}

	public function checkVerification($email, $code) {
		;
	}

}