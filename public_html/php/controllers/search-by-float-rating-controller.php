<?php

/** Search by TruFork Rating Controller
 * This is part of an MVC
 * Searches restaurant class/table by trufork rating
 */

require_once(dirname(__DIR__) . "/classes/restaurant.php");
//require_once(dirname(__DIR__) . "/lib/xsrf.php");
require_once("/etc/apache2/data-design/encrypted-config.php");

try {
//	var_dump($_GET);
	// ensure the field is actually filled out properly
	if(@isset($_GET["rating"]) === false) {
		throw(new InvalidArgumentException ("You must select at least one value. Please try again."));
	}

// verify the XSRF challenge; commented out bc this isn't a POST and there is no real user input
	//if(session_status() !== PHP_SESSION_ACTIVE) {
	//session_start();
	//}
	//verifyXsrf();

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
	$matchedRestaurants = [];
	$noneFound = false;
	for($i = 5; $i >= 0; $i--) {
		if(in_array($i, $_GET["rating"]) === true) {
			$result = Restaurant::getRestaurantsByForkRating($pdo, $i, $i + 1);
			$echoChamber = array_merge($echoChamber, $result->toArray());
			$matchedRestaurants[] = $result;
			if(count($result) > 0) {
//				echo implode($result);
			} else {
				$noneFound = true;
			}
		}
	}
	if($noneFound) {
		echo "No restaurants were found with that TruFork rating.";
	}

//	var_dump($echoChamber);
	foreach($echoChamber as $restaurant) {
		echo "<ul>" . PHP_EOL .
			"<li>" . $restaurant->getRestaurantId() . "</li>" . PHP_EOL . /** do we need this to populate w clickable links? */
			"<li>" . $restaurant->getName() . "</li>" . PHP_EOL .
			"<li>TruFork Rating = " . $restaurant->getForkRating() . "</li>" . PHP_EOL .
			"<li>" . $restaurant->getAddress() . "</li>" . PHP_EOL .
			"</ul>" . PHP_EOL;
//			'<a href="php/lib/restaurant.php?id='.$result['restaurantId'].'">'.$result['name'].'</a>';
	}

	$_SESSION["matchedRestaurants"] = $matchedRestaurants;

	// ???
	// profit!

//this shows the error msg from above if user clicks without selecting a value first
} catch(Exception $exception) {
	echo "<p class=\"alert alert-danger\">" . $exception->getMessage() . "</p>";
}
