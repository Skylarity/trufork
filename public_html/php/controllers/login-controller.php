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
	$profile = getUserByEmail(null, $SALT, $HASH);
	public static function getProfileByEmail(PDO &$pdo, $profile) {



////	$user = new User(null, $SALT, $HASH);
//	$user->insert($pdo);
	$profile = Profile(null, $user->getUserId(), $_GET["userName"], $_GET["$SALT"], $_GET[$HASH]);
	$profile->insert($pdo);
//	echo "<p class\"alert alert-success\">User (id = " . $user->getUserId() . ") posted!<p/>";
}catch (Exception $e) {
//	echo "<p class=\"alert alert-danger\">Exception: " . $e->getMessage() . "</p>";

//	echo "<p class\"alert alert-success\">Profile (id = " . $profile->getUserId() . ") posted!<p/>";
}catch (Exception $e) {
//	echo "<p class=\"alert alert-danger\">Exception: " . $e->getMessage() . "</p>";


}