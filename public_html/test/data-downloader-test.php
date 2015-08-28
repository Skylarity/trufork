<?php

/**
 * Full PHPUnit test for the Data Downloader class
 *
 * @see Profile
 * @author Skyler Rexroad skyrex1095@gmail.com
 */
class DataDownloaderTest extends PHPUnit_Framework_TestCase {

	protected $testData = 'FA0117942,"SINNERS AND SAINTS BAR AND GRILL LLC",9800 MONTGOMERY BLVD NE STE 3,ALBUQUERQUE,NM,87111,5056205440' . PHP_EOL . 'FA0115526,"MCDONALDS OF MCMAHON",5700 MCMAHON BLVD NW,ALBUQUERQUE,NM,87114' . PHP_EOL . 'FA0115560,"ELEPHANT BAR RESTAURANT",2240 LOUISIANA BLVD NE STE 5A,ALBUQUERQUE,NM,87110,5058842355' . PHP_EOL . 'FA0115576,"BEEZ WIENER WAGON",200 LOMAS NW,ALBUQUERQUE,NM,87102,5053458667' . PHP_EOL . 'FA0117064,"SIMPLY SWEET BY DARCI",1416 JUAN TABO NE,ALBUQUERQUE,NM,87112,5059225560' . PHP_EOL . 'FA0119118,"FORASTEROS MEXICAN FOOD # 2",5016 LOMAS BLVD NE,ALBUQUERQUE,NM,87110,6266079715' . PHP_EOL . 'FA0115149,"TOTAL NUTRITION",6550 PASEO DEL NORTE BLVD NE STE D3,ALBUQUERQUE,NM,87113,5055031715';

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

	public final function setUp() {
		$this->createTestFile("/var/lib/abq-data/", "businesses", DateTime::createFromFormat("U", "0"), ".csv");
	}

	public function testGetMetaData() {
		// TODO
	}

}
