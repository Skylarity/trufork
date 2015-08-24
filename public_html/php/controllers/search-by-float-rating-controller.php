<?php

/** Search by TruFork Rating Controller
 * This is part of an MVC
 * Searches restaurant class/table by trufork rating
 */

require_once(dirname(__DIR__) . "/classes/restaurant.php");
require_once(dirname(__DIR__) . "/lib/xsrf.php");
require_once("/etc/apache2/data-design/encrypted-config.php");

$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/trufork.ini");

/** select queries and return a result set
 	 currently only permits db search of values btwn 3.0 and 3.9 */
if ($result = $mysqli->query("SELECT name FROM restaurant WHERE forkRating >= 3 < 4")) {
	printf("Select returned %d rows.\n", $result->num_rows);

	/** free result set */
	$result->close();
}

$mysqli->close();

