<?php

// Get test parameters
require_once("trufork.php");

// Get the class to test
require_once(dirname(__DIR__) . "/php/classes/data-downloader.php");

/**
 * Full PHPUnit test for the Data Downloader class
 *
 * @see Profile
 * @author Skyler Rexroad skyrex1095@gmail.com
 */
class DataDownloaderTest extends PHPUnit_Framework_TestCase {

	/**
	 * Valid test data to use
	 * @var string $testData
	 */
	protected $testData = 'FA0117942,"SINNERS AND SAINTS BAR AND GRILL LLC",9800 MONTGOMERY BLVD NE STE 3,ALBUQUERQUE,NM,87111,5056205440' . PHP_EOL . 'FA0115526,"MCDONALDS OF MCMAHON",5700 MCMAHON BLVD NW,ALBUQUERQUE,NM,87114' . PHP_EOL . 'FA0115560,"ELEPHANT BAR RESTAURANT",2240 LOUISIANA BLVD NE STE 5A,ALBUQUERQUE,NM,87110,5058842355' . PHP_EOL . 'FA0115576,"BEEZ WIENER WAGON",200 LOMAS NW,ALBUQUERQUE,NM,87102,5053458667' . PHP_EOL . 'FA0117064,"SIMPLY SWEET BY DARCI",1416 JUAN TABO NE,ALBUQUERQUE,NM,87112,5059225560' . PHP_EOL . 'FA0119118,"FORASTEROS MEXICAN FOOD # 2",5016 LOMAS BLVD NE,ALBUQUERQUE,NM,87110,6266079715' . PHP_EOL . 'FA0115149,"TOTAL NUTRITION",6550 PASEO DEL NORTE BLVD NE STE D3,ALBUQUERQUE,NM,87113,5055031715';

	/**
	 * Valid file to grab
	 * @var string $fileToGrab
	 */
	protected $fileToGrab = "https://bootcamp-coders.cnm.edu/~srexroad/businesses.csv";

	/**
	 * Valid file path to use
	 * @var string $filePath
	 */
	protected $filePath = "/tmp/";

	/**
	 * Valid file name to use
	 * @var string $fileName
	 */
	protected $fileName = "businesses";

	/**
	 * Valid file time to use
	 * @var string $fileTime
	 */
	protected $fileTime = "0";

	/**
	 * Valid file extension to use
	 * @var string $fileExtension
	 */
	protected $fileExtension = ".csv";

	/**
	 * Creates a test file for... you guessed it... testing
	 *
	 * @param string $path file path
	 * @param string $name file name
	 * @param string $time file time
	 * @param string $extension file extension
	 */
	public function createTestFile($path, $name, $time, $extension) {
		// Delete file(s)
		$files = glob("$path$name*$extension");
		foreach($files as $file) {
			unlink($file);
		}

		// New filename
		$newFile = $path . $name . $time . $extension;

		// Write to new file
		$handle = fopen($newFile, "w");
		fwrite($handle, $this->testData);
		fclose($handle);
	}

	/**
	 * Create test file before running test
	 */
	public final function setUp() {
		$this->createTestFile($this->filePath, $this->fileName, $this->fileTime, $this->fileExtension);
	}

	/**
	 * Test getting metadata from a file on the bootcamp server
	 */
	public function testGetMetaData() {
		// Grab the metadata from a file on the bootcamp server
		$streamData = DataDownloader::getMetaData($this->fileToGrab);

		// Assert the metadata contains "Last-Modified"
		$this->assertContains("Last-Modified", $streamData);
	}

	/**
	 * Grab from an invalid url
	 *
	 * @expectedException Exception
	 */
	public function testGetInvalidMetaData() {
		// Grab the metadata from a file on the bootcamp server
		$streamData = DataDownloader::getMetaData($this->fileToGrab . "x");

		$wrapperData = $streamData["wrapper_data"];

		// Assert the status is 404
		$this->assertContains("404", $wrapperData);
	}

	public function testGetLastModified() {
		// Grab the "Last-Modified" attribute
		$lastModified = DataDownloader::getLastModified($this->fileToGrab);

		// Assert we got a string that contains "Last-Modified"
		$this->assertContains("Last-Modified", $lastModified);
	}

	public function testGetLastModifiedDate() {
		// Grab the last modified date
		$lastModifiedDate = DataDownloader::getLastModifiedDate($this->fileToGrab);

		// Make sure we got a DateTime object
		$this->assertInstanceOf("DateTime", $lastModifiedDate);
	}

	/**
	 * Test getting a date from a stored file, using our date system
	 */
	public function testGetDateFromStoredFile() {
		// Grab the date off of the file on the bootcamp server
		$date = DataDownloader::getDateFromStoredFile($this->filePath, $this->fileName, $this->fileExtension);

		// Make sure we got a DateTime object
		$this->assertInstanceOf("DateTime", $date);
	}

	/**
	 * Test only downloading a file if we have an older version
	 */
	public function testDownloadIfNew() {
		// Try to download the file (true if it did, false if not)
		$this->assertTrue(DataDownloader::downloadIfNew($this->fileToGrab, $this->filePath, $this->fileName, $this->fileExtension));

		// See what files are there
		$files = glob("$this->filePath$this->fileName*$this->fileExtension");

		// See if they're there
		$this->assertNotEmpty($files);
	}

	/**
	 * Test deleting the files that we created and used for testing
	 */
	public function testDeleteFiles() {
		// MAKE SURE THIS FUNCTION IS CALLED LAST
		DataDownloader::deleteFiles($this->filePath, $this->fileName, $this->fileExtension);

		// See what files are there
		$files = glob("$this->filePath$this->fileName*$this->fileExtension");

		// See if they're GONE
		$this->assertEmpty($files);
	}

	/**
	 * Delete any left over files (if they exist)
	 */
	public final function tearDown() {
		// Delete files that weren't deleted by testing (even though this is the same way deleteFiles() does it, so...)
		$files = glob("$this->filePath$this->fileName*$this->fileExtension");
		foreach($files as $file) {
			unlink($file);
		}
	}

}
