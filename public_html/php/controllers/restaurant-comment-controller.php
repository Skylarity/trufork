<?php
require_once(dirname(__DIR__) . "classes/comment.php");
require_once(dirname(__DIR__) . "lib/restaurant.php");
require_once(dirname(__DIR__) . "lib/xsrf.php");
require_once(dirname(__DIR__) . "lib/encrypted-config.php");

try {
	// ensure te field is actually filled out properly
	if(@isset($_POST["commentContent"]) === false) {
		throw(new InvalidArgumentException ("Comment not complete. Please try again."));
	}

	// verify the XSRF challenge
	if(session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}
	verifyXsrf();

	// create the new comment and insert into mySQL
	$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/trufork.ini");
	$comment = new Comment(null, $_POST["commentContent"]);
	$comment->insert($pdo);
	echo "<p class=\"alert alert-success\">Comment (id = " . $comment->getCommentId() . ") posted!</p>";
}catch(Exception $exception) {
	echo "<p class=\"alert alert-danger\">Exception: " . $exception->getMessage() . "</p>";

	//need to include sign-in info from session (see https://bootcamp-coders.cnm.edu/class-materials/php/sessions/)

}