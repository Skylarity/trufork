<?php
require_once(dirname(__DIR__) . "/classes/data-downloader.php");
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

	$response = file_get_contents($url);
	$data = json_decode($response);
	var_dump($data);

	if($data->status === "OK"){
		foreach($data->results as $result) {
			$photoUrl = "https://maps.googleapis.com/maps/api/place/photo?maxheight=512&photoreference=" . $result->photos[0]->photo_reference . "&key=" . $config["placekey"];
			$imgUrl = null;
			$metadata = DataDownloader::getMetaData($photoUrl, 0);
			foreach($metadata["wrapper_data"] as $header) {
				if(strpos($header, "Location: ") === 0) {
					$imgUrl = substr($header, 10);
					break;
				}
			}

			//write a foreach for the Geometry data array
			//var_dump($result->geometry);

			if($imgUrl !== null) {
				echo "<img class=\"img-responsive\" src=\"$imgUrl\" />" . PHP_EOL;
			}
			echo "<ul class=\"list-group\">" . PHP_EOL;
			echo "<li class=\"list-group-item\"><strong>" . $result->formatted_address."</strong></li>". PHP_EOL;
			echo "<li class=\"list-group-item\"><strong>" . $result->name . "</strong></li>" . PHP_EOL;
			echo "<li class=\"list-group-item\"><strong>" . $result->rating. "</strong></li>". PHP_EOL;
			echo "<li class=\"list-group-item\"><strong>" . $result->place_id."</strong></li>". PHP_EOL;
			echo "<li class=\"list-group-item\"><strong>" . $result->icon."</strong></li>". PHP_EOL;
			echo "<li class=\"list-group-item\"><strong>" . $result->geometry->location->lat ."</strong></li>". PHP_EOL;
			echo "<li class=\"list-group-item\"><strong>" . $result->geometry->location->lng ."</strong></li>". PHP_EOL;
		}
	} else {
		echo "error message here";
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