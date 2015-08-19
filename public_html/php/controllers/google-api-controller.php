<?php
require_once("/etc/apache2/data-design/encrypted-config.php");

try {
//grab api key from config file
	$config = readConfig("/etc/apache2/capstone-mysql/trufork.ini");

//grab user query, sanitize and validate
	$userQuery = filter_input(INPUT_GET, "userQuery", FILTER_SANITIZE_STRING);
	$userQuery = trim($userQuery);
	if(empty($userQuery) === true) {
		throw(new InvalidArgumentException("user query is empty or insecure"));
	} else {
		$userQuery = urlencode($userQuery);
	}

//construct url
	$url = "https://maps.googleapis.com/maps/api/place/textsearch/json?key=" . $config["placekey"] . "&location=35.08574,-106.64953&radius=20000&types=cafe|food|restaurant&query=" . $userQuery;


	$response = file_get_contents( $url);
			if ($response->status === "ok") {
				var_dump(json_decode($response));

			} else  if($response-> status ==="zero_results"){
							throw(newInvalitArgumentException("this search get zero resul nada"));
			}
} catch(InvalidArgumentException $invalidArgument) {
	throw(new InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument));
} catch(RangeException $range) {
	// Rethrow exception to the caller
	throw(new RangeException($range->getMessage(), 0, $range));
} catch(Exception $exception) {
	// HUMBERTO WILL WRITE EXCEPTION HANDLING HERE :D
	// Rethrow exception to the caller
	throw(new Exception($exception->getMessage(), 0, $exception));
}
?>