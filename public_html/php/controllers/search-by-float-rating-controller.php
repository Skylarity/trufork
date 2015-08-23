<?php

/** Search by TruFork Rating Controller
 * This is part of an MVC
 * Searches restaurant class/table
 */

require_once(dirname(__DIR__) . "/classes/restaurant.php");
require_once("/etc/apache2/data-design/encrypted-config.php");

$mysqli = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/trufork.ini");

/** select queries and return a result set */
if ($result = $mysqli->query("SELECT name FROM restaurant WHERE forkRating >=3")) {
	printf("Select returned %d rows.\n", $result->num_rows);

	/** free result set */
	$result->close();
}

$mysqli->close();
