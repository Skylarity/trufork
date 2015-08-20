<?php

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
	 * This function grabs a .csv file and reads it
	 *
	 * @param string $url url to grab file at
	 */
	public static function readCSV($url) {
		$context = stream_context_create(array("http" => array("ignore_errors" => true, "method" => "GET")));

		$row = 1;
		if(($fd = @fopen($url, "rb", false, $context)) !== false) {
			while(($data = fgetcsv($fd, 0, ",")) !== false) {
				echo $data[0] . " " . $data[1] . " " . $data[2] . " " . $data[6];
//				$restaurant = new Restaurant(null, "", $data[0], $data[1], $data[2], $data[6], "");
//				$restaurant->insert();
				$row++;
			}

			fclose($fd);
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
DataDownloader::readCSV("http://data.cabq.gov/business/LIVES/businesses.csv");
// TESTING ********************
