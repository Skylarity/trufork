<?php

require_once("restaurant.php");
require_once("violation.php");
require_once("/etc/apache2/data-design/encrypted-config.php");

/**
 * This class will download ABQ data about restaurant inspections
 *
 * @author Skyler Rexroad skyrex1095@gmail.com
 */
class DataDownloader {

	/*
	 * businesses: http://data.cabq.gov/business/LIVES/businesses.csv
	 * inspections: http://data.cabq.gov/business/LIVES/inspections.csv
	 * violations: http://data.cabq.gov/business/LIVES/violations.csv
	 * old xml: http://data.cabq.gov/business/foodinspections/FoodInspectionsCurrentFY-en-us.xml
	*/

	/**
	 * Gets the metadata from a file url
	 *
	 * @param string $url url to grab from
	 * @param int $redirect whether to redirect or not
	 * @return mixed stream data
	 * @throws Exception if file doesn't exist
	 */
	public static function getMetaData($url, $redirect = 1) {
		$context = stream_context_create(array("http" => array("follow_location" => $redirect, "ignore_errors" => true, "method" => "HEAD")));

		// "@" suppresses warnings and errors
		$fd = @fopen($url, "rb", false, $context);
//		var_dump(stream_get_meta_data($fd));

		// Grab the stream data
		$streamData = stream_get_meta_data($fd);

		fclose($fd);

		$wrapperData = $streamData["wrapper_data"];

		// Loop through to find the "HTTP" attribute
		$http = "";
		foreach($wrapperData as $data) {
			if(strpos($data, "HTTP") !== false) {
				$http = $data;
				break;
			}
		}

		if(strpos($http, "400")) {
			throw(new Exception("Bad request"));
		}
		if(strpos($http, "401")) {
			throw(new Exception("Unauthorized"));
		}
		if(strpos($http, "403")) {
			throw(new Exception("Forbidden"));
		}
		if(strpos($http, "404")) {
			throw(new Exception("Not found"));
		}
		if(strpos($http, "418")) {
			throw(new Exception("Get your tea set"));
		}

		return $streamData;
	}

	/**
	 * Gets the last modified attribute from a file url
	 *
	 * @param string $url url to check
	 * @return string "Last-Modified" attribute
	 */
	public static function getLastModified($url) {
		// Get the stream data
		$streamData = DataDownloader::getMetaData($url);

		// Get the wrapper data that contains the "Last-Modified" attribute
		$wrapperData = $streamData["wrapper_data"];

		// Loop through to find the "Last-Modified" attribute
		$lastModified = "";
		foreach($wrapperData as $data) {
			if(strpos($data, "Last-Modified") !== false) {
				$lastModified = $data;
				break;
			}
		}

		return $lastModified;
	}

	/**
	 * Gets the "Last-Modified" date from a file url
	 *
	 * @param string $url url to check
	 * @return DateTime date last modified
	 */
	public static function getLastModifiedDate($url) {
		// Get the "Last-Modified" attribute
		$lastModified = DataDownloader::getLastModified($url);
		$dateString = null;

		if(strpos($lastModified, "Last-Modified") !== false) {
			// Grab the string after "Last-Modified: "
			$dateString = substr($lastModified, 15);
		}

		$date = new DateTime($dateString);
		$date->setTimezone(new DateTimeZone(date_default_timezone_get()));

		// $formattedDate = $date->format("Y-m-d H:i:s");

		return $date;
	}

	/**
	 * Deletes a file or files from a directory
	 *
	 * @param string $path path to file
	 * @param string $name filename
	 * @param string $extension extension of file
	 */
	public static function deleteFiles($path, $name, $extension) {
		// Delete file(s)
		$files = glob("$path$name*$extension");
		foreach($files as $file) {
//			echo "glob: " . $file . "<br/>";
			unlink($file);
		}
	}

	/**
	 * Gets the date of a stored file
	 *
	 * @param string $path path to stored file
	 * @param string $name name of stored file
	 * @param string $extension extension of stored file
	 * @return DateTime date of stored file
	 * @throws Exception if file does not exist
	 */
	public static function getDateFromStoredFile($path, $name, $extension) {
		// Get date from stored file
		$currentDateStr = null;
		$currentFile = null;
		$files = glob("$path$name*$extension");
		if(count($files) > 0) {
			$currentFile = $files[0];
		} else {
			return DateTime::createFromFormat("U", "0");
		}
//		echo "currentFile: " . $currentFile . "<br/>";

		// Get date from filename
		$matches = array();
		preg_match("/\\d+/", $currentFile, $matches);
		$currentDateStr = $matches[0];
//		echo "currentDateStr: " . $currentDateStr . "<br/>";

		// Create date
		$currentDate = DateTime::createFromFormat("U", $currentDateStr);
//		echo "currentDate: " . $currentDate->format("Y-m-d H:i:s") . "<br/>";

		return $currentDate;
	}

	/**
	 * Downloads a file to a path from a url
	 * Code modified from a stackoverflow answer
	 * http://stackoverflow.com/a/9843981
	 *
	 * @param string $url url to grab from
	 * @param string $path path to save to
	 * @param string $name filename to save in
	 * @param string $extension extension to save in
	 */
	public static function downloadFile($url, $path, $name, $extension) {
		// Delete old file(s)
		DataDownloader::deleteFiles($path, $name, $extension);

		// Create new file
		$newFile = null;
		$newFileName = $path . $name . DataDownloader::getLastModifiedDate($url)->getTimestamp() . ".csv";
		// echo $newFileName;

		$file = fopen($url, "rb");
		if($file) {
			$newFile = fopen($newFileName, "wb");

			if($newFile)
				while(!feof($file)) {
					fwrite($newFile, fread($file, 1024 * 8), 1024 * 8);
				}
		}

		if($file) {
			fclose($file);
		} else {
			fclose($newFile);
		}
	}

	/**
	 * Saves a new version of a file if there is one
	 *
	 * @param string $url url to grab from
	 * @param string $path path to save to
	 * @param string $name filename to save in
	 * @param string $extension extension to save in
	 * @return boolean true if new file was downloaded, false if not
	 */
	public static function downloadIfNew($url, $path, $name, $extension) {
		// Get date of city file
		$newDate = DataDownloader::getLastModifiedDate($url);

		// Get date of stored file
		$currentDate = null;
		$currentDate = DataDownloader::getDateFromStoredFile($path, $name, $extension);

		// If the city file is newer, download it
		if($currentDate !== null) {
			if($newDate > $currentDate) {
				DataDownloader::downloadFile($url, $path, $name, $extension);
				return true;
			} else {
				return false;
			}
		} else {
			DataDownloader::downloadFile($url, $path, $name, $extension);
			return true;
		}
	}

	/**
	 * This function grabs the businesses.csv file and reads it
	 *
	 * @param string $url url to grab file at
	 * @throws PDOException PDO related errors
	 * @throws Exception catch-all exception
	 */
	public static function readBusinessesCSV($url) {
		$context = stream_context_create(array("http" => array("ignore_errors" => true, "method" => "GET")));

		try {
			$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/trufork.ini");

			if(($fd = @fopen($url, "rb", false, $context)) !== false) {
				fgetcsv($fd, 0, ",");
				while((($data = fgetcsv($fd, 0, ",")) !== false) && feof($fd) === false) {
					$restaurantId = null;
					$googleId = "ChIJ6fRj1sudP4YR0Q6Z7BhX4UM";
					$facilityKey = $data[0];
					$name = $data[1];
					$name = str_replace("\"", "", $name); // The city gives names quotes for some reason
					$address = $data[2];
					$phone = $data[6];
					$forkRating = 0;

					// Convert to UTF-8
					$phone = iconv($in_charset = "UTF-16", $out_charset = "UTF-8", $phone);
					if($phone === false) {
						throw(new Exception("Could not convert to UTF-8"));
					}

					try {
						$restaurant = new Restaurant($restaurantId, $googleId, $facilityKey, $name, $address, $phone, $forkRating);
						$restaurant->insert($pdo);
					} catch(PDOException $pdoException) {
						$sqlStateCode = "23000";

						$errorInfo = $pdoException->errorInfo;
						if($errorInfo[0] === $sqlStateCode) {
//							echo "<p>Duplicate</p>";
						} else {
							throw(new PDOException($pdoException->getMessage(), 0, $pdoException));
						}
					} catch(Exception $exception) {
						throw(new Exception($exception->getMessage(), 0, $exception));
					}
				}
				fclose($fd);
			}
		} catch(PDOException $pdoException) {
			throw(new PDOException($pdoException->getMessage(), 0, $pdoException));
		} catch(Exception $exception) {
			throw(new Exception($exception->getMessage(), 0, $exception));
		}
	}

	/**
	 * This function grabs the violations.csv file and reads it
	 *
	 * @param string $url url to grab file at
	 * @throws PDOException PDO related errors
	 * @throws Exception catch-all exception
	 */
	public static function readViolationsCSV($url) {
		$context = stream_context_create(array("http" => array("ignore_errors" => true, "method" => "GET")));

		try {
			$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/trufork.ini");

			if(($fd = @fopen($url, "rb", false, $context)) !== false) {
				$row = 1;
				fgetcsv($fd, 0, ",");
				while((($data = fgetcsv($fd, 0, ",")) !== false) && feof($fd) === false) {
					$num = count($data);
					echo "<p> $num fields in line $row: <br /></p>\n";
					$row++;
					for($c = 0; $c < $num; $c++) {
						echo $data[$c] . "<br />\n";
					}

					$facilityKey = $data[0];
					echo $facilityKey;
					$violationId = null;
					$restaurant = Restaurant::getRestaurantByFacilityKey($pdo, $facilityKey);
					$restaurantId = $restaurant->getRestaurantId();
					$violationCode = $data[2];
					$violationDesc = $data[3];
					$violationDesc = str_replace("\"", "", $violationDesc); // The city gives descriptions quotes for some reason
					$inspectionMemo = $data[3]; // I just put the description in here - probably shouldn't be used
					$inspectionMemo = str_replace("\"", "", $inspectionMemo); // The city gives descriptions quotes for some reason
					$serialNum = $data[2]; // I just put the code in here - probably shouldn't be used

					$violation = new Violation($violationId, $restaurantId, $violationCode, $violationDesc, $inspectionMemo, $serialNum);
					$violation->insert($pdo);
				}
				fclose($fd);
			}
		} catch(PDOException $pdoException) {
			throw(new PDOException($pdoException->getMessage(), 0, $pdoException));
		} catch(Exception $exception) {
			throw(new Exception($exception->getMessage(), 0, $exception));
		}
	}

}

// TESTING ********************
//$businessesDate = DataDownloader::getLastModifiedDate("http://data.cabq.gov/business/LIVES/businesses.csv");
//$inspectionsDate = DataDownloader::getLastModifiedDate("http://data.cabq.gov/business/LIVES/inspections.csv");
//$violationsDate = DataDownloader::getLastModifiedDate("http://data.cabq.gov/business/LIVES/violations.csv");
//$xmlDate = DataDownloader::getLastModifiedDate("http://data.cabq.gov/business/foodinspections/FoodInspectionsCurrentFY-en-us.xml");
//$importantDate = DataDownloader::getLastModifiedDate("https://bootcamp-coders.cnm.edu/~srexroad/text.txt");
//
//echo "<h2>Businesses:</h2><p>" . $businessesDate->format("Y-m-d H:i:s") . "</p>";
//echo "<h2>Inspections:</h2><p>" . $inspectionsDate->format("Y-m-d H:i:s") . "</p>";
//echo "<h2>Violations:</h2><p>" . $violationsDate->format("Y-m-d H:i:s") . "</p>";
//echo "<h2>XML:</h2><p>" . $xmlDate->format("Y-m-d H:i:s") . "</p>";
//echo "<h2>Important:</h2><p>" . $importantDate->format("Y-m-d H:i:s") . "</p>";
//
//// This deletes the files on the bootcamp server
//DataDownloader::deleteFiles("/var/lib/abq-data/", "businesses", ".csv");
//
//// This downloads the file to the bootcamp server
//DataDownloader::downloadIfNew("http://data.cabq.gov/business/LIVES/businesses.csv", "/var/lib/abq-data/", "businesses", ".csv");
DataDownloader::readBusinessesCSV("http://data.cabq.gov/business/LIVES/businesses.csv");
//DataDownloader::readViolationsCSV("http://data.cabq.gov/business/LIVES/violations.csv");
// TESTING ********************
