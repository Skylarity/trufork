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
	 * @param string $urlBegin beginning of url to grab file at
	 * @param string $urlEnd end of url to grab file at
	 * @throws PDOException PDO related errors
	 * @throws Exception catch-all exception
	 */
	public static function readBusinessesCSV($urlBegin, $urlEnd) {
		$urls = glob("$urlBegin*$urlEnd");
		if(count($urls) > 0) {
			$url = $urls[0];
		}

		$context = stream_context_create(array("http" => array("ignore_errors" => true, "method" => "GET")));

		try {
			$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/trufork.ini");

			if(($fd = @fopen($url, "rb", false, $context)) !== false) {
				fgetcsv($fd, 0, ",");
				while((($data = fgetcsv($fd, 0, ",")) !== false) && feof($fd) === false) {
					$restaurantId = null;
					$googleId = "";
					$facilityKey = $data[0];
					$name = $data[1];
					$address = $data[2];
					$phone = $data[6];
					$forkRating = 0;

					// Convert everything to UTF-8
					$facilityKey = mb_convert_encoding($facilityKey, "UTF-8", "UTF-16");
					$name = mb_convert_encoding($name, "UTF-8", "UTF-16");
					$name = str_replace("\"", "", $name); // The city gives names quotes for some reason
					$address = mb_convert_encoding($address, "UTF-8", "UTF-16");
					$phone = mb_convert_encoding($phone, "UTF-8", "UTF-16");

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
	 * @param string $urlBegin beginning of url to grab file at
	 * @param string $urlEnd end of url to grab file at
	 * @throws PDOException PDO related errors
	 * @throws Exception catch-all exception
	 */
	public static function readViolationsCSV($urlBegin, $urlEnd) {
		$urls = glob("$urlBegin*$urlEnd");
		if(count($urls) > 0) {
			$url = $urls[0];
		} else {
			throw(new RuntimeException("No file exists at specified location"));
		}

		$context = stream_context_create(array("http" => array("ignore_errors" => true, "method" => "GET")));

		try {
			$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/trufork.ini");

			if(($csvData = file_get_contents($url, null, $context)) !== false) {

//				$violations = [];

				if(($utfFd = @fopen("php://memory", "wb+")) === false) {
					throw(new RuntimeException("Memory Error: I can't remember"));
				}
				$csvData = mb_convert_encoding($csvData, "UTF-8", "UTF-16");
				$bytes = fwrite($utfFd, $csvData);
				if(strlen($csvData) !== $bytes) {
					throw(new RuntimeException("Insufficient Data"));
				}
				rewind($utfFd);

				$lines = 0;
				while(feof($utfFd) !== true) {
					$line = fgets($utfFd, 1024);
					$lines = $lines + substr_count($line, PHP_EOL);
				}
				$lines--;
				rewind($utfFd);
				$violations = new SplFixedArray($lines);

				fgetcsv($utfFd, 0, ",");
				while((($data = fgetcsv($utfFd, 0, ",", "\"")) !== false) && feof($utfFd) !== true) {
					$facilityKey = $data[0];
					$violationId = null;

					$restaurant = Restaurant::getRestaurantByFacilityKey($pdo, $facilityKey);
//					var_dump($restaurant);

					if($restaurant !== null) {
						$restaurantId = $restaurant->getRestaurantId();
						$violationCode = $data[2];
						$violationDesc = $data[3];
						$inspectionMemo = $violationDesc; // This attribute probably shouldn't exist
						$serialNum = $violationCode; // This attribute also probably shouldn't exist

//						var_dump($violationDesc);
//						echo "<p>Create new violation:</p>";

						$violation = new Violation($violationId, $restaurantId, $violationCode, $violationDesc, $inspectionMemo, $serialNum);

						$violations[$violations->key()] = $violation;
						$violations->next();

//						var_dump($violation);
//						echo "<hr/>";
					}
				}
				fclose($utfFd);

				Violation::insertEnMasse($pdo, $violations);
			}
		} catch(PDOException $pdoException) {
			throw(new PDOException($pdoException->getMessage(), 0, $pdoException));
		} catch(Exception $exception) {
			throw(new Exception($exception->getMessage(), 0, $exception));
		}
	}

	/**
	 * Fills the database with restaurants and violations
	 *
	 * @param string $businessesUrlBegin businesses.csv url beginning
	 * @param string $businessesUrlEnd businesses.csv url end
	 * @param string $violationsUrlBegin violations.csv url beginning
	 * @param string $violationsUrlEnd violations.csv url end
	 */
	public static function fillDatabase($businessesUrlBegin, $businessesUrlEnd, $violationsUrlBegin, $violationsUrlEnd) {
		$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/trufork.ini");

		$forkRatingStorage = new SplObjectStorage();

		$size = $pdo->query("SELECT COUNT(restaurantId) FROM restaurant", PDO::FETCH_ASSOC); // TODO: Grab size of restaurant table
		var_dump($size);
		$forkRatings = new SplFixedArray($size);
		$facilityKeys = new SplFixedArray($size);
		$googleIds = new SplFixedArray($size);

		// TODO: Fill arrays with data

		$forkRatingStorage->attach($forkRatings);
		$forkRatingStorage->attach($facilityKeys);
		$forkRatingStorage->attach($googleIds);


		$pdo->query("SET FOREIGN_KEY_CHECKS = 0");
		$pdo->query("TRUNCATE violation");
		$pdo->query("TRUNCATE restaurant");
		$pdo->query("SET FOREIGN_KEY_CHECKS = 1");

		// Actually put the stuff back into the database
		DataDownloader::readBusinessesCSV($businessesUrlBegin, $businessesUrlEnd);
		DataDownloader::readViolationsCSV($violationsUrlBegin, $violationsUrlEnd);

		// TODO: Stick $forkRatingStorage data back in the database
	}

}
