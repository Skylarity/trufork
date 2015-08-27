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
	if(in_array("5", $_GET["rating"]) === true) {
		$result = Restaurant::getRestaurantsByForkRating($pdo, 5, 5.1);
		var_dump($result);
	}

	if(in_array("4", $_GET["rating"]) === true) {
		$result = Restaurant::getRestaurantsByForkRating($pdo, 4, 5);
		var_dump($result);
	}

	if(in_array("3", $_GET["rating"]) === true) {
		$result = Restaurant::getRestaurantsByForkRating($pdo, 3, 4);
		var_dump($result);
	}

	if(in_array("2", $_GET["rating"]) === true) {
		$result = Restaurant::getRestaurantsByForkRating($pdo, 2, 3);
		var_dump($result);
	}

	if(in_array("1", $_GET["rating"]) === true) {
		$result = Restaurant::getRestaurantsByForkRating($pdo, 1, 2);
		var_dump($result);
	}

	if(in_array("0", $_GET["rating"]) === true) {
		$result = Restaurant::getRestaurantsByForkRating($pdo, 0, 1);
		var_dump($result);
	}

			// ???
			// profit!


} catch(Exception $exception) {
	echo "<p class=\"alert alert-danger\">" . $exception->getMessage() . "</p>";
}

//foreach( $result->fetchAll() as $row ) {
	//$html_table .= '<tr>' . "\n";
	//foreach( $row as $col ) {
		//$html_table .= '<td>' .$col. '</td>';
	//}
	//$html_table .= '</tr>' . "\n";
//}

//echo '<table cellpadding="1" cellspacing="1" border="1">';


//foreach([$result as $rating) {
//	echo '<td>' . $rating["name"] . '</td>';
//	echo '<td>' . $rating["address"] . '</td>';
//}
//	echo '</table>';

