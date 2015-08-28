<?php
require_once(dirname(__DIR__) . "/classes/profile.php");
require_once(dirname(__DIR__) . "/classes/user.php");
require_once("/etc/apache2/data-design/encrypted-config.php");
require_once(dirname(__DIR__) . "/lib/xsrf.php");

try {
	//ensures that the fields are filled out
	if(@isset($_POST["loginEmail"]) === false || @isset($_POST["loginPassword"]) === false) {
		throw(new InvalidArgumentException("form not complete. Please verify and try again"));
	}

	// verify the XSRF challenge
	if(session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}
	verifyXsrf();

//	// create a salt and hash for user
	$SALT = bin2hex(openssl_random_pseudo_bytes(32));
	$HASH = hash_pbkdf2("sha512", $_POST["password"], $SALT, 262144, 128);

	//sign in user by email profile
	$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/trufork.ini");
	$profile = Profile::getProfileByEmail($pdo, $_GET["profile"], $_GET["email"]);
	if(empty($profile))
	{
		throw(new InvalidArgumentException("invalid username or password"));
	}
	$user = User::getUserByUserId($pdo, $_GET["SALT"], $_GET[$HASH], $_GET["email"]);
	if(empty($profile))
	{
//	$profile = Profile::getProfileByUserId($pdo, $_GET["profile"])
//		throw(new InvalidArgumentException("invalid username or password");
	}

	} catch (Exception $exception) {
		echo "<p class=\"alert alert-danger\">" . $exception->getMessage() . "</p>";
}


//	$user = new User(null, $SALT, $HASH);
//	$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/trufork.ini");
//	$user =  User(null, $SALT, $HASH);
//	$user->get($pdo);
//	$profile = Profile(null, $user->getUserByEmail(), $_GET["userName"], $_GET["$SALT"], $_GET[$HASH]);
//	$profile->insert($pdo);

