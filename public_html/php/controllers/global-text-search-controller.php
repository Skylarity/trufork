<?php

/** Global Text Search Controller
 * This is part of an MVC
 * Searches restaurant class/table by restaurant name
 *
 * THIS CONTROLLER IS NOT READY!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 */

require_once(dirname(__DIR__) . "/lib/xsrf.php");
require_once("/etc/apache2/data-design/encrypted-config.php");

try {
	var_dump($_GET);
	// ensure the field is actually filled out properly
	if(@isset($_GET["name"]) === false) {
		throw(new InvalidArgumentException ("You must enter a restaurant name. Please try again."));
	}

	// verify the XSRF challenge; commented out bc this isn't a POST and there is no real user input
	if(session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}
	verifyXsrf();

	$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/trufork.ini");

	/** select queries and return a result set
	 *
	 * ...*/
	/**
	 * $i is used as the loop iterator...
	 * a database call within a loop is generally not optional,
	 * but this case offers no alternatives
	 **/
	$echoChamber = [];
	$noneFound = false;
	for($i = 5; $i >= 0; $i--) {
		if(in_array($i, $_GET["rating"]) === true) {
			$result = Restaurant::getRestaurantsByForkRating($pdo, $i, $i + 1);
			$echoChamber = array_merge($echoChamber, $result->toArray());
			if(count($result) > 0) {
//				echo implode($result);
			} else {
				$noneFound = true;
			}
		}
	}
	if($noneFound) {
		echo "No restaurants were found with that name.";
	}

	var_dump($echoChamber);
	foreach($echoChamber as $restaurant) {
		echo "<ul>" . PHP_EOL .
			"<li>" . $restaurant->getName() . "</li>" . PHP_EOL .
			"<li>" . $restaurant->getForkRating() . "</li>" . PHP_EOL .
			"<li>" . $restaurant->getAddress() . "</li>" . PHP_EOL .
			"</ul>" . PHP_EOL ;
	}


//this shows the error msg from above if user clicks without entering a value first
} catch(Exception $exception) {
	echo "<p class=\"alert alert-danger\">" . $exception->getMessage() . "</p>";
}



