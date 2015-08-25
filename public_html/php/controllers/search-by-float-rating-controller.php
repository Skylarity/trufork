<?php

/** Search by TruFork Rating Controller
 * This is part of an MVC
 * Searches restaurant class/table by trufork rating
 */

require_once(dirname(__DIR__) . "/classes/restaurant.php");
require_once(dirname(__DIR__) . "/lib/xsrf.php");
require_once("/etc/apache2/data-design/encrypted-config.php");

try {
	var_dump($_POST);
	// ensure the field is actually filled out properly
	if(@isset($_POST["rating"]) === false) {
		throw(new InvalidArgumentException ("You must select at least one value. Please try again."));
	}

// verify the XSRF challenge
		//if(session_status() !== PHP_SESSION_ACTIVE) {
			//session_start();
		//}
		//verifyXsrf();

		$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/trufork.ini");

		/** select queries and return a result set
		 * currently only permits db search of values btwn 3.0 and 3.999 tho
		 * doesn't actually work either...*/
		if($result = Restaurant::getRestaurantsByForkRating($pdo, 3, 4))
			var_dump($result);

		if($result = Restaurant::getRestaurantsByForkRating($pdo, 4, 5))
		var_dump($result);
			// ???
			// profit!





} catch(Exception $exception) {
	echo "<p class=\"alert alert-danger\">Exception: " . $exception->getMessage() . "</p>";
}