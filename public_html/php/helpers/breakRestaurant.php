<?php

require_once("../classes/restaurant.php");
require_once("/etc/apache2/data-design/encrypted-config.php");

$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/trufork.ini");

$restaurantId = null;
$googleId = "ChIJ6fRj1sudP4YR0Q6Z7BhX4UM";
$facilityKey = "FA0111865";
$name = "\"Freshy's Burgers and More\"";
$name = str_replace("\"", "", $name); // The city gives names quotes for some reason
$address = "218 MARBLE AV NW";
$phone = "5055555555";
$forkRating = 0;

$restaurant = new Restaurant($restaurantId, $googleId, $facilityKey, $name, $address, $phone, $forkRating);

try {
	$restaurant->insert($pdo);
	$restaurant->insert($pdo);
} catch(PDOException $pdoException) {
	echo $pdoException->errorInfo[0];
}