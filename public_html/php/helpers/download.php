<?php

require_once(dirname(__DIR__) . "/classes/data-downloader.php");

// This downloads the file to the bootcamp server
DataDownloader::downloadIfNew("http://data.cabq.gov/business/LIVES/businesses.csv", "/var/lib/abq-data/", "businesses", ".csv");
DataDownloader::downloadIfNew("http://data.cabq.gov/business/LIVES/violations.csv", "/var/lib/abq-data/", "violations", ".csv");
// This fills the database with information
DataDownloader::fillDatabase("/var/lib/abq-data/businesses", ".csv", "/var/lib/abq-data/violations", ".csv");
