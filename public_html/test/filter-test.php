<?php

// Get the test parameters
require_once("trufork.php");

// Get the class to test
require_once(dirname(__DIR__) . "/php/helpers/filter.php");

/**
 * Full PHPUnit test for the Filter class
 *
 * @see Profile
 * @author Skyler Rexroad skyrex1095@gmail.com
 */
class FilterTest extends PHPUnit_Framework_TestCase {

	/**
	 * Valid integer to use
	 * @var int $VALID_INT
	 */
	protected $VALID_INT = 42;

	/**
	 * Name to use for integer
	 * @var string $VALID_INT_NAME
	 */
	protected $VALID_INT_NAME = "Integer";

	/**
	 * Valid string to use
	 * @var string $VALID_STRING
	 */
	protected $VALID_STRING = "Ech my dad nose im";

	/**
	 * Name to use for string
	 * @var string $VALID_STRING_NAME
	 */
	protected $VALID_STRING_NAME = "String";

	/**
	 * Test filtering a valid integer
	 */
	public function testFilterValidInt() {
		$this->assertTrue(is_int(Filter::filterInt($this->VALID_INT, $this->VALID_INT_NAME)));
		$this->assertTrue(is_int(Filter::filterInt($this->VALID_INT, $this->VALID_INT_NAME, true)));
		$this->assertNull(Filter::filterInt(null, $this->VALID_INT_NAME, true));
	}

	/**
	 * Try to filter an invalid integer
	 *
	 * @expectedException InvalidArgumentException
	 * @expectedException RangeException
	 */
	public function testFilterInvalidInt() {
		Filter::filterInt("<script></script>", $this->VALID_INT_NAME);
		Filter::filterInt("<script></script>", $this->VALID_INT_NAME, true);
		Filter::filterInt(null, $this->VALID_INT_NAME);
		Filter::filterInt(-42, $this->VALID_INT_NAME);
		Filter::filterInt(-42, $this->VALID_INT_NAME, true);
	}

	/**
	 * Test filtering a valid string
	 */
	public function testFilterValidString() {
		$this->assertTrue(is_string(Filter::filterString($this->VALID_STRING, $this->VALID_STRING_NAME)));
		$this->assertTrue(is_string(Filter::filterString($this->VALID_STRING, $this->VALID_STRING_NAME, strlen($this->VALID_STRING))));
	}

	/**
	 * Try to filter an invalid string
	 *
	 * @expectedException InvalidArgumentException
	 * @expectedException RangeException
	 */
	public function testFilterInvalidString() {
		$this->assertTrue(is_string(Filter::filterString("<script></script>", $this->VALID_STRING_NAME)));
		$this->assertTrue(is_string(Filter::filterString("<script></script>", $this->VALID_INT_NAME, 1)));
	}

}