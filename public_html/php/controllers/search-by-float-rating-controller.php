<?php

/** Search by TruFork Rating Controller
 * This is part of an MVC
 * Searches restaurant class/table by trufork rating
 */

require_once(dirname(__DIR__) . "/classes/restaurant.php");
//require_once(dirname(__DIR__) . "/lib/xsrf.php");
require_once("/etc/apache2/data-design/encrypted-config.php");

try {
	var_dump($_GET);
	// ensure the field is actually filled out properly
	if(@isset($_GET["rating"]) === false) {
		throw(new InvalidArgumentException ("You must select at least one value. Please try again."));
	}

// verify the XSRF challenge
		//if(session_status() !== PHP_SESSION_ACTIVE) {
			//session_start();
		//}
		//verifyXsrf();

		$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/trufork.ini");

		/** select queries and return a result set
		 *
		  ...*/
	/**
	 * $i is used as the loop iterator...
	 * ...this is from the old days of for loops in C in the 1970s
	 * LOL Skyler's young!
	 **/
	$echoChamber = [];
	for($i = 5; $i >= 0; $i--) {
		if(in_array($i, $_GET["rating"]) === true) {
			$result = Restaurant::getRestaurantsByForkRating($pdo, $i, $i + 1);
			$echoChamber = array_merge($echoChamber, $result->toArray());
		}
	}

	var_dump($echoChamber);
	foreach($echoChamber as $restaurant) {
		echo "<ul>" . PHP_EOL .
				"<li>" . $restaurant->getName() . "</li>" . PHP_EOL .
				"<li>" . $restaurant->getForkRating() . "</li>" . PHP_EOL .
			"</ul>" . PHP_EOL;
	}


			// ???
			// profit!

//this shows the error msg from above if user clicks without selecting a value first
} catch(Exception $exception) {
	echo "<p class=\"alert alert-danger\">" . $exception->getMessage() . "</p>";
}
