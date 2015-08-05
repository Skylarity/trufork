<?php

/**
 * This class will download ABQ data about restaurant inspections
 *
 * @author Skyler Rexroad
 */
class DataDownloader {

	/*
	 * businesses: http://data.cabq.gov/business/LIVES/businesses.csv
	 * inspections: http://data.cabq.gov/business/LIVES/inspections.csv
	 * violations: http://data.cabq.gov/business/LIVES/violations.csv
	 * old xml: http://data.cabq.gov/business/foodinspections/FoodInspectionsCurrentFY-en-us.xml
	*/

	/**
	 * Grabs the metadata from a file url
	 *
	 * @param string $url url to grab from
	 * @return mixed stream data
	 */
	public static function getMetaData($url) {
		$context = stream_context_create(array("http" => array("method" => "HEAD")));
		$fd = fopen($url, "rb", false, $context);
		// var_dump(stream_get_meta_data($fd));

		// Grab the stream data
		$streamData = stream_get_meta_data($fd);

		fclose($fd);

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
			if(preg_match("/(Last-Modified)/", $data)) {
				$lastModified = $data;
				break;
			}
		}

		return $lastModified;
	}

	/**
	 * Downloads a file to a path from a url
	 * Code modified from a stackoverflow answer
	 *
	 * @param $url
	 * @param $path
	 */
	public static function downloadFile($url, $path) {
		$newFile = null;
		$newFileName = $path;
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
		}

		if($newFile) {
			fclose($newFile);
		}
	}
}

echo "<h2>Businesses:</h2><p>" . DataDownloader::getLastModified("http://data.cabq.gov/business/LIVES/businesses.csv") . "</p>";
echo "<h2>Inspections:</h2><p>" . DataDownloader::getLastModified("http://data.cabq.gov/business/LIVES/inspections.csv") . "</p>";
echo "<h2>Violations:</h2><p>" . DataDownloader::getLastModified("http://data.cabq.gov/business/LIVES/violations.csv") . "</p>";
echo "<h2>XML:</h2><p>" . DataDownloader::getLastModified("http://data.cabq.gov/business/foodinspections/FoodInspectionsCurrentFY-en-us.xml") . "</p>";

// This downloads the file to the server's temporary directory
// DataDownloader::downloadFile("http://data.cabq.gov/business/LIVES/businesses.csv", "/tmp/businesses.csv");