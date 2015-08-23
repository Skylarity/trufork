<?php
require_once(dirname(__DIR__) . "/classes/comment.php");
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
	verifyXsrf();

	// handle datetime as it will need to be inserted
	$newDateTime = new DateTime();

	// create the new comment and insert into mySQL
	$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/trufork.ini");
	$comment = new Comment(null, $_POST["profileId"], $_POST["restaurantId"], $newDateTime, $_POST["txtComment"]);
	//$comment = new Comment(null, null, null, null, null);
	$comment->insert($pdo);
	echo "<p class=\"alert alert-success\">Comment posted!</p>";
} catch(Exception $exception) {
	echo "<p class=\"alert alert-danger\">Exception: " . $exception->getMessage() . "</p>";

	//need to include sign-in info from session (see https://bootcamp-coders.cnm.edu/class-materials/php/sessions/)

}