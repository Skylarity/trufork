<?php
require_once(dirname(__DIR__) . "/classes/profile.php");
require_once(dirname(__DIR__) . "/classes/user.php");
require_once("/etc/apache2/data-design/encrypted-config.php");
require_once(dirname(__DIR__) . "/lib/xsrf.php");

try {
	//ensures that the fields are filled out
	if(@isset($_POST["userName"]) === false || @isset($_POST["password"]) === false) {
		throw(new InvalidArgumentException("form not complete. Please verify and try again"));
	}

	// verify the XSRF challenge
	if(session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}
	verifyXsrf();

	// create a salt and hash for user
	$SALT = bin2hex(openssl_random_pseudo_bytes(32));
	$HASH = hash_pbkdf2("sha512", $_POST["password"], $SALT, 262144, 128);

	//create a new user id profile id and insert in mySQL
	$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/trufork.ini");
	$user = $_GET User::getUserByUserId(	)
	$user->insert($pdo);
	$profile = new Profile(null, $user->getUserId(), $_POST["email"]);
	$profile->insert($pdo);
//	echo "<p class\"alert alert-success\">User (id = " . $user->getUserId() . ") posted!<p/>";
}catch (Exception $e) {
//	echo "<p class=\"alert alert-danger\">Exception: " . $e->getMessage() . "</p>";

//	echo "<p class\"alert alert-success\">Profile (id = " . $profile->getUserId() . ") posted!<p/>";
}catch (Exception $e) {
//	echo "<p class=\"alert alert-danger\">Exception: " . $e->getMessage() . "</p>";


}