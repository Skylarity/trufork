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

	function getMetaData() {
		$context = stream_context_create(array("http" => array("method" => "HEAD")));
		$fd = fopen("http://data.cabq.gov/business/LIVES/businesses.csv", "rb", false, $context);
		var_dump(stream_get_meta_data($fd));
		fclose($fd);
	}
}

$test = new DataDownloader();
$test->getMetaData();