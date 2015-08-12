
<?php

/**
 * Full PHPUnit test for the Violation class
 *
 * @see Profile
 * @author Skyler Rexroad skyrex1095@gmail.com
 */

// Get the test parameters
require_once("trufork.php");

// Get the class to test
require_once(dirname(__DIR__) . "/php/classes/violation.php");

// Get the foreign class
require_once(dirname(__DIR__) . "/php/classes/restaurant.php");

class ViolationTest extends TruForkTest {

	/**
	 * Valid ID to use
	 * @var int $VALID_ID
	 */
        	protected $VALID_ID = null;

	/**
	 * Valid violation code to use
	 * @var int $VALID_VIOLATION_CODE
	 */
	protected $VALID_VIOLATION_CODE = "04  S8";

	/**
	 * Second valid violation code to use
	 * @var int $VALID_VIOLATION_CODE2
	 */
	protected $VALID_VIOLATION_CODE2 = "04  S9";

	/**
	 * Valid violation description to use
	 * @var string $VALID_VIOLATION_DESC
	 */
	protected $VALID_VIOLATION_DESC = "OBSERVED HAND WASHING SINKS NOT PROPERLY STOCKED OR CONVENIENTLY LOCATED.";

	/**
	 * Valid inspection memo to use
	 * @var string $VALID_INSPECTION_MEMO
	 */
	protected $VALID_INSPECTION_MEMO = "OBSERVED HAND WASHING SINKS NOT PROPERLY STOCKED OR CONVENIENTLY LOCATED.";

	/**
	 * Valid serial number to use
	 * @var string $VALID_SERIAL_NUM
	 */
	protected $VALID_SERIAL_NUM = "21j7dg0or7ks";

	/**
	 * Restaurant that has the violation(s); this is for foreign key relations
	 * @var Violation $restaurant
	 */
	protected $restaurant = null;

	/**
	 * Create dependent objects before running each test
	 */
	public final function setUp() {
		// Run the default setUp() method first
		parent::setUp();
		// create and insert a Profile to own the test Tweet
		$this->restaurant = new Restaurant(null, "3498bf923f893", "FD8098S", "NAME", "123 ADDRESS ST", "505555555", "5");
		$this->restaurant->insert($this->getPDO());
	}

	/**
	 * Test inserting a valid Violation and verify that the actual MySQL data matches
	 */
	public function testInsertValidViolation() {
		// Count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("violation");

		// Create a new Violation and insert it into MySQL
		$violation = new Violation($this->VALID_ID, $this->restaurant->getRestaurantId(), $this->VALID_VIOLATION_CODE, $this->VALID_VIOLATION_DESC, $this->VALID_INSPECTION_MEMO, $this->VALID_SERIAL_NUM);
		$violation->insert($this->getPDO());

		// Grab the data from MySQL and enforce the fields match out expectations
		$pdoViolation = Violation::getViolationById($this->getPDO(), $violation->getViolationId());
		$this->assertSame($numRows + 1, $this->getConnection()->getRowCount("violation"));
		$this->assertLessThan($pdoViolation->getViolationId(), 0);
		$this->assertSame($pdoViolation->getRestaurantId(), $this->restaurant->getRestaurantId());
		$this->assertSame($pdoViolation->getViolationCode(), $this->VALID_VIOLATION_CODE);
		$this->assertSame($pdoViolation->getViolationDesc(), $this->VALID_VIOLATION_DESC);
		$this->assertSame($pdoViolation->getInspectionMemo(), $this->VALID_INSPECTION_MEMO);
		$this->assertSame($pdoViolation->getSerialNum(), $this->VALID_SERIAL_NUM);
	}

	/**
	 * Test inserting a Violation that makes no sense
	 *
	 * @expectedException InvalidArgumentException
	 */
	public function testInsertInvalidViolation() {
		// Create a violation with no foreign key and watch it fail
		$violation = new Violation(null, null, $this->VALID_VIOLATION_CODE, $this->VALID_VIOLATION_DESC, $this->VALID_INSPECTION_MEMO, $this->VALID_SERIAL_NUM);
		$violation->insert($this->getPDO());
	}

	/**
	 * Test creating a violation, editing it, and then updating it
	 */
	public function testUpdateValidViolation() {
		// Count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("violation");

		// Create a new Violation and insert it into MySQL
		$violation = new Violation($this->VALID_ID, $this->restaurant->getRestaurantId(), $this->VALID_VIOLATION_CODE, $this->VALID_VIOLATION_DESC, $this->VALID_INSPECTION_MEMO, $this->VALID_SERIAL_NUM);
		$violation->insert($this->getPDO());

		// Edit the violation and update it in MySQL
		$violation->setViolationCode($this->VALID_VIOLATION_CODE2);
		$violation->update($this->getPDO());

		// Grab the data from MySQL and enforce the fields match out expectations
		$pdoViolation = Violation::getViolationById($this->getPDO(), $violation->getViolationId());
		$this->assertSame($numRows + 1, $this->getConnection()->getRowCount("violation"));
		$this->assertLessThan($pdoViolation->getViolationId(), 0);
		$this->assertSame($pdoViolation->getRestaurantId(), $this->restaurant->getRestaurantId());
		$this->assertSame($pdoViolation->getViolationCode(), $this->VALID_VIOLATION_CODE2);
		$this->assertSame($pdoViolation->getViolationDesc(), $this->VALID_VIOLATION_DESC);
		$this->assertSame($pdoViolation->getInspectionMemo(), $this->VALID_INSPECTION_MEMO);
		$this->assertSame($pdoViolation->getSerialNum(), $this->VALID_SERIAL_NUM);
	}

	/**
	 * Test updating a violation without inserting it
	 *
	 * @expectedException PDOException
	 */
	public function testUpdateInvalidViolation() {
		$violation = new Violation($this->VALID_ID, $this->restaurant->getRestaurantId(), $this->VALID_VIOLATION_CODE, $this->VALID_VIOLATION_DESC, $this->VALID_INSPECTION_MEMO, $this->VALID_SERIAL_NUM);
		$violation->update($this->getPDO());
	}

	/**
	 * Test creating a Violation and then deleting it
	 */
	public function testDeleteValidViolation() {
		// Count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("violation");

		// Create a new violation and insert it into MySQL
		$violation = new Violation($this->VALID_ID, $this->restaurant->getRestaurantId(), $this->VALID_VIOLATION_CODE, $this->VALID_VIOLATION_DESC, $this->VALID_INSPECTION_MEMO, $this->VALID_SERIAL_NUM);
		$violation->insert($this->getPDO());

		// delete the violation from mySQL
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("violation"));
		$violation->delete($this->getPDO());
		// grab the data from mySQL and enforce the violation does not exist
		$pdoViolation = Violation::getViolationByRestaurantId($this->getPDO(), $this->restaurant->getRestaurantId());
		$this->assertNull($pdoViolation);
		$this->assertEquals($numRows, $this->getConnection()->getRowCount("violation"));
	}

	/**
	 * Test grabbing a Violation that does not exist
	 *
	 * @expectedException PDOException
	 */
	public function testGetInvalidViolationByRestaurantId() {
		// Create a violation with no foreign key and watch it fail
		$violation = new Violation(TruForkTest::INVALID_KEY, TruForkTest::INVALID_KEY, $this->VALID_VIOLATION_CODE, $this->VALID_VIOLATION_DESC, $this->VALID_INSPECTION_MEMO, $this->VALID_SERIAL_NUM);
		$violation->insert($this->getPDO());
	}

	public function testGetValidViolationByString() {
		// Count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("violation");

		// Create a new Violation and insert it into MySQL
		$violation = new Violation($this->VALID_ID, $this->restaurant->getRestaurantId(), $this->VALID_VIOLATION_CODE, $this->VALID_VIOLATION_DESC, $this->VALID_INSPECTION_MEMO, $this->VALID_SERIAL_NUM);
		$violation->insert($this->getPDO());

		// Grab the data from MySQL and enforce the fields match out expectations - VIOLATION CODE
		$pdoViolations = Violation::getViolationByString($this->getPDO(), "violationCode", 8, $this->VALID_VIOLATION_CODE);
		foreach($pdoViolations as $pdoViolation) {
			$this->assertSame($numRows + 1, $this->getConnection()->getRowCount("violation"));
			$this->assertLessThan($pdoViolation->getViolationId(), 0);
			$this->assertSame($pdoViolation->getRestaurantId(), $this->restaurant->getRestaurantId());
			$this->assertSame($pdoViolation->getViolationCode(), $this->VALID_VIOLATION_CODE);
			$this->assertSame($pdoViolation->getViolationDesc(), $this->VALID_VIOLATION_DESC);
			$this->assertSame($pdoViolation->getInspectionMemo(), $this->VALID_INSPECTION_MEMO);
			$this->assertSame($pdoViolation->getSerialNum(), $this->VALID_SERIAL_NUM);
		}

		// Grab the data from MySQL and enforce the fields match out expectations - VIOLATION DESC
		$pdoViolations = Violation::getViolationByString($this->getPDO(), "violationDesc", 1024, $this->VALID_VIOLATION_DESC);
		foreach($pdoViolations as $pdoViolation) {
			$this->assertSame($numRows + 1, $this->getConnection()->getRowCount("violation"));
			$this->assertLessThan($pdoViolation->getViolationId(), 0);
			$this->assertSame($pdoViolation->getRestaurantId(), $this->restaurant->getRestaurantId());
			$this->assertSame($pdoViolation->getViolationCode(), $this->VALID_VIOLATION_CODE);
			$this->assertSame($pdoViolation->getViolationDesc(), $this->VALID_VIOLATION_DESC);
			$this->assertSame($pdoViolation->getInspectionMemo(), $this->VALID_INSPECTION_MEMO);
			$this->assertSame($pdoViolation->getSerialNum(), $this->VALID_SERIAL_NUM);
		}

		// Grab the data from MySQL and enforce the fields match out expectations - INSPECTION MEMO
		$pdoViolations = Violation::getViolationByString($this->getPDO(), "inspectionMemo", 1024, $this->VALID_INSPECTION_MEMO);
		foreach($pdoViolations as $pdoViolation) {
			$this->assertSame($numRows + 1, $this->getConnection()->getRowCount("violation"));
			$this->assertLessThan($pdoViolation->getViolationId(), 0);
			$this->assertSame($pdoViolation->getRestaurantId(), $this->restaurant->getRestaurantId());
			$this->assertSame($pdoViolation->getViolationCode(), $this->VALID_VIOLATION_CODE);
			$this->assertSame($pdoViolation->getViolationDesc(), $this->VALID_VIOLATION_DESC);
			$this->assertSame($pdoViolation->getInspectionMemo(), $this->VALID_INSPECTION_MEMO);
			$this->assertSame($pdoViolation->getSerialNum(), $this->VALID_SERIAL_NUM);
		}

		// Grab the data from MySQL and enforce the fields match out expectations - SERIAL NUMBER
		$pdoViolations = Violation::getViolationByString($this->getPDO(), "serialNum", 12, $this->VALID_SERIAL_NUM);
		foreach($pdoViolations as $pdoViolation) {
			$this->assertSame($numRows + 1, $this->getConnection()->getRowCount("violation"));
			$this->assertLessThan($pdoViolation->getViolationId(), 0);
			$this->assertSame($pdoViolation->getRestaurantId(), $this->restaurant->getRestaurantId());
			$this->assertSame($pdoViolation->getViolationCode(), $this->VALID_VIOLATION_CODE);
			$this->assertSame($pdoViolation->getViolationDesc(), $this->VALID_VIOLATION_DESC);
			$this->assertSame($pdoViolation->getInspectionMemo(), $this->VALID_INSPECTION_MEMO);
			$this->assertSame($pdoViolation->getSerialNum(), $this->VALID_SERIAL_NUM);
		}
	}

}