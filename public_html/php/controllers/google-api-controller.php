<?php

require_once(dirname(__DIR__) . "/classes/data-downloader.php");
require_once(dirname(__DIR__) . "/classes/restaurant.php");
require_once(dirname(__DIR__) . "/classes/violation.php");

require_once("/etc/apache2/data-design/encrypted-config.php");

try {
	//grab api key from config file
	$config = readConfig("/etc/apache2/capstone-mysql/trufork.ini");
	$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/trufork.ini");

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

	if($data->status === "OK") {
		$matchedRestaurants = [];
		foreach($data->results as $result) {
			$rating = 0.0;
			if(empty($result->rating) === false) {
				$rating = $result->rating;
			}

//			$imgUrl = null;
//			if(empty($result->photos) === false) {
//				$photoUrl = "https://maps.googleapis.com/maps/api/place/photo?maxheight=512&photoreference=" . $result->photos[0]->photo_reference . "&key=" . $config["placekey"];
//				$metadata = DataDownloader::getMetaData($photoUrl, 0);
//				foreach($metadata["wrapper_data"] as $header) {
//					if(strpos($header, "Location: ") === 0) {
//						$imgUrl = substr($header, 10);
//						break;
//					}
//				}
//			}

			$detailsUrl = "https://maps.googleapis.com/maps/api/place/details/json?key=" . $config["placekey"] . "&placeid=" .
				$result->place_id;
			$detailsResponse = json_decode(file_get_contents($detailsUrl));

			if($detailsResponse->status === "OK") {
				$fullAddress = ["street_number" => null, "route" => null];
				$gooArray = array();
				$address_components = $detailsResponse->result->address_components;
				foreach($address_components as $component) {
					foreach($component->types as $type) {
						if($type === "route") {
							$fullAddress["route"] = $component->short_name;
						}
						if($type === "street_number") {
							$fullAddress["street_number"] = $component->short_name;
						}
					}
				}
				$fullAddress = implode(" ", $fullAddress);
			}
			// we will put ours address in capital letters
			$fullAddress = trim(strtoupper($fullAddress));

			$googleId = $result->place_id;
			//grab the address from city
			//start the session
			try {
				$matchedRestaurantResult = Restaurant::getRestaurantsByAddress($pdo, $fullAddress);
				$matchedRestaurants = array_merge($matchedRestaurants, $matchedRestaurantResult->toArray());
				foreach($matchedRestaurantResult as $matchedRestaurant) {
					$comments = 0;
					$violations = Violation::getViolationByRestaurantId($pdo, $matchedRestaurant->getRestaurantId());
					foreach($violations as $violation) {
						if($violation->getViolationDesc() !== null) {
							$comments++;
						}
					}

					//creating the trufork rating
					$truFork = $rating * (-0.125 * sqrt($comments) + 1);
					$matchedRestaurant->setForkRating($truFork);
					$matchedRestaurant->setGoogleId($result->place_id);
					$matchedRestaurant->update($pdo);
				}
			} catch(Exception $exception) {
				// cállate
			}
		}
		if(session_status() !== PHP_SESSION_ACTIVE) {
			session_start();
		}
		$_SESSION["matchedRestaurants"] = $matchedRestaurants;
	}

} catch(InvalidArgumentException $invalidArgument) {
	throw(new InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument));
} catch(RangeException $range) {
	// Rethrow exception to the caller
	throw(new RangeException($range->getMessage(), 0, $range));
} catch(Exception $exception) {
	// Rethrow exception to the caller
	throw(new Exception($exception->getMessage(), 0, $exception));
}

header("Location: ../../results/", true, 301);