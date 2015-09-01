<?php

require_once(dirname(__DIR__) . "/classes/user.php");
//require_once(dirname(__DIR__) . "/lib/sign-up-login-modal.php");
require_once("/etc/apache2/data-design/encrypted-config.php");
require_once(dirname(__DIR__) . "/lib/xsrf.php");

try {
	//ensures that the fields are filled out
	if(@isset($_POST["name"]) === false || @isset($_POST["password"]) === false || @isset($_POST["verifyPassword"]) === false || @isset($_POST["email"]) === false) {
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
	$user = new User(null, $SALT, $HASH, $_POST["email"], $_POST["name"]);
	$user->insert($pdo);

   echo "<p class=\"alert alert-success\">Welcome" . $user->getUserByName($pdo, "name") . "!<p/>";
}catch (Exception $e) {
	echo "<p class=\"alert alert-danger\">Exception: " . $e->getMessage() . "</p>";
}