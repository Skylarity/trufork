<?php

/**
 * Full PHPUnit test for the Restaurant class
 *
 * @see Profile
 * @author Skyler Rexroad
 */

// Get the test parameters
require_once("trufork.php");

// Get the class to test
require_once(dirname(__DIR__) . "php/classes/restaurant.php");

class RestaurantTest extends TruForkTest {

	/**
	 * Valid ID to use
	 * @var int $VALID_ID
	 */
	protected $VALID_ID = 1;

	/**
	 * Valid Google ID to use
	 * @var string $VALID_GOOGLE_ID
	 */
	protected $VALID_GOOGLE_ID = "ChIJ6fRj1sudP4YR0Q6Z7BhX4UM";

	/**
	 * Valid facility key to use
	 * @var string $VALID_FACILITY_KEY
	 */
	protected $VALID_FACILITY_KEY = "FA0090658";

	/**
	 * Valid name to use
	 * @var string $VALID_NAME
	 */
	protected $VALID_NAME = "FRESHY'S BURGERS AND MORE";

	/**
	 * Second valid name to use
	 * @var string $VALID_NAME2
	 */
	protected $VALID_NAME2 = "RESTAURANT NAME OOOOOOOOOOOO";

	/**
	 * Valid address to use
	 * @var string $VALID_ADDRESS
	 */
	protected $VALID_ADDRESS = "2721 CARLISLE BLVD NE, ALBUQUERQUE NM, 87110";

	/**
	 * Valid phone to use
	 * @var string $VALID_PHONE
	 */
	protected $VALID_PHONE = "5054015018";

	/**
	 * Valid fork rating to use
	 * @var string $VALID_FORK_RATING
	 */
	protected $VALID_FORK_RATING = "5";

	/**
	 * Test inserting a valid Restaurant and verify that the actual MySQL data matches
	 */
	public function testInsertValidRestaurant() {
		// Count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("restaurant");

		// Create a new Restaurant and insert it into MySQL
		$restaurant = new Restaurant(null, $this->VALID_ID, $this->VALID_GOOGLE_ID, $this->VALID_FACILITY_KEY, $this->VALID_NAME, $this->VALID_ADDRESS, $this->VALID_PHONE, $this->VALID_FORK_RATING);
		$restaurant->insert($this->getPDO());

		// Grab the data from MySQL and enforce the fields match out expectations
		$pdoRestaurant = Restaurant::getRestaurantById($this->getPDO(), $restaurant->getRestaurantId());
		$this->assertSame($numRows + 1, $this->getConnection()->getRowCount("restaurant"));
		$this->assertSame($pdoRestaurant->getRestaurantId(), $this->VALID_ID);
		$this->assertSame($pdoRestaurant->getGoogleId(), $this->VALID_GOOGLE_ID);
		$this->assertSame($pdoRestaurant->getFacilityKey(), $this->VALID_FACILITY_KEY);
		$this->assertSame($pdoRestaurant->getName(), $this->VALID_NAME);
		$this->assertSame($pdoRestaurant->getAddress(), $this->VALID_ADDRESS);
		$this->assertSame($pdoRestaurant->getPhone(), $this->VALID_PHONE);
		$this->assertSame($pdoRestaurant->getForkRating(), $this->VALID_FORK_RATING);
	}

	/**
	 * Test inserting a Restaurant that already exists
	 *
	 * @expectedException PDOException
	 **/
	public function testInsertInvalidProfile() {
		// create a profile with a non null profileId and watch it fail
		$restaurant = new Restaurant(TruForkTest::INVALID_KEY, $this->VALID_ID, $this->VALID_GOOGLE_ID, $this->VALID_FACILITY_KEY, $this->VALID_NAME, $this->VALID_ADDRESS, $this->VALID_PHONE, $this->VALID_FORK_RATING);
		$restaurant->insert($this->getPDO());
	}

	/**
	 * Test inserting a Restaurant, editing it, and then updating it
	 **/
	public function testUpdateValidRestaurant() {
		// Count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("restaurant");

		// Create a new Restaurant and insert it into MySQL
		$restaurant = new Restaurant(null, $this->VALID_ID, $this->VALID_GOOGLE_ID, $this->VALID_FACILITY_KEY, $this->VALID_NAME, $this->VALID_ADDRESS, $this->VALID_PHONE, $this->VALID_FORK_RATING);
		$restaurant->insert($this->getPDO());

		// Edit the restaurant and update it in MySQL
		$restaurant->setName($this->VALID_NAME2);
		$restaurant->update($this->getPDO());

		// Grab the data from MySQL and enforce the fields match out expectations
		$pdoRestaurant = Restaurant::getRestaurantById($this->getPDO(), $restaurant->getRestaurantId());
		$this->assertSame($numRows + 1, $this->getConnection()->getRowCount("restaurant"));
		$this->assertSame($pdoRestaurant->getRestaurantId(), $this->VALID_ID);
		$this->assertSame($pdoRestaurant->getGoogleId(), $this->VALID_GOOGLE_ID);
		$this->assertSame($pdoRestaurant->getFacilityKey(), $this->VALID_FACILITY_KEY);
		$this->assertSame($pdoRestaurant->getName(), $this->VALID_NAME);
		$this->assertSame($pdoRestaurant->getAddress(), $this->VALID_ADDRESS);
		$this->assertSame($pdoRestaurant->getPhone(), $this->VALID_PHONE);
		$this->assertSame($pdoRestaurant->getForkRating(), $this->VALID_FORK_RATING);
	}

	/**
	 * Test updating a Restaurant that does not exist
	 *
	 * @expectedException PDOException
	 **/
	public function testUpdateInvalidProfile() {
		// create a Restaurant and try to update it without actually inserting it
		$restaurant = new Restaurant(null, $this->VALID_ID, $this->VALID_GOOGLE_ID, $this->VALID_FACILITY_KEY, $this->VALID_NAME, $this->VALID_ADDRESS, $this->VALID_PHONE, $this->VALID_FORK_RATING);
		$restaurant->update($this->getPDO());
	}

	/**
	 * Test creating a Restaurant and then deleting it
	 **/
	public function testDeleteValidRestaurant() {
		// Count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("restaurant");

		// Create a new Restaurant and insert it into MySQL
		$restaurant = new Restaurant(null, $this->VALID_ID, $this->VALID_GOOGLE_ID, $this->VALID_FACILITY_KEY, $this->VALID_NAME, $this->VALID_ADDRESS, $this->VALID_PHONE, $this->VALID_FORK_RATING);
		$restaurant->insert($this->getPDO());

		// Delete the Restaurant from MySQL
		$this->assertSame($numRows + 1, $this->getConnection()->getRowCount("restaurant"));
		$restaurant->delete($this->getPDO());

		// Grab the data from MySQL and enforce the Restaurant does not exist
		$pdoRestaurant = Restaurant::getRestaurantById($this->getPDO(), $restaurant->getRestaurantId());
		$this->assertNull($pdoRestaurant);
		$this->assertSame($numRows, $this->getConnection()->getRowCount("restaurant"));
	}

}