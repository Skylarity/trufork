<?php

/** TruFork Comment System Controller */

require_once(dirname(__DIR__) . "/classes/autoload.php");
require_once(dirname(__DIR__) . "/lib/xsrf.php");
require_once("/etc/apache2/data-design/encrypted-config.php");

try {
	// ensure the field is actually filled out properly
	if(@isset($_POST["txtComment"]) === false) {
		throw(new InvalidArgumentException ("Comment not complete. Please try again."));
	}

	// verify the XSRF challenge
	if(session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}

	// verify the user is even here
	if(empty($_SESSION["user"]) === true) {
		throw(new RuntimeException("Please log in or sign up to submit a comment."));
	}

	verifyXsrf();

	// handle datetime as it will need to be inserted
	$newDateTime = new DateTime();

	// create the new comment and insert into mySQL
	$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/trufork.ini");
	//$comment = new Comment(null, $_POST["userId"], $_POST["restaurantId"], $newDateTime, $_POST["txtComment"]);
	//$comment = new Comment(null, null, null, null, null);
	$comment = new Comment(null, $_SESSION["user"]->getUserId(), $_POST["restaurantId"], $newDateTime, $_POST["txtComment"]);
	$comment->insert($pdo);
	echo "<p class=\"alert alert-success\">Comment posted!</p>";
} catch(Exception $exception) {
	echo "<p class=\"alert alert-danger\">Exception: " . $exception->getMessage() . "</p>";


}