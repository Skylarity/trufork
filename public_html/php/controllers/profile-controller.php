<?php
require_once(dirname(__DIR__) . "/classes/profile.php");
require_once(dirname(__DIR__) . "/classes/user.php");
require_once(dirname(__DIR__) . "/lib/aes256.php");
require_once(dirname(__DIR__) . "/lib/xsrf.php");

try {
	//ensures that the fields are filled out
	if(@isset($_POST["profileId"]) === false || @isset($_POST["salt"]) === false || @isset($_POST["hash"]) === false || @isset($_POST["email"]) === false) {
		throw(new InvalidArgumentException("form not complete. Please verify and try again"));
	}

	// verify the XSRF challenge
	if(session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}
	verifyXsrf();

	//create a new user id and insert in mySQL
	$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/trufork.ini");
	$profile = new User(null, $_POST["userId"], $_POST["salt"], $_POST["hash"], $_POST["email"]);
	$userId->insert($pdo);
	echo "<p class\"alert alert-success\">profile (id = " . $userId->getUserId() . ") posted!<p/>";
}catch (Exception $e) {
	echo "<p class=\"alert alert-danger\">Exception: " . $e->getMessage() . "</p>";

}