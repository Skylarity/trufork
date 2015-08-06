<?php

/**
 * Full PHPUnit test for the Violation class
 *
 * @see Profile
 * @author Skyler Rexroad
 */

// Get the test parameters
require_once("trufork.php");

// Get the class to test
require_once(dirname(__DIR__) . "/php/classes/violation.php");

class ViolationTest extends TruForkTest {

	/**
	 * Valid ID to use
	 * @var int $VALID_ID
	 */
	protected $VALID_ID = null;

	/**
	 * Valid violation code to use
	 * @var int $violationCode
	 */
	protected $VALID_VIOLATION_CODE = "04  S8";

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
	protected $VALID_SERIAL_NUM = "3gj5wm35n45b3e4";

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
		$this->assertSame($numRows + 1, $this->getConnection()->getRowCount("restaurant"));
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

}