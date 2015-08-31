<?php
require_once(dirname(__DIR__) . "/classes/data-downloader.php");
require_once(dirname(__DIR__) . "/classes/restaurant.php");
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

	if($data->status === "OK") {
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
			echo "<li class=\"list-group-item\"><strong>" . $result->formatted_address . "</strong></li>" . PHP_EOL;
							echo "<li class=\"list-group-item\"><strong>" . $result->name . "</strong></li>" . PHP_EOL;
			echo "<li class=\"list-group-item\"><strong>" . $result->rating . "</strong></li>" . PHP_EOL;
			echo "<li class=\"list-group-item\"><strong>" . $result->place_id . "</strong></li>" . PHP_EOL;
			//	echo "<li class=\"list-group-item\"><strong>" . $result->price_level . "</strong></li>" . PHP_EOL;
			echo "<li class=\"list-group-item\"><strong>" . $result->geometry->location->lat . "</strong></li>" . PHP_EOL;
			echo "<li class=\"list-group-item\"><strong>" . $result->geometry->location->lng . "</strong></li>" . PHP_EOL;
			echo "</ul>";

			$detailsUrl = "https://maps.googleapis.com/maps/api/place/details/json?key=" . $config["placekey"]. "&placeid=".
				$result->place_id;
				$detailsResponse = json_decode(file_get_contents($detailsUrl));
				var_dump($detailsResponse->result);

				if($detailsResponse->status ==="OK") {
//							foreach($detailsResponse->result as $response=>$response_value){
//											echo "direccion y tel".$response."y aqui q".$response_value;
//							}
					//		}}
					$fullAddress = [];
					$gooArray = array();
					$address_components = $detailsResponse->address_components;

					//foreach($address_components as $component) {
					foreach($address_components as $component) {
						echo "dir=" . $component;
						$fullAddress[] = $component["short_name"];
					}

					$fullAddress = implode(" ", $fullAddress);
					echo "$fullAddress";
				}
			// we will put ours address in capital letters

			$fullAddress = strtoupper($fullAddress);

			/*
					*  this subcode is trying to match the city address  with google address
			*/

			$restaurant = array();
			$gMatches = array();
			$cMatches = array();
			$pattern = "/^(\d{1,6)\s+(.+)\s+(NE|NW|SE|SW).*(\d+)?$/iU";
			// how we grab the address from goggle
			$subjectg = $result->formatted_address;

			$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/trufork.ini");
			$restaurant = Restaurant::getRestaurantByAddress($pdo, $subjectg);
			var_dump($restaurant);
			if($restaurant !== null) {
				$subjectc = $restaurant->getAddress();
				preg_match($pattern, $subjectg, $gMatches);
				preg_match($pattern, $subjectc, $cMatches);

				$g0 = $gMatches[0];
				$c0 = $cMatches[0];
				if($g0 === $c0) {
					//create a new Restaurant object, and output it to an array ($restaurants)
					$restaurant = Restaurant::getRestaurantByGoogleId($pdo, $googleId);
					if($restaurant === null) {
						$restaurant = Restaurant::getRestaurantByAddress($pdo, $g0);
						$restaurant->setGoogleId($googleId);
						$restaurant->update($pdo);
					}
				}
			}

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
