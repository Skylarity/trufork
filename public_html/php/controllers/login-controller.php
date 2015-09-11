<?php
require_once(dirname(__DIR__) . "/classes/autoload.php");
require_once("/etc/apache2/mysql/encrypted-config.php");
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

	// create a salt and hash for user
	$pdo = connectToEncryptedMySQL("/etc/apache2/mysql/trufork.ini");
	$user = User::getUserByEmail($pdo, $_POST["loginEmail"]);
	if($user === null) {
		throw(new InvalidArgumentException("email or password is invalid"));
	}

	$hash = hash_pbkdf2("sha512", $_POST["loginPassword"], $user->getSalt(), 262144, 128);
	if($hash !== $user->getHash()) {
		throw(new InvalidArgumentException("email or password is invalid"));
	}

	$_SESSION["user"] = $user;
//	$_SESSION["userName"] = $user->getName();
	$userName = $_SESSION["user"]->getName();

	echo "<p class=\"alert alert-success\">Welcome Back, " . $userName . "!<p/>";

} catch(Exception $exception) {
	echo "<p class=\"alert alert-danger\">Exception: " . $exception->getMessage() . "</p>";
}


